<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeamOverview extends Component
{
    public int $overviewMonth;

    public int $overviewYear;

    public bool $showMemberModal = false;

    public ?int $selectedMemberId = null;

    public function mount(): void
    {
        $this->overviewMonth = (int) now()->month;
        $this->overviewYear = (int) now()->year;
    }

    public function openMember(int $userId): void
    {
        $this->selectedMemberId = $userId;
        $this->showMemberModal = true;
    }

    public function closeMember(): void
    {
        $this->showMemberModal = false;
        $this->selectedMemberId = null;
    }

    public function updatedOverviewMonth(mixed $value): void
    {
        $this->overviewMonth = max(1, min(12, (int) $value));
    }

    public function updatedOverviewYear(mixed $value): void
    {
        $y = (int) $value;
        $this->overviewYear = max(2020, min((int) now()->format('Y') + 1, $y));
    }

    public function previousMonth(): void
    {
        $d = Carbon::createFromDate($this->overviewYear, $this->overviewMonth, 1)->subMonth();
        $this->overviewMonth = $d->month;
        $this->overviewYear = (int) $d->year;
    }

    public function nextMonth(): void
    {
        $d = Carbon::createFromDate($this->overviewYear, $this->overviewMonth, 1)->addMonth();
        $this->overviewMonth = $d->month;
        $this->overviewYear = (int) $d->year;
    }

    /**
     * @return Collection<int, User>
     */
    protected function teamMembers(): Collection
    {
        return User::query()
            ->where('status_akun', 'active')
            ->whereIn('role', ['admin', 'manager', 'employee'])
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        $members = $this->teamMembers();

        $today = today();
        $todayCounts = Task::query()
            ->where('status_tugas', Task::STATUS_FINISHED)
            ->whereDate('diperbarui', $today)
            ->whereNotNull('assigned_to')
            ->selectRaw('assigned_to, COUNT(*) as c')
            ->groupBy('assigned_to')
            ->pluck('c', 'assigned_to');

        $dailyLabels = [];
        $dailyValues = [];
        foreach ($members as $user) {
            $dailyLabels[] = $user->name;
            $dailyValues[] = (int) ($todayCounts[$user->id] ?? 0);
        }

        $maxDailyVal = $dailyValues === [] ? 0 : max($dailyValues);
        $dailyYAxisMax = max(8, (int) (ceil(max(1, $maxDailyVal) / 2) * 2));
        if ($dailyYAxisMax < $maxDailyVal) {
            $dailyYAxisMax = $maxDailyVal + 2;
        }

        $monthOptions = collect(range(1, 12))
            ->mapWithKeys(fn (int $m) => [
                $m => Carbon::createFromDate(2000, $m, 1)->locale('id')->translatedFormat('F'),
            ])
            ->all();

        $yearOptions = range((int) now()->format('Y') - 2, (int) now()->format('Y') + 1);

        $periodStart = Carbon::createFromDate($this->overviewYear, $this->overviewMonth, 1)->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();

        $monthCounts = Task::query()
            ->where('status_tugas', Task::STATUS_FINISHED)
            ->whereBetween('diperbarui', [$periodStart, $periodEnd])
            ->whereNotNull('assigned_to')
            ->selectRaw('assigned_to, COUNT(*) as c')
            ->groupBy('assigned_to')
            ->pluck('c', 'assigned_to');

        $maxDoneMonth = (int) ($monthCounts->max() ?? 0);

        $monthLabel = $periodStart->copy()->locale('id')->translatedFormat('F Y');

        $teamCards = $members->map(function (User $user) use ($monthCounts, $maxDoneMonth) {
            $done = (int) ($monthCounts[$user->id] ?? 0);
            $progress = $maxDoneMonth > 0 ? (int) round(($done / $maxDoneMonth) * 100) : 0;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'jabatan' => $user->jabatan ?: ucfirst($user->role),
                'role' => $user->role,
                'done' => $done,
                'progress' => $progress,
                'initials' => $this->initials($user->name),
                'avatar_hue' => ($user->id * 47) % 360,
            ];
        })->values()->all();

        $topDone = $maxDoneMonth;
        foreach ($teamCards as &$card) {
            $card['is_top_performer'] = $topDone > 0 && $card['done'] === $topDone;
        }
        unset($card);

        $achievements = $this->buildAchievements($members);

        $memberDetail = null;
        if ($this->showMemberModal && $this->selectedMemberId) {
            $memberDetail = $this->buildMemberDetail(
                $this->selectedMemberId,
                $periodStart,
                $periodEnd,
                $achievements['per_user'][$this->selectedMemberId] ?? null,
            );
        }

        return view('livewire.team-overview', [
            'dailyLabels' => $dailyLabels,
            'dailyValues' => $dailyValues,
            'dailyYAxisMax' => $dailyYAxisMax,
            'monthOptions' => $monthOptions,
            'yearOptions' => $yearOptions,
            'teamCards' => $teamCards,
            'monthLabel' => $monthLabel,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'todayLabel' => now()->locale('id')->translatedFormat('d M Y'),
            'memberDetail' => $memberDetail,
            'achievementMonths' => $achievements['months'],
            'leaderboard' => $achievements['leaderboard'],
            'showMemberModalState' => $this->showMemberModal,
        ])->layout('layouts.dashboard', [
            'title' => 'Team Overview',
        ]);
    }

    /**
     * Build best-employee history for the last N months (default 12).
     *
     * @param  Collection<int, User>  $members
     * @return array{
     *     months: array<int, array<string, mixed>>,
     *     per_user: array<int, array<int, array<string, mixed>>>,
     *     leaderboard: array<int, array<string, mixed>>,
     * }
     */
    protected function buildAchievements(Collection $members, int $monthsBack = 12): array
    {
        $end = now()->endOfMonth();
        $start = now()->copy()->subMonths($monthsBack - 1)->startOfMonth();

        $driver = DB::connection()->getDriverName();
        $ymExpr = match ($driver) {
            'sqlite' => "strftime('%Y-%m', diperbarui)",
            'pgsql' => "to_char(diperbarui, 'YYYY-MM')",
            default => "DATE_FORMAT(diperbarui, '%Y-%m')",
        };

        $rows = Task::query()
            ->where('status_tugas', Task::STATUS_FINISHED)
            ->whereBetween('diperbarui', [$start, $end])
            ->whereNotNull('assigned_to')
            ->selectRaw("$ymExpr as ym, assigned_to, COUNT(*) as c")
            ->groupBy('ym', 'assigned_to')
            ->get();

        $userMap = $members->keyBy('id');

        $byMonth = $rows->groupBy('ym');

        $months = [];
        $perUser = [];

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $ym = $cursor->format('Y-m');
            $monthEntries = $byMonth->get($ym, collect());

            $maxCount = (int) ($monthEntries->max('c') ?? 0);
            $winners = $maxCount > 0
                ? $monthEntries->where('c', $maxCount)->values()
                : collect();

            $monthLabel = $cursor->copy()->locale('id')->translatedFormat('F Y');

            $winnerData = [];
            foreach ($winners as $row) {
                $user = $userMap->get($row->assigned_to);
                if (! $user) {
                    continue;
                }

                $entry = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'jabatan' => $user->jabatan ?: ucfirst((string) $user->role),
                    'count' => (int) $row->c,
                    'initials' => $this->initials($user->name),
                    'avatar_hue' => ($user->id * 47) % 360,
                ];

                $winnerData[] = $entry;

                $perUser[$user->id][] = [
                    'ym' => $ym,
                    'label' => $monthLabel,
                    'count' => (int) $row->c,
                ];
            }

            $months[] = [
                'ym' => $ym,
                'label' => $monthLabel,
                'short' => $cursor->copy()->locale('id')->translatedFormat('M Y'),
                'is_current' => $cursor->isSameMonth(now()),
                'winners' => $winnerData,
            ];

            $cursor->addMonth();
        }

        $months = array_reverse($months);

        $leaderboard = [];
        foreach ($perUser as $userId => $wins) {
            $user = $userMap->get($userId);
            if (! $user) {
                continue;
            }

            usort($wins, fn ($a, $b) => strcmp($b['ym'], $a['ym']));

            $leaderboard[] = [
                'user_id' => $userId,
                'name' => $user->name,
                'jabatan' => $user->jabatan ?: ucfirst((string) $user->role),
                'initials' => $this->initials($user->name),
                'avatar_hue' => ($userId * 47) % 360,
                'wins' => count($wins),
                'last_win' => $wins[0]['label'] ?? null,
                'history' => $wins,
            ];
        }

        usort($leaderboard, function ($a, $b) {
            return $b['wins'] <=> $a['wins']
                ?: strcmp($b['last_win'] ?? '', $a['last_win'] ?? '');
        });

        return [
            'months' => $months,
            'per_user' => $perUser,
            'leaderboard' => $leaderboard,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $winHistory
     * @return array<string, mixed>|null
     */
    protected function buildMemberDetail(int $userId, Carbon $periodStart, Carbon $periodEnd, ?array $winHistory = null): ?array
    {
        $user = User::query()->find($userId);
        if (! $user) {
            return null;
        }

        $winHistory = $winHistory ?? [];
        usort($winHistory, fn ($a, $b) => strcmp($b['ym'] ?? '', $a['ym'] ?? ''));

        $statusLabels = Task::workflowStatuses();

        $statusCounts = Task::query()
            ->where('assigned_to', $userId)
            ->selectRaw('status_tugas, COUNT(*) as c')
            ->groupBy('status_tugas')
            ->pluck('c', 'status_tugas');

        $totalTasks = (int) $statusCounts->sum();
        $totalDone = (int) ($statusCounts[Task::STATUS_FINISHED] ?? 0);
        $inProgress = $totalTasks - $totalDone;

        $doneToday = (int) Task::query()
            ->where('assigned_to', $userId)
            ->where('status_tugas', Task::STATUS_FINISHED)
            ->whereDate('diperbarui', today())
            ->count();

        $doneThisMonth = (int) Task::query()
            ->where('assigned_to', $userId)
            ->where('status_tugas', Task::STATUS_FINISHED)
            ->whereBetween('diperbarui', [$periodStart, $periodEnd])
            ->count();

        $overdue = (int) Task::query()
            ->where('assigned_to', $userId)
            ->where('status_tugas', '!=', Task::STATUS_FINISHED)
            ->where('diperbarui', '<', now()->subDays(7))
            ->count();

        $recentTasks = Task::query()
            ->where('assigned_to', $userId)
            ->orderByDesc('diperbarui')
            ->take(8)
            ->get(['id', 'judul', 'status_tugas', 'priority', 'due_date', 'diperbarui']);

        $statusBreakdown = collect($statusLabels)
            ->map(fn (string $label) => [
                'label' => $label,
                'count' => (int) ($statusCounts[$label] ?? 0),
            ])
            ->all();

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'jabatan' => $user->jabatan ?: ucfirst((string) $user->role),
                'role' => (string) $user->role,
                'email' => $user->email,
                'initials' => $this->initials($user->name),
                'avatar_hue' => ($user->id * 47) % 360,
            ],
            'kpi' => [
                'done_today' => $doneToday,
                'done_this_month' => $doneThisMonth,
                'in_progress' => $inProgress,
                'overdue' => $overdue,
                'total' => $totalTasks,
            ],
            'status_breakdown' => $statusBreakdown,
            'recent_tasks' => $recentTasks,
            'period_label' => $periodStart->copy()->locale('id')->translatedFormat('F Y'),
            'win_history' => $winHistory,
        ];
    }

    protected function initials(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY);
        if ($parts === false || $parts === []) {
            return '?';
        }

        $slice = array_slice($parts, 0, 2);
        $out = '';
        foreach ($slice as $part) {
            $out .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $out !== '' ? $out : '?';
    }
}
