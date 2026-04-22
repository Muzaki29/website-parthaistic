<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;

class UserNotificationService
{
    public function createForUser(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $actionLabel = null,
        ?array $data = null,
        ?string $sourceKey = null,
    ): UserNotification {
        $attrs = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'action_label' => $actionLabel,
            'data' => $data,
        ];

        if ($sourceKey !== null) {
            $notification = UserNotification::updateOrCreate(
                ['user_id' => $user->id, 'source_key' => $sourceKey],
                $attrs
            );
        } else {
            $notification = UserNotification::create(array_merge($attrs, [
                'user_id' => $user->id,
            ]));
        }

        if ($notification->wasRecentlyCreated) {
            ActivityLogger::log(
                'notification_created',
                $user,
                UserNotification::class,
                $notification->id,
                ['type' => $type, 'source_key' => $sourceKey]
            );
        }

        return $notification;
    }

    public function notifyAdminsOfNewLead(Lead $lead): void
    {
        $admins = User::query()
            ->where('role', 'admin')
            ->where('status_akun', 'active')
            ->get();

        foreach ($admins as $admin) {
            $this->createForUser(
                $admin,
                'lead',
                'Lead baru masuk',
                "{$lead->name} — ".($lead->company ?: $lead->email),
                route('admin.leads.index', ['q' => $lead->email]),
                'Buka inbox',
                ['lead_id' => $lead->id],
                'lead:'.$lead->id.':user:'.$admin->id
            );
        }
    }

    /**
     * Lightweight task summary rows kept in sync for the bell UI (idempotent via source_key).
     */
    public function syncDerivedTaskNotifications(User $user): void
    {
        $openCount = Task::where('assigned_to', $user->id)
            ->where('status_tugas', '!=', Task::STATUS_FINISHED)
            ->count();

        if ($openCount > 0) {
            UserNotification::updateOrCreate(
                ['user_id' => $user->id, 'source_key' => 'derived:open_tasks'],
                [
                    'type' => 'task',
                    'title' => 'Tugas aktif',
                    'message' => "Anda memiliki {$openCount} tugas yang belum selesai.",
                    'action_url' => '/reports',
                    'action_label' => 'Lihat laporan',
                    'data' => ['open_count' => $openCount],
                ]
            );
        } else {
            UserNotification::where('user_id', $user->id)
                ->where('source_key', 'derived:open_tasks')
                ->delete();
        }

        $overdueCount = Task::where('assigned_to', $user->id)
            ->where('status_tugas', '!=', Task::STATUS_FINISHED)
            ->where('diperbarui', '<', now()->subDays(7))
            ->count();

        if ($overdueCount > 0) {
            UserNotification::updateOrCreate(
                ['user_id' => $user->id, 'source_key' => 'derived:overdue_tasks'],
                [
                    'type' => 'warning',
                    'title' => 'Tugas overdue',
                    'message' => "{$overdueCount} tugas belum diperbarui lebih dari 7 hari.",
                    'action_url' => '/reports?overdue=1',
                    'action_label' => 'Tinjau tugas',
                    'data' => ['overdue_count' => $overdueCount],
                ]
            );
        } else {
            UserNotification::where('user_id', $user->id)
                ->where('source_key', 'derived:overdue_tasks')
                ->delete();
        }
    }

    public function markAllAsRead(User $user): int
    {
        $count = UserNotification::query()
            ->where('user_id', $user->id)
            ->visible()
            ->unread()
            ->update(['read_at' => now()]);

        if ($count > 0) {
            ActivityLogger::log('notification_read', $user, null, null, ['batch' => true, 'count' => $count]);
        }

        return $count;
    }

    public function dismiss(User $user, int $notificationId): bool
    {
        $n = UserNotification::where('user_id', $user->id)->whereKey($notificationId)->first();
        if (! $n) {
            return false;
        }
        $n->update([
            'dismissed_at' => now(),
            'read_at' => $n->read_at ?? now(),
        ]);

        return true;
    }

    public function dismissAllVisible(User $user): int
    {
        $now = now();

        $a = UserNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('dismissed_at')
            ->whereNull('read_at')
            ->update([
                'read_at' => $now,
                'dismissed_at' => $now,
            ]);

        $b = UserNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('dismissed_at')
            ->whereNotNull('read_at')
            ->update(['dismissed_at' => $now]);

        return $a + $b;
    }

    /**
     * Map DB row to the existing bell / full-page view array shape.
     */
    public static function toViewArray(UserNotification $n): array
    {
        [$icon, $color] = self::iconAndColor($n->type);

        return [
            'id' => (int) $n->id,
            'type' => $n->type,
            'icon' => $icon,
            'title' => $n->title,
            'message' => $n->message,
            'time' => $n->created_at ? Carbon::parse($n->created_at)->diffForHumans() : '',
            'read' => $n->read_at !== null,
            'url' => route('notifications.open', $n),
            'action_url' => $n->action_url,
            'action_label' => $n->action_label,
            'color' => $color,
        ];
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected static function iconAndColor(string $type): array
    {
        return match ($type) {
            'lead' => ['mail', 'blue'],
            'task' => ['task', 'blue'],
            'warning' => ['alert', 'red'],
            'success' => ['check', 'green'],
            'meeting' => ['calendar', 'purple'],
            default => ['system', 'gray'],
        };
    }
}
