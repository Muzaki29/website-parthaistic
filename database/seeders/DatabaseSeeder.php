<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Parthaistic',
            'email' => 'admin@team.parthaistic.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'jabatan' => 'Administrator',
        ]);

        // CEO (Manager)
        User::create([
            'name' => 'Rizky Yudo',
            'email' => 'rizky.yudo@team.parthaistic.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'jabatan' => 'CEO',
        ]);

        // Staff (Video Editor, Creative Writer)
        $staffs = [
            ['name' => 'Staff Editor 1', 'email' => 'editor1@team.parthaistic.com', 'jabatan' => 'Video Editor'],
            ['name' => 'Staff Writer 1', 'email' => 'writer1@team.parthaistic.com', 'jabatan' => 'Creative Writer'],
            ['name' => 'Staff Editor 2', 'email' => 'editor2@team.parthaistic.com', 'jabatan' => 'Video Editor'],
        ];

        foreach ($staffs as $staff) {
            User::create([
                'name' => $staff['name'],
                'email' => $staff['email'],
                'password' => Hash::make('password'),
                'role' => 'employee', // Updated to employee
                'jabatan' => $staff['jabatan'],
            ]);
        }

        // Call TaskSeeder to generate tasks and statistics
        $this->call(TaskSeeder::class);
    }
}
