<?php

namespace App\Providers;

use App\Models\UserNotification;
use App\Models\Task;
use App\Policies\TaskPolicy;
use App\Services\UserNotificationService;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Task::class, TaskPolicy::class);

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
