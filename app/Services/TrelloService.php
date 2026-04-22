<?php

namespace App\Services;

use App\Models\Statistic;
use App\Models\SyncLog;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrelloService
{
    protected ?string $apiKey;

    protected ?string $apiToken;

    protected ?string $boardId;

    protected array $listMap;

    public function __construct()
    {
        $this->apiKey = config('services.trello.key');
        $this->apiToken = config('services.trello.token');
        $this->boardId = config('services.trello.board_id');
        $this->listMap = config('services.trello.lists', []);
    }

    /**
     * Fetch and normalize Trello board cards.
     */
    public function getBoardCards(): array
    {
        if (! $this->apiKey || ! $this->apiToken || ! $this->boardId) {
            Log::warning('Trello credentials are incomplete.');

            return [];
        }

        $cacheKey = "trello:board:cards:{$this->boardId}";

        return Cache::remember($cacheKey, now()->addSeconds(60), function () {
            try {
                $lists = $this->fetchBoardListsForDisplay();
                $cards = $this->fetchBoardCardsForDisplay();

                if ($lists === [] && $cards === []) {
                    return [];
                }

                $listNameMap = collect($lists)
                    ->filter(fn ($list) => isset($list['id'], $list['name']))
                    ->mapWithKeys(fn ($list) => [$list['id'] => $list['name']])
                    ->all();

                return collect($cards)->map(function ($card) use ($listNameMap) {
                    return [
                        'id' => $card['id'] ?? null,
                        'name' => $card['name'] ?? '',
                        'description' => $card['desc'] ?? '',
                        'list_name' => $listNameMap[$card['idList'] ?? ''] ?? 'Unknown',
                        'labels' => collect($card['labels'] ?? [])
                            ->pluck('name')
                            ->filter()
                            ->values()
                            ->all(),
                        'due' => $card['due'] ?? null,
                        'url' => $card['url'] ?? null,
                    ];
                })->filter(fn ($card) => ! empty($card['id']))->values()->all();
            } catch (ConnectionException $exception) {
                Log::warning('Trello API timeout or network issue.', [
                    'message' => $exception->getMessage(),
                    'board_id' => $this->boardId,
                ]);

                return [];
            } catch (\Throwable $exception) {
                Log::error('Unexpected Trello fetch error.', [
                    'message' => $exception->getMessage(),
                    'board_id' => $this->boardId,
                ]);

                return [];
            }
        });
    }

    protected function fetchBoardListsForDisplay(): array
    {
        $response = Http::timeout(10)->get("https://api.trello.com/1/boards/{$this->boardId}/lists", [
            'key' => $this->apiKey,
            'token' => $this->apiToken,
            'fields' => 'id,name',
        ]);

        if ($response->failed()) {
            Log::warning('Failed fetching Trello lists for board display.', [
                'status' => $response->status(),
                'board_id' => $this->boardId,
            ]);

            return [];
        }

        return $response->json();
    }

    protected function fetchBoardCardsForDisplay(): array
    {
        $response = Http::timeout(15)->get("https://api.trello.com/1/boards/{$this->boardId}/cards", [
            'key' => $this->apiKey,
            'token' => $this->apiToken,
            'fields' => 'id,name,desc,idList,labels,due,url',
        ]);

        if ($response->failed()) {
            Log::warning('Failed fetching Trello cards for board display.', [
                'status' => $response->status(),
                'board_id' => $this->boardId,
            ]);

            return [];
        }

        return $response->json();
    }

    /**
     * Main method to sync data from Trello
     */
    public function syncData(): array
    {
        if (! $this->apiKey || ! $this->apiToken || ! $this->boardId) {
            return [
                'status' => false,
                'message' => 'Konfigurasi Trello belum lengkap. Periksa TRELLO_API_KEY, TRELLO_API_TOKEN, dan TRELLO_BOARD_ID.',
            ];
        }

        $lock = Cache::lock('trello:sync:lock', 120);
        if (! $lock->get()) {
            return [
                'status' => false,
                'message' => 'Sinkronisasi sedang berjalan. Coba lagi dalam beberapa saat.',
            ];
        }

        $startTime = now();

        $userId = auth()->id();
        if (! $userId) {
            $lock->release();

            return [
                'status' => false,
                'message' => 'Sesi pengguna tidak valid untuk melakukan sinkronisasi.',
            ];
        }

        $syncLog = SyncLog::create([
            'waktu_sinkron' => $startTime,
            'status' => 'Proses',
            'keterangan' => 'Memulai sinkronisasi...',
            'id_user' => $userId,
        ]);

        try {
            // 1. Fetch Lists for Status Mapping
            $lists = $this->fetchLists();
            $statusMap = $this->mapListsToStatus($lists);

            // 2. Fetch Members for User Mapping
            $members = $this->fetchMembers();
            $userMap = $this->mapMembersToUsers($members);

            // 3. Fetch Cards
            $cards = $this->fetchCards();

            // 4. Process Cards
            $count = 0;
            foreach ($cards as $card) {
                $this->processCard($card, $statusMap, $userMap, $syncLog->id);
                $count++;
            }

            // 5. Calculate Statistics
            $this->calculateStatistics();

            // 6. Update Log Success
            $syncLog->update([
                'status' => 'Sukses',
                'keterangan' => "Berhasil sinkronisasi $count kartu.",
            ]);

            return [
                'status' => true,
                'message' => "Sinkronisasi selesai. $count kartu diproses.",
            ];

        } catch (\Exception $e) {
            Log::error('Trello Sync Error', [
                'error' => $e->getMessage(),
                'board_id' => $this->boardId,
            ]);

            $syncLog->update([
                'status' => 'Gagal',
                'keterangan' => substr($e->getMessage(), 0, 255), // Truncate if too long
            ]);

            return [
                'status' => false,
                'message' => 'Sinkronisasi gagal. Silakan coba lagi atau hubungi admin sistem.',
            ];
        } finally {
            optional($lock)->release();
        }
    }

    protected function fetchLists()
    {
        $response = Http::timeout(10)
            ->retry(2, 300, throw: false)
            ->get("https://api.trello.com/1/boards/{$this->boardId}/lists", [
            'key' => $this->apiKey,
            'token' => $this->apiToken,
        ]);

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data list Trello.');
        }

        return $response->json();
    }

    protected function fetchMembers()
    {
        $response = Http::timeout(10)
            ->retry(2, 300, throw: false)
            ->get("https://api.trello.com/1/boards/{$this->boardId}/members", [
            'key' => $this->apiKey,
            'token' => $this->apiToken,
        ]);

        if ($response->failed()) {
            // Non-critical, just return empty
            Log::warning('Gagal mengambil member Trello', [
                'board_id' => $this->boardId,
                'status' => $response->status(),
            ]);

            return [];
        }

        return $response->json();
    }

    protected function fetchCards()
    {
        $response = Http::timeout(15)
            ->retry(2, 300, throw: false)
            ->get("https://api.trello.com/1/boards/{$this->boardId}/cards", [
            'key' => $this->apiKey,
            'token' => $this->apiToken,
            'fields' => 'id,name,desc,idList,dateLastActivity,idMembers',
        ]);

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data kartu Trello.');
        }

        return $response->json();
    }

    protected function mapListsToStatus($lists)
    {
        $map = [];
        $configMap = array_flip(array_filter($this->listMap)); // id => type

        foreach ($lists as $list) {
            $id = $list['id'];
            $name = strtolower($list['name']);

            if (isset($configMap[$id])) {
                $map[$id] = $this->normalizeStatus($configMap[$id]);
            } else {
                // Auto-detect by name
                if (str_contains($name, 'do') && ! str_contains($name, 'done')) {
                    $map[$id] = Task::STATUS_DROP_IDEA;
                } elseif (str_contains($name, 'doing') || str_contains($name, 'progress') || str_contains($name, 'on')) {
                    $map[$id] = Task::STATUS_PRODUCTION;
                } elseif (str_contains($name, 'done') || str_contains($name, 'selesai') || str_contains($name, 'complete')) {
                    $map[$id] = Task::STATUS_FINISHED;
                } else {
                    $map[$id] = Task::STATUS_DROP_IDEA; // Default fallback
                }
            }
        }

        return $map;
    }

    protected function normalizeStatus($key)
    {
        return match ($key) {
            'todo' => Task::STATUS_DROP_IDEA,
            'doing' => Task::STATUS_PRODUCTION,
            'done' => Task::STATUS_FINISHED,
            default => Task::STATUS_DROP_IDEA
        };
    }

    protected function mapMembersToUsers($trelloMembers)
    {
        $map = []; // TrelloMemberID => DBUserID
        $users = User::all();

        foreach ($trelloMembers as $member) {
            // Match by Name (Simple heuristic)
            // Ideally we match by email, but Trello API email field requires special permissions
            $matchedUser = $users->first(function ($user) use ($member) {
                return strtolower($user->name) === strtolower($member['fullName'])
                    || strtolower($user->name) === strtolower($member['username']);
            });

            if ($matchedUser) {
                $map[$member['id']] = $matchedUser->id;
            }
        }

        return $map;
    }

    protected function processCard($card, $statusMap, $userMap, $syncId)
    {
        $status = $statusMap[$card['idList']] ?? Task::STATUS_DROP_IDEA;

        // Determine Assignee
        $assignedTo = null;
        if (! empty($card['idMembers'])) {
            // Take the first member that matches a local user
            foreach ($card['idMembers'] as $memberId) {
                if (isset($userMap[$memberId])) {
                    $assignedTo = $userMap[$memberId];
                    break;
                }
            }
        }

        Task::updateOrCreate(
            ['id_kartu_trello' => $card['id']],
            [
                'judul' => $card['name'],
                'deskripsi' => $card['desc'],
                'status_tugas' => $status,
                'dibuat' => now(), // Should ideally be card creation date, but that requires parsing ID or another API call
                'diperbarui' => Carbon::parse($card['dateLastActivity']),
                'id_sinkron' => $syncId,
                'assigned_to' => $assignedTo,
            ]
        );
    }

    protected function calculateStatistics()
    {
        $rows = Task::query()
            ->selectRaw("
                assigned_to as id_user,
                SUM(CASE WHEN status_tugas IN ('Drop idea','Script idea','Script written','Script preview') THEN 1 ELSE 0 END) as total_todo,
                SUM(CASE WHEN status_tugas IN ('Crew call shooting','Production','Post - Production','Preview') THEN 1 ELSE 0 END) as total_doing,
                SUM(CASE WHEN status_tugas = 'Finished' THEN 1 ELSE 0 END) as total_done
            ")
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to')
            ->get();

        foreach ($rows as $row) {
            Statistic::updateOrCreate(
                ['id_user' => $row->id_user],
                [
                    'total_todo' => (int) $row->total_todo,
                    'total_doing' => (int) $row->total_doing,
                    'total_done' => (int) $row->total_done,
                    'diperbarui_pada' => now(),
                ]
            );
        }
    }
}
