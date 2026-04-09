<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public function mount()
    {
        if (Auth::check()) {
            redirect()->route('dashboard')->send();
        }
    }

    public function register()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'employee',
            'status_akun' => 'active',
        ]);

        Auth::attempt([
            'email' => strtolower(trim($validated['email'])),
            'password' => $validated['password'],
        ]);
        session()->regenerate();

        session()->flash('success', 'Akun berhasil dibuat. Selamat datang!');

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.register')->layout('layouts.auth');
    }
}

