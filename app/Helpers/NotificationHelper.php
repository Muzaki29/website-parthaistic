<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\UserNotification;
use App\Services\UserNotificationService;

class NotificationHelper
{
    /**
     * Bell dropdown + full page: DB-backed notifications with derived task summaries.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getNotificationsForUser(User $user): array
    {
        app(UserNotificationService::class)->syncDerivedTaskNotifications($user);

        return UserNotification::query()
            ->where('user_id', $user->id)
            ->visible()
            ->latest()
            ->get()
            ->map(fn (UserNotification $n) => UserNotificationService::toViewArray($n))
            ->values()
            ->all();
    }

    public static function getUnreadCount(User $user): int
    {
        return UserNotification::query()
            ->where('user_id', $user->id)
            ->visible()
            ->unread()
            ->count();
    }
}
