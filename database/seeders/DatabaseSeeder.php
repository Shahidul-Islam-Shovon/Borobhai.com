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
            ['email' => 'shahidul.webdev@gmail.com'],
            [
                'name' => 'MD shahidul Islam Shovon',
                'password' => Hash::make('Admin@1234'), // আপনার সিকিউর পাসওয়ার্ড দিতে পারেন
                'role' => 'admin',
                'is_super_admin' => 1, // 🔥 এই লাইনটি মাস্ট! লজিক ঠিক রাখার জন্য
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        
        // only super admin id is required for the system to work, so we can stop here.
    }
}