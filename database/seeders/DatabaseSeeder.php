<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin Account
        User::updateOrCreate(
            ['email' => 'admin@borobhai.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@1234'), // Secure default password
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // 2. Create Sample Alumni Account for testing
        User::updateOrCreate(
            ['email' => 'alumni@borobhai.com'],
            [
                'name' => 'John Doe (Alumni)',
                'password' => Hash::make('Alumni@1234'),
                'role' => 'alumni',
                'status' => 'active',
            ]
        );

        // 3. Create Sample Student Account for testing
        User::updateOrCreate(
            ['email' => 'student@borobhai.com'],
            [
                'name' => 'Alex Smith (Student)',
                'password' => Hash::make('Student@1234'),
                'role' => 'student',
                'status' => 'active',
            ]
        );
    }
}