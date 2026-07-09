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
        // 👑 1. Create Chief Super Admin Account (আপনার রিয়েল প্রোফাইল)
        User::updateOrCreate(
            ['email' => env('SUPER_ADMIN_EMAIL')],
            [
                'name' => env('SUPER_ADMIN_NAME'),
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD')),
                'role' => 'admin',
                'is_super_admin' => true,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
                
        // only super admin id is required for the system to work, so we can stop here.
    }
}