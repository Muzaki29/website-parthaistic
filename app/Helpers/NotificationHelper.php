<?php

namespace App\Helpers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class NotificationHelper
{
    /**
     * Get dummy notifications for a user
     */
    public static function getDummyNotifications(User $user): array
    {
        $notifications = [];

        // Get user's recent tasks
        $recentTasks = Task::where('assigned_to', $user->id)
            ->where('status_tugas', '!=', 'Done')
            ->orderBy('diperbarui', 'desc')
            ->take(3)
            ->get();

        // Notification 1: New Task Assigned (if user has tasks)
        if ($recentTasks->count() > 0) {
            $task = $recentTasks->first();
            $notifications[] = [
                'id' => 1,
                'type' => 'task',
                'icon' => 'task',
                'title' => 'New Task Assigned',
                'message' => "You have a new task: {$task->judul}",
                'time' => Carbon::parse($task->diperbarui)->diffForHumans(),
                'read' => false,
                'url' => '/reports?task='.$task->id,
                'color' => 'blue',
            ];
        } else {
            $notifications[] = [
                'id' => 1,
                'type' => 'task',
                'icon' => 'task',
                'title' => 'New Task Assigned',
                'message' => 'You have a new video editing task.',
                'time' => '2 hours ago',
                'read' => false,
                'url' => '/reports',
                'color' => 'blue',
            ];
        }

        // Notification 2: Meeting Reminder
        $notifications[] = [
            'id' => 2,
            'type' => 'meeting',
            'icon' => 'calendar',
            'title' => 'Meeting Reminder',
            'message' => 'Weekly sync meeting at 10:00 AM tomorrow.',
            'time' => '5 hours ago',
            'read' => false,
            'url' => '#',
            'color' => 'purple',
        ];

        // Notification 3: System Update
        $notifications[] = [
            'id' => 3,
            'type' => 'system',
            'icon' => 'system',
            'title' => 'System Update',
            'message' => 'System maintenance scheduled tonight at 11:00 PM.',
            'time' => '1 day ago',
            'read' => true,
            'url' => '#',
            'color' => 'gray',
        ];

        // Notification 4: Task Completed (if user has completed tasks)
        $completedTasks = Task::where('assigned_to', $user->id)
            ->where('status_tugas', 'Done')
            ->where('diperbarui', '>=', now()->subDays(1))
            ->count();

        if ($completedTasks > 0) {
            $notifications[] = [
                'id' => 4,
                'type' => 'success',
                'icon' => 'check',
                'title' => 'Task Completed',
                'message' => "Great! You've completed {$completedTasks} task(s) today.",
                'time' => '3 hours ago',
                'read' => false,
                'url' => '/reports?status=Done',
                'color' => 'green',
            ];
        }

        // Notification 5: Overdue Task Warning
        $overdueTasks = Task::where('assigned_to', $user->id)
            ->where('status_tugas', '!=', 'Done')
            ->where('diperbarui', '<', now()->subDays(7))
            ->count();

        if ($overdueTasks > 0) {
            $notifications[] = [
                'id' => 5,
                'type' => 'warning',
                'icon' => 'alert',
                'title' => 'Overdue Task Alert',
                'message' => "You have {$overdueTasks} overdue task(s) that need attention.",
                'time' => '1 hour ago',
                'read' => false,
                'url' => '/reports?overdue=1',
                'color' => 'red',
            ];
        }

        // Sort by unread first, then by time
        usort($notifications, function ($a, $b) {
            if ($a['read'] === $b['read']) {
                return 0;
            }

            return $a['read'] ? 1 : -1;
        });

        return $notifications;
    }

    /**
     * Get unread notification count
     */
    public static function getUnreadCount(User $user): int
    {
        $notifications = self::getDummyNotifications($user);

        return collect($notifications)->where('read', false)->count();
    }
}
