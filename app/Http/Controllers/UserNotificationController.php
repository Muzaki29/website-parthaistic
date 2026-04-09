<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function open(Request $request, UserNotification $userNotification): RedirectResponse
    {
        abort_unless($request->user() && (int) $userNotification->user_id === (int) $request->user()->id, 403);

        if ($userNotification->dismissed_at === null && $userNotification->read_at === null) {
            $userNotification->update(['read_at' => now()]);
            ActivityLogger::log(
                'notification_read',
                $request->user(),
                UserNotification::class,
                $userNotification->id
            );
        }

        $target = $userNotification->action_url;
        if (is_string($target) && str_starts_with($target, '/') && ! str_starts_with($target, '//')) {
            return redirect()->to($target);
        }

        return redirect()->route('dashboard');
    }
}
