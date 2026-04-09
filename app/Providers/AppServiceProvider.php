<?php

namespace App\Providers;

use App\Models\UserNotification;
use App\Services\UserNotificationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view) {
            if (! auth()->check()) {
                $view->with([
                    'navNotifications' => [],
                    'navUnreadCount' => 0,
                ]);

                return;
            }

            $user = auth()->user();
            app(UserNotificationService::class)->syncDerivedTaskNotifications($user);

            $navUnreadCount = UserNotification::query()
                ->where('user_id', $user->id)
                ->visible()
                ->unread()
                ->count();

            $navNotifications = UserNotification::query()
                ->where('user_id', $user->id)
                ->visible()
                ->latest()
                ->take(8)
                ->get()
                ->map(fn ($n) => UserNotificationService::toViewArray($n))
                ->all();

            $view->with(compact('navNotifications', 'navUnreadCount'));
        });
    }
}
