<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Middleware\EnsureUserRole;
use App\Livewire\Dashboard;
use App\Livewire\Employees;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\Profile;
use App\Livewire\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome_v2');
})->name('landing');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store')->middleware('throttle:8,1');

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::middleware([EnsureUserRole::class.':admin,manager,employee'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/reports', Reports::class)->name('reports');
    Route::get('/tasks/{id}', \App\Livewire\TaskDetail::class)->name('tasks.show');
    Route::get('/profile', Profile::class)->name('profile.edit');
    Route::get('/notifications', \App\Livewire\Notifications::class)->name('notifications');
    Route::get('/notifications/{userNotification}/open', [UserNotificationController::class, 'open'])->name('notifications.open');
});

Route::middleware([EnsureUserRole::class.':admin'])->group(function () {
    Route::get('/employees', Employees::class)->name('employees');
    Route::get('/admin/leads', [LeadController::class, 'index'])->name('admin.leads.index');
    Route::patch('/admin/leads/{lead}', [LeadController::class, 'update'])->name('admin.leads.update');
    Route::get('/test-trello-sync', function (App\Services\TrelloService $trelloService) {
        abort_unless(app()->isLocal(), 404);

        return $trelloService->syncData();
    })->name('trello.sync.test');
});
