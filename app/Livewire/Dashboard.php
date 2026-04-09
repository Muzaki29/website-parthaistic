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
            ->when($user->role !== 'admin', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(12)
            ->get();

        // 1. Stats Cards
        $totalTasks = Task::count();
        $totalDone = Task::where('status_tugas', 'Done')->count();
        $percentageDone = $totalTasks > 0 ? round(($totalDone / $totalTasks) * 100, 1) : 0;

        // Simple heuristic for Overdue: Not done and not updated in 7 days
        $overdueTasks = Task::where('status_tugas', '!=', 'Done')
            ->where('diperbarui', '<', now()->subDays(7))
            ->count();

        // Productivity Trend (Tasks done this week vs last week)
        $doneThisWeek = Task::where('status_tugas', 'Done')->whereBetween('diperbarui', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $doneLastWeek = Task::where('status_tugas', 'Done')->whereBetween('diperbarui', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
        $trend = $doneThisWeek - $doneLastWeek;
        $trendText = $trend >= 0 ? "+{$trend} from last week" : "{$trend} from last week";

        // 2. Charts Data
        // Status Distribution
        $statusCounts = [
            'To Do' => Task::where('status_tugas', 'To Do')->count(),
            'Doing' => Task::where('status_tugas', 'Doing')->count(),
            'Done' => Task::where('status_tugas', 'Done')->count(),
        ];

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
            'topPerformers' => $topPerformers,
            'activityData' => $activityData,
            'unreadNotifications' => $unreadNotifications,
            'leadsNeedingFollowUp' => $leadsNeedingFollowUp,
            'recentActivity' => $recentActivity,
        ])->layout('layouts.dashboard');
    }
}
