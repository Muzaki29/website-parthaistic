<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use App\Services\TrelloService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class TrelloMapping extends Component
{
    /** Trello member id => local user id (or '' for unassigned) */
    public array $mapping = [];

    public string $flash = '';

    public string $error = '';

    /** Cached display data from Trello board */
    public array $trelloMembers = [];

    public function mount(TrelloService $trello): void
    {
        $this->authorizeAdmin();
        $this->loadMembers($trello);
    }

    protected function authorizeAdmin(): void
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk mengelola pemetaan Trello.');
        }
    }

    protected function loadMembers(TrelloService $trello, bool $useCache = true): void
    {
        $members = $trello->getBoardMembers($useCache);

        if ($members === []) {
            $this->error = 'Gagal mengambil member dari Trello. Periksa konfigurasi TRELLO_API_KEY / TRELLO_API_TOKEN / TRELLO_BOARD_ID.';
            $this->trelloMembers = [];

            return;
        }

        $this->trelloMembers = $members;

        $existing = User::query()
            ->whereNotNull('trello_member_id')
            ->pluck('id', 'trello_member_id')
            ->all();

        foreach ($members as $m) {
            $this->mapping[$m['id']] = isset($existing[$m['id']])
                ? (string) $existing[$m['id']]
                : '';
        }
    }

    public function refreshFromTrello(TrelloService $trello): void
    {
        $this->authorizeAdmin();
        $this->flash = '';
        $this->error = '';
        $this->loadMembers($trello, false);

        if ($this->error === '') {
            $this->flash = 'Daftar member Trello berhasil diperbarui.';
        }
    }

    /**
     * Persist the mapping selections back to users.trello_member_id.
     * Also unlinks users that are no longer the target of any Trello member.
     */
    public function saveMapping(): void
    {
        $this->authorizeAdmin();
        $this->flash = '';
        $this->error = '';

        $picked = collect($this->mapping)
            ->filter(fn ($v) => $v !== '' && $v !== null);

        if ($picked->duplicates()->isNotEmpty()) {
            $this->error = 'Satu user lokal tidak boleh dipasangkan ke lebih dari satu member Trello.';

            return;
        }

        $allowedTrelloIds = collect($this->trelloMembers)->pluck('id')->all();

        User::query()
            ->whereNotNull('trello_member_id')
            ->whereIn('trello_member_id', $allowedTrelloIds)
            ->update(['trello_member_id' => null]);

        foreach ($this->mapping as $trelloId => $userId) {
            if (! $userId) {
                continue;
            }
            $username = collect($this->trelloMembers)->firstWhere('id', $trelloId)['username'] ?? null;
            User::query()->whereKey((int) $userId)->update([
                'trello_member_id' => (string) $trelloId,
                'trello_username' => $username ?: null,
            ]);
        }

        $this->flash = 'Pemetaan member Trello berhasil disimpan.';
    }

    /**
     * Create a brand-new local employee tied to the Trello member.
     */
    public function createUserForMember(string $trelloId): void
    {
        $this->authorizeAdmin();
        $this->flash = '';
        $this->error = '';

        $member = collect($this->trelloMembers)->firstWhere('id', $trelloId);
        if (! $member) {
            $this->error = 'Member Trello tidak ditemukan.';

            return;
        }

        if (User::query()->where('trello_member_id', $trelloId)->exists()) {
            $this->error = 'Member ini sudah terhubung ke user lokal.';

            return;
        }

        $username = $member['username'] !== '' ? $member['username'] : Str::slug($member['fullName'], '');
        $email = strtolower($username).'@trello.team.parthaistic.com';
        $i = 1;
        while (User::query()->where('email', $email)->exists()) {
            $i++;
            $email = strtolower($username).$i.'@trello.team.parthaistic.com';
        }

        $user = User::create([
            'name' => $member['fullName'] !== '' ? $member['fullName'] : $username,
            'email' => $email,
            'password' => Hash::make(Str::random(40)),
            'role' => 'employee',
            'status_akun' => 'active',
            'jabatan' => 'Anggota Tim',
            'trello_member_id' => $trelloId,
            'trello_username' => $member['username'] ?: null,
        ]);

        $this->mapping[$trelloId] = (string) $user->id;
        $this->flash = "User baru \"{$user->name}\" dibuat dan ditautkan ke Trello.";
    }

    public function unlinkMember(string $trelloId): void
    {
        $this->authorizeAdmin();
        $this->flash = '';
        $this->error = '';

        $userId = $this->mapping[$trelloId] ?? null;
        if ($userId) {
            User::query()->whereKey((int) $userId)->update(['trello_member_id' => null]);
        }
        $this->mapping[$trelloId] = '';
        $this->flash = 'Tautan member Trello dilepas.';
    }

    public function runSync(TrelloService $trello): void
    {
        $this->authorizeAdmin();
        $this->flash = '';
        $this->error = '';

        $result = $trello->syncData();
        if (! empty($result['status'])) {
            $this->flash = $result['message'] ?? 'Sinkronisasi selesai.';
        } else {
            $this->error = $result['message'] ?? 'Sinkronisasi gagal.';
        }
    }

    public function render()
    {
        $users = User::query()->orderBy('name')->get(['id', 'name', 'role', 'email', 'trello_member_id']);

        $taskCounts = Task::query()
            ->selectRaw('assigned_to, COUNT(*) as total')
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to')
            ->pluck('total', 'assigned_to')
            ->all();

        return view('livewire.trello-mapping', [
            'users' => $users,
            'taskCounts' => $taskCounts,
        ])->layout('layouts.dashboard');
    }
}
