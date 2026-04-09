<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Employees extends Component
{
    public $editingUser = null;

    public $role = '';

    public $showModal = false;

    // Create User Properties
    public $showCreateModal = false;

    public $newName = '';

    public $newEmail = '';

    public $newPassword = '';

    public $newRole = 'manager';

    public $newJabatan = '';

    public function openCreateModal()
    {
        $this->authorizeAdmin();
        $this->reset(['newName', 'newEmail', 'newPassword', 'newRole', 'newJabatan']);
        $this->newRole = 'manager';
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    public function createUser()
    {
        $this->authorizeAdmin();
        $this->validate([
            'newName' => 'required|string|max:255',
            'newEmail' => 'required|email|unique:users,email',
            'newPassword' => 'required|min:8',
            'newRole' => 'required|in:admin,manager,employee',
            'newJabatan' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $this->newName,
            'email' => $this->newEmail,
            'password' => \Illuminate\Support\Facades\Hash::make($this->newPassword),
            'role' => strtolower($this->newRole),
            'jabatan' => $this->newJabatan,
            'status_akun' => 'active',
        ]);

        session()->flash('success', 'Karyawan berhasil ditambahkan!');
        $this->closeCreateModal();
        $this->dispatch('close-modal');
    }

    public function editRole($userId)
    {
        $this->authorizeAdmin();
        $this->editingUser = User::find($userId);
        $this->role = $this->editingUser->role;
        $this->showModal = true;
    }

    public function updateRole()
    {
        $this->authorizeAdmin();
        $this->validate([
            'role' => 'required|in:admin,manager,employee',
        ]);

        if ($this->editingUser) {
            $this->editingUser->update(['role' => $this->role]);
            session()->flash('success', 'User role updated successfully.');
            $this->closeModal();
        }
    }

    public function deleteUser($userId)
    {
        $this->authorizeAdmin();
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            session()->flash('success', 'User deleted successfully.');
        }
    }

    protected function authorizeAdmin(): void
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk mengelola user.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingUser = null;
    }

    public function render()
    {
        $users = User::all();

        return view('livewire.employees', ['users' => $users])->layout('layouts.dashboard');
    }
}
