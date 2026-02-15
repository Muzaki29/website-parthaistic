<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@parthaistic.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Manager
        User::updateOrCreate(
            ['email' => 'manager@parthaistic.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ]
        );
    }
}
