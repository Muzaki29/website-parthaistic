<div class="ui-data-dense space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="mb-2 text-4xl font-bold text-gray-900 dark:text-white">Pemetaan Trello</h1>
            <p class="text-gray-600 dark:text-gray-400">
                Tautkan setiap member Trello ke user lokal supaya statistik & Best Employee terhitung benar.
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" wire:click="refreshFromTrello" wire:loading.attr="disabled"
                class="ui-btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Reload dari Trello</span>
            </button>
            <button type="button" wire:click="saveMapping" wire:loading.attr="disabled"
                class="ui-btn-primary inline-flex items-center gap-2 px-4 py-2 text-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Simpan Pemetaan</span>
            </button>
            <button type="button" wire:click="runSync" wire:loading.attr="disabled"
                class="ui-btn-primary inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-sm hover:from-emerald-700 hover:to-teal-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span>Sync Sekarang</span>
            </button>
        </div>
    </div>

    @if ($flash)
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 dark:border-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">
            <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ $flash }}</span>
        </div>
    @endif

    @if ($error)
        <div class="flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 dark:border-rose-700 dark:bg-rose-900/20 dark:text-rose-300">
            <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10A8 8 0 11.999 9.999 8 8 0 0118 10zm-7-4a1 1 0 10-2 0v4a1 1 0 102 0V6zm-1 8a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ $error }}</span>
        </div>
    @endif

    <div wire:loading.delay class="text-sm text-gray-500 dark:text-gray-400">Memproses...</div>

    <div class="ui-card ui-reveal-soft overflow-hidden transition-all duration-300">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-linear-to-r from-gray-50 to-gray-100/50 transition-colors duration-300 dark:from-gray-700 dark:to-gray-700/50">
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Member Trello</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Username</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">User Lokal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Task Tertaut</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($trelloMembers as $member)
                        @php
                            $linkedUserId = (int) ($mapping[$member['id']] ?? 0);
                            $linkedUser = $linkedUserId ? $users->firstWhere('id', $linkedUserId) : null;
                            $taskCount = $linkedUser ? ($taskCounts[$linkedUser->id] ?? 0) : 0;
                        @endphp
                        <tr wire:key="trello-{{ $member['id'] }}" class="transition-colors duration-300 hover:bg-gray-50/50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 text-sm font-bold text-white">
                                        {{ strtoupper($member['initials'] ?: substr($member['fullName'], 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $member['fullName'] ?: '—' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ Str::limit($member['id'], 12) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">@ {{ $member['username'] ?: '—' }}</td>
                            <td class="px-6 py-4">
                                <select wire:model.live="mapping.{{ $member['id'] }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-900/40">
                                    <option value="">— Belum ditautkan —</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} · {{ ucfirst($u->role) }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($linkedUser)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                        {{ $taskCount }} tugas
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if (! $linkedUser)
                                        <button type="button" wire:click="createUserForMember('{{ $member['id'] }}')"
                                            class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Buat User
                                        </button>
                                    @else
                                        <button type="button" wire:click="unlinkMember('{{ $member['id'] }}')"
                                            class="inline-flex items-center gap-1 rounded-lg bg-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                            Lepas Tautan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada member Trello yang ditemukan. Klik "Reload dari Trello" untuk mencoba lagi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:border-blue-700 dark:bg-blue-900/20 dark:text-blue-200">
        <p class="font-semibold">Tip:</p>
        <ul class="mt-1 list-disc space-y-1 pl-5">
            <li>Pasangkan tiap member Trello ke user lokal di kolom <strong>User Lokal</strong>, lalu klik <strong>Simpan Pemetaan</strong>.</li>
            <li>Member yang belum punya akun lokal: klik <strong>Buat User</strong> agar dibuat otomatis sebagai employee (email placeholder, password random).</li>
            <li>Setelah semua mapping benar, klik <strong>Sync Sekarang</strong> agar 2.300+ kartu Trello menempel ke user yang tepat. Best Employee di Team Overview akan langsung terisi.</li>
        </ul>
    </div>
</div>
