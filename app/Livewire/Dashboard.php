<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\TrelloService;
use App\Services\UserNotificationService;
use Livewire\Component;

class Dashboard extends Component
{
    /** @var list<int|string> */
    public array $selectedActivityLogIds = [];

    public function syncData(TrelloService $trelloService)
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk sinkronisasi data.');
        }

        $result = $trelloService->syncData();

        if ($result['status']) {
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', 'Gagal sinkronisasi: '.$result['message']);
        }
    }

    /**
     * @return list<int>
     */
    protected function visibleRecentActivityLogIds(\Illuminate\Contracts\Auth\Authenticatable $user): array
    {
        return ActivityLog::query()
            ->when($user->role !== 'admin', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(12)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function toggleSelectAllActivity(): void
    {
        $user = auth()->user();
        $visible = $this->visibleRecentActivityLogIds($user);
        if ($visible === []) {
            $this->selectedActivityLogIds = [];

            return;
        }

        $selected = array_map('intval', $this->selectedActivityLogIds);
        $allVisibleSelected = count(array_diff($visible, $selected)) === 0;

        if ($allVisibleSelected) {
            $this->selectedActivityLogIds = array_values(array_diff($selected, $visible));
        } else {
            $this->selectedActivityLogIds = array_values(array_unique(array_merge($selected, $visible)));
        }
    }

    public function deleteSelectedActivityLogs(): void
    {
        $user = auth()->user();
        $visible = $this->visibleRecentActivityLogIds($user);
        $ids = array_values(array_intersect(
            array_map('intval', $this->selectedActivityLogIds),
            $visible
        ));

        if ($ids === []) {
            session()->flash('error', 'Pilih minimal satu entri log untuk dihapus.');

            return;
        }

        $query = ActivityLog::query()->whereIn('id', $ids);
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $deleted = $query->delete();
        $this->selectedActivityLogIds = [];

        session()->flash('success', $deleted === 1
            ? '1 entri log aktivitas telah dihapus.'
            : "{$deleted} entri log aktivitas telah dihapus.");
    }

    public function render()
    {
        $user = auth()->user();
        app(UserNotificationService::class)->syncDerivedTaskNotifications($user);

        $unreadNotifications = UserNotification::query()
            ->where('user_id', $user->id)
            ->visible()
            ->unread()
            ->count();

        $leadsNeedingFollowUp = 0;
        if ($user->role === 'admin') {
            $leadsNeedingFollowUp = Lead::query()
                ->whereIn('status', [Lead::STATUS_NEW, Lead::STATUS_CONTACTED, Lead::STATUS_QUALIFIED])
                ->count();
        }

        $recentActivity = ActivityLog::query()
            ->with('user:id,name,role')
            ->when($user->role !== 'admin', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(12)
            ->get();

        // 1. Stats Cards
        $totalTasks = Task::count();
        $totalDone = Task::where('status_tugas', Task::STATUS_FINISHED)->count();
        $percentageDone = $totalTasks > 0 ? round(($totalDone / $totalTasks) * 100, 1) : 0;

        // Simple heuristic for Overdue: Not done and not updated in 7 days
        $overdueTasks = Task::where('status_tugas', '!=', Task::STATUS_FINISHED)
            ->where('diperbarui', '<', now()->subDays(7))
            ->count();

        // Productivity Trend (Tasks done this week vs last week)
        $doneThisWeek = Task::where('status_tugas', Task::STATUS_FINISHED)->whereBetween('diperbarui', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $doneLastWeek = Task::where('status_tugas', Task::STATUS_FINISHED)->whereBetween('diperbarui', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
        $trend = $doneThisWeek - $doneLastWeek;
        $trendText = $trend >= 0 ? "+{$trend} from last week" : "{$trend} from last week";

        // 2. Charts Data
        // Status Distribution
        $statusCounts = collect(Task::workflowStatuses())
            ->mapWithKeys(fn ($status) => [$status => Task::where('status_tugas', $status)->count()])
            ->all();

        // Top Performers
        $topPerformers = User::withSum('statistics', 'total_done')
            ->get()
            ->sortByDesc('statistics_sum_total_done')
            ->take(5);

        // Heatmap Data (Activity by Date for last 30 days)
        // Group by date of 'diperbarui'
        $activityData = Task::selectRaw('DATE(diperbarui) as date, COUNT(*) as count')
            ->where('diperbarui', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'x' => $item->date,
                    'y' => $item->count,
                ];
            })->toArray();

        return view('livewire.dashboard', [
            'totalTasks' => $totalTasks,
            'percentageDone' => $percentageDone,
            'overdueTasks' => $overdueTasks,
            'trendText' => $trendText,
            'statusCounts' => $statusCounts,
            'statusLabels' => array_keys($statusCounts),
            'statusValues' => array_values($statusCounts),
            'topPerformers' => $topPerformers,
            'activityData' => $activityData,
            'unreadNotifications' => $unreadNotifications,
            'leadsNeedingFollowUp' => $leadsNeedingFollowUp,
            'recentActivity' => $recentActivity,
        ])->layout('layouts.dashboard');
    }
}
