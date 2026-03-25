<?php

use App\Http\Middleware\EnsureUserRole;
use App\Livewire\Dashboard;
use App\Livewire\Employees;
use App\Livewire\Login;
use App\Livewire\Profile;
use App\Livewire\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome_v2');
})->name('landing');

Route::get('/login', Login::class)->name('login');

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
});

Route::middleware([EnsureUserRole::class.':admin'])->group(function () {
    Route::get('/employees', Employees::class)->name('employees');
    Route::get('/test-trello-sync', function (App\Services\TrelloService $trelloService) {
        return $trelloService->syncData();
    });
});
