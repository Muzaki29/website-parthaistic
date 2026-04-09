<?php

namespace App\Livewire;

use App\Models\UserNotification;
use App\Services\UserNotificationService;
use Livewire\Component;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public function markAllAsRead(): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }

        app(UserNotificationService::class)->markAllAsRead($user);
        session()->flash('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function clearAll(): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }

        app(UserNotificationService::class)->dismissAllVisible($user);
        session()->flash('success', 'Semua notifikasi telah dibersihkan.');
    }

    public function dismissNotification(int $notificationId): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }

        app(UserNotificationService::class)->dismiss($user, $notificationId);
    }

    public function render()
    {
        $user = auth()->user();
        if (! $user) {
            return view('livewire.notifications', [
                'notificationRows' => collect(),
            ])->layout('layouts.dashboard');
        }

        app(UserNotificationService::class)->syncDerivedTaskNotifications($user);

        $paginator = UserNotification::query()
            ->where('user_id', $user->id)
            ->visible()
            ->latest()
            ->paginate(15);

        $paginator->getCollection()->transform(
            fn (UserNotification $n) => UserNotificationService::toViewArray($n)
        );

        return view('livewire.notifications', [
            'notificationRows' => $paginator,
        ])->layout('layouts.dashboard');
    }
}
