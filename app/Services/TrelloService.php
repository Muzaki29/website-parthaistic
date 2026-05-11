<?php

namespace App\Services;

use App\Models\Statistic;
use App\Models\SyncLog;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        if (! $userId || ! User::query()->whereKey($userId)->exists()) {
            $lock->release();

            return [
                'status' => false,
                'message' => 'Sesi pengguna tidak valid. Silakan login ulang lalu coba lagi.',
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

            if (isset($syncLog)) {
                $syncLog->update([
                    'status' => 'Gagal',
                    'keterangan' => substr($e->getMessage(), 0, 255),
                ]);
            }

            return [
                'status' => false,
                'message' => $e->getMessage(),
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
            throw new \Exception($this->trelloHttpErrorMessage('list', $response));
        }

        return $response->json();
    }

    protected function trelloHttpErrorMessage(string $context, Response $response): string
    {
        $status = $response->status();
        if ($status === 401 || $status === 403) {
            return 'Akses Trello ditolak. Periksa TRELLO_API_KEY dan TRELLO_API_TOKEN di pengaturan (.env).';
        }
        $json = $response->json();
        if (is_array($json) && ! empty($json['message']) && is_string($json['message'])) {
            $hint = trim($json['message']);
            if (stripos($hint, 'invalid key') !== false) {
                return 'Kunci API Trello tidak valid. Periksa TRELLO_API_KEY di .env.';
            }

            return 'Gagal mengambil data '.$context.' Trello: '.$hint;
        }

        return 'Gagal mengambil data '.$context.' Trello (HTTP '.$status.').';
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

    /**
     * Public, cached helper used by the admin "Pemetaan Trello" page.
     *
     * @return array<int, array{id:string,fullName:string,username:string,initials:string}>
     */
    public function getBoardMembers(bool $useCache = true): array
    {
        if (! $this->apiKey || ! $this->apiToken || ! $this->boardId) {
            return [];
        }

        $cacheKey = "trello:board:members:{$this->boardId}";
        if (! $useCache) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $members = $this->fetchMembers();

            return collect($members)
                ->map(fn ($m) => [
                    'id' => (string) ($m['id'] ?? ''),
                    'fullName' => (string) ($m['fullName'] ?? ''),
                    'username' => (string) ($m['username'] ?? ''),
                    'initials' => (string) ($m['initials'] ?? ''),
                ])
                ->filter(fn ($m) => $m['id'] !== '')
                ->sortBy('fullName')
                ->values()
                ->all();
        });
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
            throw new \Exception($this->trelloHttpErrorMessage('kartu', $response));
        }

        return $response->json();
    }

    protected function mapListsToStatus($lists)
    {
        $map = [];
        $configMap = array_flip(array_filter($this->listMap)); // id => type

        foreach ($lists as $list) {
            $id = $list['id'];
            $name = $list['name'] ?? '';

            if (isset($configMap[$id])) {
                $map[$id] = $this->normalizeStatus($configMap[$id]);

                continue;
            }

            $map[$id] = $this->detectStatusFromListName($name);
        }

        return $map;
    }

    /**
     * Best-effort fuzzy mapping of a Trello list name to one of Task::workflowStatuses().
     * Order matters: more specific patterns are tested before generic ones.
     */
    protected function detectStatusFromListName(string $name): string
    {
        $normalized = strtolower(trim($name));
        $compact = preg_replace('/[^a-z0-9]/', '', $normalized) ?? $normalized;

        // Most specific patterns first
        if (str_contains($compact, 'finished') || str_contains($compact, 'selesai')
            || str_contains($compact, 'completed') || str_contains($compact, 'complete')
            || str_contains($compact, 'done') || str_contains($compact, 'published')) {
            return Task::STATUS_FINISHED;
        }

        if (str_contains($compact, 'postproduction') || str_contains($compact, 'postprod')
            || str_contains($compact, 'editing') || str_contains($compact, 'edit')) {
            return Task::STATUS_POST_PRODUCTION;
        }

        if (str_contains($compact, 'crewcall') || str_contains($compact, 'shooting')
            || str_contains($compact, 'shoot') || str_contains($compact, 'callsheet')) {
            return Task::STATUS_CREW_CALL_SHOOTING;
        }

        if (str_contains($compact, 'production') || str_contains($compact, 'produksi')
            || str_contains($compact, 'progress') || str_contains($compact, 'doing')) {
            return Task::STATUS_PRODUCTION;
        }

        if (str_contains($compact, 'scriptpreview') || str_contains($compact, 'scriptdraftreview')) {
            return Task::STATUS_SCRIPT_PREVIEW;
        }

        if (str_contains($compact, 'scriptwritten') || str_contains($compact, 'scriptdraft')
            || str_contains($compact, 'scriptdone') || str_contains($compact, 'writtenscript')) {
            return Task::STATUS_SCRIPT_WRITTEN;
        }

        if (str_contains($compact, 'scriptidea') || str_contains($compact, 'ideascript')
            || (str_contains($compact, 'idea') && str_contains($compact, 'script'))) {
            return Task::STATUS_SCRIPT_IDEA;
        }

        if (str_contains($compact, 'preview') || str_contains($compact, 'review')
            || str_contains($compact, 'qc') || str_contains($compact, 'approval')) {
            return Task::STATUS_PREVIEW;
        }

        if (str_contains($compact, 'dropped') || str_contains($compact, 'dropidea')
            || str_contains($compact, 'archived') || str_contains($compact, 'rejected')
            || str_contains($compact, 'backlog') || str_contains($compact, 'idea')
            || str_contains($compact, 'todo') || str_contains($compact, 'inbox')) {
            return Task::STATUS_DROP_IDEA;
        }

        return Task::STATUS_DROP_IDEA;
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

    /**
     * Resolve every Trello member to a local user.
     *
     * Resolution order (first hit wins):
     *  1. users.trello_member_id  (manual link, most reliable)
     *  2. users.trello_username
     *  3. case-insensitive exact match on user.name vs Trello fullName / username
     *  4. fuzzy match: Trello fullName contains user.name (or vice versa), e.g.
     *     "Rizky Yudo Atmaja" ↔ "Rizky Yudo"
     *  5. auto-create a new employee user backed by the Trello member id
     *
     * @return array<string,int> Trello member id => local user id
     */
    protected function mapMembersToUsers($trelloMembers): array
    {
        $map = [];
        $users = User::query()->get();

        foreach ($trelloMembers as $member) {
            $trelloId = $member['id'] ?? null;
            if (! $trelloId) {
                continue;
            }

            $fullName = trim((string) ($member['fullName'] ?? ''));
            $username = trim((string) ($member['username'] ?? ''));

            $matched = $users->first(fn (User $u) => $u->trello_member_id === $trelloId);

            if (! $matched && $username !== '') {
                $matched = $users->first(fn (User $u) => strtolower((string) $u->trello_username) === strtolower($username));
            }

            if (! $matched) {
                $matched = $users->first(function (User $u) use ($fullName, $username) {
                    $name = strtolower((string) $u->name);

                    return $name !== '' && ($name === strtolower($fullName) || $name === strtolower($username));
                });
            }

            if (! $matched && $fullName !== '') {
                $matched = $this->fuzzyMatchUserByName($users, $fullName);
            }

            if (! $matched) {
                $matched = $this->autoCreateUserFromTrello($member);
                if ($matched) {
                    $users->push($matched);
                }
            } else {
                $this->backfillTrelloLink($matched, $trelloId, $username);
            }

            if ($matched) {
                $map[$trelloId] = $matched->id;
            }
        }

        return $map;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, User>  $users
     */
    protected function fuzzyMatchUserByName($users, string $fullName): ?User
    {
        $needle = strtolower($fullName);

        return $users->first(function (User $u) use ($needle) {
            $name = strtolower((string) $u->name);
            if ($name === '' || strlen($name) < 4) {
                return false;
            }

            return str_contains($needle, $name) || str_contains($name, $needle);
        });
    }

    protected function autoCreateUserFromTrello(array $member): ?User
    {
        $trelloId = (string) ($member['id'] ?? '');
        $fullName = trim((string) ($member['fullName'] ?? ''));
        $username = trim((string) ($member['username'] ?? ''));

        if ($trelloId === '' || ($fullName === '' && $username === '')) {
            return null;
        }

        $existing = User::query()->where('trello_member_id', $trelloId)->first();
        if ($existing) {
            return $existing;
        }

        $name = $fullName !== '' ? $fullName : $username;
        $emailSeed = $username !== '' ? $username : Str::slug($fullName, '');
        $email = strtolower($emailSeed).'@trello.team.parthaistic.com';

        $i = 1;
        while (User::query()->where('email', $email)->exists()) {
            $i++;
            $email = strtolower($emailSeed).$i.'@trello.team.parthaistic.com';
        }

        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(Str::random(40)),
            'role' => 'employee',
            'status_akun' => 'active',
            'jabatan' => 'Anggota Tim',
            'trello_member_id' => $trelloId,
            'trello_username' => $username !== '' ? $username : null,
        ]);
    }

    protected function backfillTrelloLink(User $user, string $trelloId, string $username): void
    {
        $updates = [];
        if (empty($user->trello_member_id)) {
            $updates['trello_member_id'] = $trelloId;
        }
        if ($username !== '' && empty($user->trello_username)) {
            $updates['trello_username'] = $username;
        }
        if ($updates !== []) {
            $user->forceFill($updates)->save();
        }
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
