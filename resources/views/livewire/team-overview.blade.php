<div class="ui-data-dense space-y-8">
    {{-- Page header --}}
    <header class="ui-card ui-reveal-soft flex flex-col gap-5 bg-white p-6 dark:bg-gray-800/80 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-start gap-4">
            <div class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400 sm:flex">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Team Overview</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Task selesai per anggota terdaftar hari ini, dan ringkasan kinerja per bulan.
                </p>
            </div>
        </div>

        <div class="flex flex-col items-stretch gap-2 sm:flex-row sm:items-center sm:justify-end">
            <div class="hidden text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 sm:block">
                Periode
                <div class="mt-0.5 text-sm font-semibold normal-case text-gray-700 dark:text-gray-200">
                    {{ $monthLabel }}
                </div>
            </div>
            <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white p-1.5 shadow-sm dark:border-gray-700 dark:bg-gray-900/40">
                <button type="button" wire:click="previousMonth"
                    class="ui-btn-secondary !rounded-lg !px-2.5 !py-2"
                    title="Bulan sebelumnya" aria-label="Bulan sebelumnya">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <select wire:model.live="overviewMonth" class="ui-input !w-32 !py-1.5 text-sm" aria-label="Pilih bulan">
                    @foreach($monthOptions as $num => $label)
                        <option value="{{ $num }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select wire:model.live="overviewYear" class="ui-input !w-24 !py-1.5 text-sm" aria-label="Pilih tahun">
                    @foreach($yearOptions as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
                <button type="button" wire:click="nextMonth"
                    class="ui-btn-secondary !rounded-lg !px-2.5 !py-2"
                    title="Bulan berikutnya" aria-label="Bulan berikutnya">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </header>

    {{-- Daily chart: wire:ignore so Livewire month navigation does not destroy Apex instance --}}
    <section class="ui-card ui-reveal-soft overflow-hidden p-6 dark:border-gray-700">
        <div class="mb-5 flex flex-col gap-3 border-b border-gray-200 pb-4 dark:border-gray-700 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l3-3 4 4 5-5 6 6"/></svg>
                    </span>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white md:text-xl">Daily Completed Tasks by Member</h2>
                </div>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Task berstatus <span class="font-semibold text-gray-800 dark:text-gray-200">Finished</span>
                    yang diperbarui hari ini ({{ $todayLabel }}), per anggota aktif.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Total: {{ array_sum($dailyValues) }}
                </span>
                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 font-semibold text-blue-800 dark:bg-blue-900/40 dark:text-blue-200">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    Anggota: {{ count($dailyLabels) }}
                </span>
            </div>
        </div>

        @if(count($dailyLabels) === 0)
            <div class="ui-empty-state py-12 text-center text-sm text-gray-600 dark:text-gray-400">
                Belum ada pengguna aktif untuk ditampilkan.
            </div>
        @else
            <div wire:ignore class="rounded-xl bg-gray-50/60 p-2 dark:bg-gray-900/40">
                <div id="chart-daily-by-member" class="min-h-[320px]"></div>
            </div>
        @endif
    </section>

    {{-- Monthly cards --}}
    <section>
        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white md:text-2xl">
                    Team Overview <span class="text-primary dark:text-blue-400">{{ $monthLabel }}</span>
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Progres relatif dihitung dari penyelesaian tertinggi dalam bulan tersebut.
                </p>
            </div>
            <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                {{ $periodStart->format('d M Y') }} — {{ $periodEnd->format('d M Y') }}
            </div>
        </div>

        @if(count($teamCards) === 0)
            <div class="ui-empty-state ui-card rounded-2xl py-16 text-center">
                <p class="text-gray-600 dark:text-gray-400">Tidak ada anggota tim aktif.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($teamCards as $card)
                    <button
                        type="button"
                        wire:key="team-card-{{ $card['id'] }}"
                        wire:click="openMember({{ $card['id'] }})"
                        wire:loading.attr="disabled"
                        wire:target="openMember({{ $card['id'] }})"
                        aria-label="Lihat detail {{ $card['name'] }}"
                        class="ui-reveal-soft group relative flex flex-col items-center overflow-hidden rounded-2xl bg-gradient-to-b from-blue-500 to-blue-600 px-5 pb-5 pt-6 text-center text-white shadow-md shadow-blue-500/25 ring-1 ring-blue-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:ring-blue-500/40 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-300 disabled:opacity-70 dark:from-blue-600 dark:to-blue-800 dark:shadow-blue-900/40 dark:ring-blue-400/20 dark:focus-visible:ring-blue-400/60"
                        data-reveal-delay="{{ min($loop->index, 8) }}"
                    >
                        @if($card['is_top_performer'] && $card['done'] > 0)
                            <span class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-amber-950 shadow-sm">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1.5l2.4 5.4 5.9.6-4.5 4 1.4 5.8L10 14l-5.2 3.3L6.2 11.5 1.7 7.5l5.9-.6L10 1.5z"/></svg>
                                Top
                            </span>
                        @endif

                        <span class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/15 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white opacity-0 transition-opacity duration-200 group-hover:opacity-100 group-focus-visible:opacity-100">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M9 5l7 7-7 7"/></svg>
                            Detail
                        </span>

                        <span
                            class="mb-3 flex h-16 w-16 items-center justify-center rounded-full border-[3px] border-white/40 text-lg font-bold text-white shadow-inner ring-2 ring-white/10"
                            style="background-color: hsl({{ $card['avatar_hue'] }}, 65%, 42%)"
                        >
                            {{ $card['initials'] }}
                        </span>

                        <h3 class="line-clamp-1 text-base font-bold leading-tight tracking-tight">
                            {{ $card['name'] }}
                        </h3>
                        <p class="mt-0.5 line-clamp-1 text-xs font-medium text-blue-100">
                            {{ $card['jabatan'] }}
                        </p>

                        <div class="mt-4 flex w-full items-baseline justify-between text-xs text-blue-100">
                            <span class="font-medium">Selesai</span>
                            <span class="font-bold text-white">{{ $card['done'] }}</span>
                        </div>
                        <div class="mt-1 h-2 w-full overflow-hidden rounded-full bg-white/25">
                            <div
                                class="h-full rounded-full bg-amber-400 transition-all duration-500"
                                style="width: {{ max($card['progress'], $card['done'] > 0 ? 6 : 0) }}%"
                            ></div>
                        </div>

                        <p class="mt-3 text-[11px] font-medium uppercase tracking-wide text-blue-100/90">
                            {{ $card['done'] }} tugas selesai bulan ini
                        </p>

                        <span class="absolute inset-x-0 bottom-0 h-1 origin-left scale-x-0 bg-amber-400 transition-transform duration-300 group-hover:scale-x-100 group-focus-visible:scale-x-100"></span>
                    </button>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Hall of Fame: Best Employee history --}}
    <section class="ui-card ui-reveal-soft overflow-hidden p-6 dark:border-gray-700">
        <div class="mb-5 flex flex-col gap-2 border-b border-gray-200 pb-4 dark:border-gray-700 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1.5l2.4 5.4 5.9.6-4.5 4 1.4 5.8L10 14l-5.2 3.3L6.2 11.5 1.7 7.5l5.9-.6L10 1.5z"/></svg>
                    </span>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white md:text-xl">Hall of Fame · Best Employee</h2>
                </div>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Riwayat anggota dengan task selesai terbanyak di tiap bulan (12 bulan terakhir).
                </p>
            </div>
        </div>

        @php
            $hasAnyWinner = collect($achievementMonths)->contains(fn ($m) => count($m['winners']) > 0);
        @endphp

        @if(! $hasAnyWinner)
            <div class="ui-empty-state py-10 text-center text-sm text-gray-600 dark:text-gray-400">
                Belum ada riwayat pencapaian. Selesaikan task untuk mulai mengisi papan ini.
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.6fr,1fr]">
                {{-- Per month timeline --}}
                <div>
                    <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Riwayat per bulan</h3>
                    <ul class="relative space-y-3 border-l border-gray-200 pl-5 dark:border-gray-700">
                        @foreach($achievementMonths as $month)
                            <li class="relative" wire:key="hof-month-{{ $month['ym'] ?? $loop->index }}">
                                <span class="absolute -left-[27px] top-1.5 flex h-4 w-4 items-center justify-center rounded-full
                                    {{ count($month['winners']) > 0 ? 'bg-amber-400 ring-4 ring-amber-100 dark:ring-amber-900/40' : 'bg-gray-300 ring-4 ring-gray-100 dark:bg-gray-600 dark:ring-gray-800' }}">
                                </span>
                                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $month['short'] }}</span>
                                        @if($month['is_current'])
                                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-blue-700 dark:bg-blue-900/40 dark:text-blue-200">Bulan ini</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        @forelse($month['winners'] as $winner)
                                            <button
                                                type="button"
                                                wire:key="hof-winner-{{ $month['ym'] ?? $loop->parent->index }}-{{ $winner['user_id'] }}"
                                                wire:click="openMember({{ $winner['user_id'] }})"
                                                class="group inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-900 transition hover:border-amber-300 hover:bg-amber-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 dark:border-amber-700/40 dark:bg-amber-900/30 dark:text-amber-100 dark:hover:bg-amber-900/50"
                                            >
                                                <span
                                                    class="flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold text-white"
                                                    style="background-color: hsl({{ $winner['avatar_hue'] }}, 65%, 42%)"
                                                >{{ $winner['initials'] }}</span>
                                                {{ $winner['name'] }}
                                                <span class="rounded-full bg-amber-400/30 px-1.5 text-[10px] font-bold text-amber-900 dark:bg-amber-300/20 dark:text-amber-100">
                                                    {{ $winner['count'] }}
                                                </span>
                                            </button>
                                        @empty
                                            <span class="text-xs italic text-gray-400 dark:text-gray-500">Tidak ada task selesai</span>
                                        @endforelse
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Leaderboard total wins per user --}}
                <div>
                    <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total kemenangan per anggota</h3>
                    @if(count($leaderboard) === 0)
                        <div class="rounded-xl border border-dashed border-gray-200 py-6 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            Belum ada anggota yang pernah jadi best employee.
                        </div>
                    @else
                        <ol class="space-y-2">
                            @foreach($leaderboard as $row)
                                @php
                                    $rank = $loop->iteration;
                                    $rankClass = match (true) {
                                        $rank === 1 => 'bg-amber-400 text-amber-950',
                                        $rank === 2 => 'bg-slate-300 text-slate-900 dark:bg-slate-200 dark:text-slate-900',
                                        $rank === 3 => 'bg-orange-300 text-orange-950',
                                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200',
                                    };
                                @endphp
                                <li wire:key="leaderboard-{{ $row['user_id'] }}">
                                    <button
                                        type="button"
                                        wire:click="openMember({{ $row['user_id'] }})"
                                        class="group flex w-full items-center justify-between gap-3 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-left transition hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 dark:border-gray-700 dark:bg-gray-900/40 dark:hover:border-amber-700/60"
                                    >
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold {{ $rankClass }}">
                                                {{ $rank }}
                                            </span>
                                            <span
                                                class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold text-white shadow-sm"
                                                style="background-color: hsl({{ $row['avatar_hue'] }}, 65%, 42%)"
                                            >{{ $row['initials'] }}</span>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $row['name'] }}</p>
                                                <p class="truncate text-[11px] text-gray-500 dark:text-gray-400">
                                                    Terakhir menang: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $row['last_win'] }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1.5l2.4 5.4 5.9.6-4.5 4 1.4 5.8L10 14l-5.2 3.3L6.2 11.5 1.7 7.5l5.9-.6L10 1.5z"/></svg>
                                            {{ $row['wins'] }}× menang
                                        </span>
                                    </button>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        @endif
    </section>

    @if(($showMemberModal ?? false) && ($memberDetail ?? null))
        @php
            $member = $memberDetail['user'];
            $kpi = $memberDetail['kpi'];
            $statusBreakdown = $memberDetail['status_breakdown'];
            $recentTasks = $memberDetail['recent_tasks'];
            $statusBadge = function (string $status) {
                $key = strtolower(str_replace([' ', '-'], '', $status));
                return match (true) {
                    str_contains($key, 'finished') => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200',
                    str_contains($key, 'preview') => 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-200',
                    str_contains($key, 'production') => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                    str_contains($key, 'crew') => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-200',
                    str_contains($key, 'script') => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200',
                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-700/50 dark:text-slate-200',
                };
            };
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="member-modal-title" wire:key="member-modal-{{ $member['id'] }}">
            <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                <div class="ui-modal-backdrop" wire:click="closeMember"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
                <div class="ui-modal-shell text-left sm:max-w-3xl"
                    x-data="{}"
                    x-init="$nextTick(() => $el.querySelector('[data-modal-close]')?.focus())"
                    @keydown.escape.window="$wire.closeMember()"
                >
                    {{-- Header --}}
                    <div class="relative bg-gradient-to-r from-primary to-blue-600 px-6 py-5 text-white">
                        <div class="flex items-start gap-4">
                            <span
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border-2 border-white/40 text-lg font-bold shadow-inner ring-2 ring-white/10"
                                style="background-color: hsl({{ $member['avatar_hue'] }}, 65%, 42%)"
                            >
                                {{ $member['initials'] }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <h3 id="member-modal-title" class="truncate text-xl font-bold tracking-tight">
                                    {{ $member['name'] }}
                                </h3>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-blue-100">
                                    <span class="rounded-full bg-white/15 px-2 py-0.5 font-semibold">{{ $member['jabatan'] }}</span>
                                    <span class="rounded-full bg-white/10 px-2 py-0.5 uppercase tracking-wide">{{ $member['role'] }}</span>
                                    @if($member['email'])
                                        <span class="truncate">· {{ $member['email'] }}</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-xs text-blue-100/90">
                                    Periode kinerja: <span class="font-semibold text-white">{{ $memberDetail['period_label'] }}</span>
                                </p>
                            </div>
                            <button
                                type="button"
                                data-modal-close
                                wire:click="closeMember"
                                class="rounded-full p-1.5 text-white/80 transition hover:bg-white/15 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60"
                                aria-label="Tutup"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="space-y-5 px-6 py-5">
                        {{-- KPI grid --}}
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                            @php
                                $kpis = [
                                    ['label' => 'Selesai bulan ini', 'value' => $kpi['done_this_month'], 'cls' => 'text-emerald-600 dark:text-emerald-400'],
                                    ['label' => 'Selesai hari ini', 'value' => $kpi['done_today'], 'cls' => 'text-blue-600 dark:text-blue-400'],
                                    ['label' => 'Sedang dikerjakan', 'value' => $kpi['in_progress'], 'cls' => 'text-amber-600 dark:text-amber-400'],
                                    ['label' => 'Overdue', 'value' => $kpi['overdue'], 'cls' => 'text-rose-600 dark:text-rose-400'],
                                    ['label' => 'Total task', 'value' => $kpi['total'], 'cls' => 'text-slate-700 dark:text-slate-200'],
                                ];
                            @endphp
                            @foreach($kpis as $item)
                                <div class="rounded-xl border border-gray-200 bg-white px-3 py-3 text-center shadow-sm dark:border-gray-700 dark:bg-gray-900/40">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $item['label'] }}</p>
                                    <p class="mt-1 text-2xl font-bold {{ $item['cls'] }}">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Status breakdown --}}
                        <div>
                            <h4 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Distribusi status</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($statusBreakdown as $row)
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusBadge($row['label']) }}">
                                        {{ $row['label'] }}
                                        <span class="rounded-full bg-white/70 px-1.5 text-[11px] font-bold text-gray-800 dark:bg-black/30 dark:text-white">
                                            {{ $row['count'] }}
                                        </span>
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Achievement / Best Employee history --}}
                        <div>
                            <div class="mb-2 flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1.5l2.4 5.4 5.9.6-4.5 4 1.4 5.8L10 14l-5.2 3.3L6.2 11.5 1.7 7.5l5.9-.6L10 1.5z"/></svg>
                                </span>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Pencapaian Best Employee</h4>
                                @if(count($memberDetail['win_history']) > 0)
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-bold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                                        {{ count($memberDetail['win_history']) }}× menang
                                    </span>
                                @endif
                            </div>
                            @if(count($memberDetail['win_history']) === 0)
                                <p class="rounded-xl border border-dashed border-gray-200 px-3 py-3 text-xs italic text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                    Belum pernah menjadi best employee dalam 12 bulan terakhir.
                                </p>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @foreach($memberDetail['win_history'] as $win)
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-900 dark:border-amber-700/40 dark:bg-amber-900/30 dark:text-amber-100">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1.5l2.4 5.4 5.9.6-4.5 4 1.4 5.8L10 14l-5.2 3.3L6.2 11.5 1.7 7.5l5.9-.6L10 1.5z"/></svg>
                                            {{ $win['label'] }}
                                            <span class="rounded-full bg-amber-400/30 px-1.5 text-[10px] font-bold text-amber-900 dark:bg-amber-300/20 dark:text-amber-100">
                                                {{ $win['count'] }} task
                                            </span>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Recent tasks --}}
                        <div>
                            <div class="mb-2 flex items-end justify-between">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Task terbaru</h4>
                                <a href="{{ route('reports', ['userId' => $member['id']]) }}"
                                   class="text-xs font-semibold text-primary hover:underline dark:text-blue-300">
                                    Buka di Reports →
                                </a>
                            </div>
                            @if($recentTasks->isEmpty())
                                <div class="ui-empty-state rounded-xl border border-dashed border-gray-200 py-6 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                    Belum ada task untuk anggota ini.
                                </div>
                            @else
                                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                                        <thead class="bg-gray-50/70 dark:bg-gray-900/40">
                                            <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                <th class="px-4 py-2">Judul</th>
                                                <th class="px-4 py-2">Status</th>
                                                <th class="hidden px-4 py-2 sm:table-cell">Priority</th>
                                                <th class="px-4 py-2 text-right">Diperbarui</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($recentTasks as $task)
                                                <tr class="bg-white transition-colors hover:bg-gray-50 dark:bg-gray-800/40 dark:hover:bg-gray-700/40">
                                                    <td class="max-w-[260px] px-4 py-2.5">
                                                        <a href="{{ route('tasks.show', ['id' => $task->id]) }}"
                                                           class="line-clamp-1 font-medium text-gray-900 hover:text-primary dark:text-gray-100 dark:hover:text-blue-300">
                                                            {{ $task->judul }}
                                                        </a>
                                                    </td>
                                                    <td class="px-4 py-2.5">
                                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $statusBadge((string) $task->status_tugas) }}">
                                                            {{ $task->status_tugas }}
                                                        </span>
                                                    </td>
                                                    <td class="hidden px-4 py-2.5 text-xs text-gray-600 sm:table-cell dark:text-gray-300">
                                                        {{ $task->priority ?? '—' }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-4 py-2.5 text-right text-xs text-gray-500 dark:text-gray-400"
                                                        title="{{ optional($task->diperbarui)->format('d M Y H:i') }}">
                                                        {{ optional($task->diperbarui)->diffForHumans() ?? '—' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex flex-col-reverse gap-2 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40 sm:flex-row sm:justify-end">
                        <button type="button" wire:click="closeMember" class="ui-btn-secondary px-4 py-2 text-sm">
                            Tutup
                        </button>
                        <a href="{{ route('reports', ['userId' => $member['id']]) }}" class="ui-btn-primary inline-flex items-center justify-center gap-2 px-4 py-2 text-sm">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Lihat semua task di Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Chart bootstrap data: kept as JSON-only payload so it survives Livewire morph
         (no executable JS runs from within the component template) --}}
    @if(count($dailyLabels) > 0)
        @php($dailyMemberPayload = ['labels' => $dailyLabels, 'values' => $dailyValues, 'yMax' => $dailyYAxisMax])
        <script type="application/json" id="partha-daily-member-data">{!! json_encode($dailyMemberPayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endif
</div>

@push('scripts')
<script>
    (function () {
        if (window.__parthaDailyMemberInit) return;
        window.__parthaDailyMemberInit = true;

        function readPayload() {
            var node = document.getElementById('partha-daily-member-data');
            if (!node) return null;
            try { return JSON.parse(node.textContent || '{}'); }
            catch (e) { return null; }
        }

        function isDark() { return document.documentElement.classList.contains('dark'); }
        function labelColor() { return isDark() ? '#9CA3AF' : '#4B5563'; }
        function gridColor() { return isDark() ? '#374151' : '#E5E7EB'; }

        function buildOptions(payload) {
            var palette = ['#8B5CF6', '#FB7185', '#22D3EE', '#FB923C', '#3B82F6', '#86EFAC', '#A855F7', '#14B8A6', '#EC4899'];
            var colors = (payload.labels || []).map(function (_, i) { return palette[i % palette.length]; });
            var lc = labelColor();
            return {
                series: [{ name: 'Task Done', data: payload.values || [] }],
                chart: { type: 'bar', height: 340, toolbar: { show: false }, fontFamily: 'Instrument Sans, ui-sans-serif, system-ui, sans-serif' },
                plotOptions: { bar: { horizontal: false, columnWidth: '52%', borderRadius: 8, distributed: true, dataLabels: { position: 'top' } } },
                dataLabels: { enabled: true, offsetY: -22, style: { fontSize: '12px', fontWeight: 700, colors: [lc] } },
                colors: colors,
                legend: { show: true, position: 'bottom', fontWeight: 600, labels: { colors: lc } },
                xaxis: {
                    categories: payload.labels || [],
                    title: { text: 'Task Done', style: { color: lc, fontWeight: 600 } },
                    labels: { style: { colors: lc, fontWeight: 600, fontSize: '12px' } },
                },
                yaxis: { min: 0, max: payload.yMax || 5, tickAmount: 4, labels: { style: { colors: lc, fontWeight: 600 } } },
                grid: { borderColor: gridColor(), strokeDashArray: 4 },
                tooltip: { theme: isDark() ? 'dark' : 'light' },
            };
        }

        function mount() {
            var el = document.querySelector('#chart-daily-by-member');
            if (!el || typeof ApexCharts === 'undefined') return false;
            var payload = readPayload();
            if (!payload || !(payload.labels || []).length) return false;
            if (window.__parthaDailyMemberChart) {
                try { window.__parthaDailyMemberChart.destroy(); } catch (e) {}
                window.__parthaDailyMemberChart = null;
            }
            var chart = new ApexCharts(el, buildOptions(payload));
            chart.render();
            window.__parthaDailyMemberChart = chart;
            return true;
        }

        function tryMount() {
            if (mount()) return;
            var attempts = 0;
            var iv = setInterval(function () {
                attempts += 1;
                if (mount() || attempts > 20) clearInterval(iv);
            }, 150);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', tryMount, { once: true });
        } else {
            tryMount();
        }

        document.addEventListener('livewire:navigated', tryMount);

        var darkObserver = new MutationObserver(function () {
            if (!window.__parthaDailyMemberChart) return;
            var c = labelColor();
            try {
                window.__parthaDailyMemberChart.updateOptions({
                    tooltip: { theme: isDark() ? 'dark' : 'light' },
                    xaxis: { labels: { style: { colors: c } }, title: { style: { color: c } } },
                    yaxis: { labels: { style: { colors: c } } },
                    legend: { labels: { colors: c } },
                    grid: { borderColor: gridColor() },
                    dataLabels: { style: { colors: [c] } },
                }, false, false);
            } catch (e) {}
        });
        darkObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    })();
</script>
@endpush
