<?php

namespace App\Services;

use App\Models\Statistic;
use App\Models\SyncLog;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrelloService
{
    protected $apiKey;

    protected $apiToken;

    protected $boardId;

    protected $listMap;

    public function __construct()
    {
        $this->apiKey = config('services.trello.api_key');
        $this->apiToken = config('services.trello.api_token');
        $this->boardId = config('services.trello.board_id');
        $this->listMap = config('services.trello.lists');
    }

    /**
     * Main method to sync data from Trello
     */
    public function syncData()
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
                    $map[$id] = 'To Do';
                } elseif (str_contains($name, 'doing') || str_contains($name, 'progress') || str_contains($name, 'on')) {
                    $map[$id] = 'Doing';
                } elseif (str_contains($name, 'done') || str_contains($name, 'selesai') || str_contains($name, 'complete')) {
                    $map[$id] = 'Done';
                } else {
                    $map[$id] = 'To Do'; // Default fallback
                }
            }
        }

        return $map;
    }

    protected function normalizeStatus($key)
    {
        return match ($key) {
            'todo' => 'To Do',
            'doing' => 'Doing',
            'done' => 'Done',
            default => 'To Do'
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
        $status = $statusMap[$card['idList']] ?? 'To Do';

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
                SUM(CASE WHEN status_tugas = 'To Do' THEN 1 ELSE 0 END) as total_todo,
                SUM(CASE WHEN status_tugas = 'Doing' THEN 1 ELSE 0 END) as total_doing,
                SUM(CASE WHEN status_tugas = 'Done' THEN 1 ELSE 0 END) as total_done
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
