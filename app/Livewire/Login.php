<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';

    public $password = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();

            // Check if user account is active
            if ($user->status_akun !== 'active') {
                Auth::logout();
                $this->addError('email', 'Akun Anda tidak aktif. Silakan hubungi administrator.');

                return;
            }

            session()->regenerate();

            return redirect()->intended('dashboard');
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.login')->layout('components.layouts.guest');
    }
}
