<?php

namespace App\Livewire;

use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public $email = '';

    public $password = '';

    public function mount()
    {
        if (Auth::check()) {
            redirect()->route('dashboard')->send();
        }
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = $this->throttleKey();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.");

            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();

            // Check if user account is active
            if ($user->status_akun !== 'active') {
                Auth::logout();
                RateLimiter::hit($throttleKey, 60);
                $this->addError('email', 'Akun Anda tidak aktif. Silakan hubungi administrator.');

                return;
            }

            session()->regenerate();
            RateLimiter::clear($throttleKey);

            ActivityLogger::log('user_logged_in', $user, null, null, ['email' => $user->email]);

            return redirect()->intended('dashboard');
        }

        RateLimiter::hit($throttleKey, 60);
        $this->addError('email', 'The provided credentials do not match our records.');
    }

    protected function throttleKey(): string
    {
        return Str::lower($this->email).'|'.request()->ip();
    }

    public function render()
    {
        return view('livewire.login')->layout('layouts.auth');
    }
}
