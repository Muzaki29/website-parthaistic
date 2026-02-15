<?php

namespace App\Services;

use App\Models\Statistic;
use App\Models\SyncLog;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
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
        $startTime = now();

        // Use authenticated user or fallback to first admin/user for logging
        $userId = auth()->id();
        if (! $userId) {
            $firstUser = User::first();
            if (! $firstUser) {
                throw new \Exception('Tidak ada user di database. Silakan jalankan seeder terlebih dahulu.');
            }
            $userId = $firstUser->id;
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
            Log::error('Trello Sync Error: '.$e->getMessage());

            $syncLog->update([
                'status' => 'Gagal',
                'keterangan' => substr($e->getMessage(), 0, 255), // Truncate if too long
            ]);

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function fetchLists()
    {
        $response = Http::get("https://api.trello.com/1/boards/{$this->boardId}/lists", [
            'key' => $this->apiKey,
            'token' => $this->apiToken,
        ]);

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil List Trello: '.$response->body());
        }

        return $response->json();
    }

    protected function fetchMembers()
    {
        $response = Http::get("https://api.trello.com/1/boards/{$this->boardId}/members", [
            'key' => $this->apiKey,
            'token' => $this->apiToken,
        ]);

        if ($response->failed()) {
            // Non-critical, just return empty
            Log::warning('Gagal mengambil Member Trello: '.$response->body());

            return [];
        }

        return $response->json();
    }

    protected function fetchCards()
    {
        $response = Http::get("https://api.trello.com/1/boards/{$this->boardId}/cards", [
            'key' => $this->apiKey,
            'token' => $this->apiToken,
            'fields' => 'id,name,desc,idList,dateLastActivity,idMembers',
        ]);

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil Kartu Trello: '.$response->body());
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
        // Calculate for all users who have tasks
        $userIds = Task::whereNotNull('assigned_to')->distinct()->pluck('assigned_to');

        foreach ($userIds as $userId) {
            $stats = [
                'total_todo' => Task::where('assigned_to', $userId)->where('status_tugas', 'To Do')->count(),
                'total_doing' => Task::where('assigned_to', $userId)->where('status_tugas', 'Doing')->count(),
                'total_done' => Task::where('assigned_to', $userId)->where('status_tugas', 'Done')->count(),
                'diperbarui_pada' => now(),
            ];

            Statistic::updateOrCreate(
                ['id_user' => $userId],
                $stats
            );
        }
    }
}
