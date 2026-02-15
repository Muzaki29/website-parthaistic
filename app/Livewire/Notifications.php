<?php

namespace App\Livewire;

use App\Helpers\NotificationHelper;
use Livewire\Component;

class Notifications extends Component
{
    public function render()
    {
        $notifications = auth()->check()
            ? NotificationHelper::getDummyNotifications(auth()->user())
            : [];

        return view('livewire.notifications', [
            'notifications' => $notifications,
        ]);
    }
}
