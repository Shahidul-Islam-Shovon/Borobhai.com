<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    // টার্মিনালে যে কমান্ড লিখে রান করবেন
    protected $signature = 'make:super-admin';
    protected $description = 'Create the initial Super Admin after truncating the users table';

    public function handle()
    {
        $this->info('======================================');
        $this->info('   Creating Initial Super Admin...   ');
        $this->info('======================================');

        // ১. নাম ইনপুট নেওয়া
        $name = $this->ask('Enter Super Admin Name', 'Admin Chief');
        
        // ২. ইমেইল ইনপুট নেওয়া
        $email = $this->ask('Enter Super Admin Email');

        // ইমেইল অলরেডি আছে কিনা চেক (যদিও ট্রাঙ্কেট করলে থাকবে না)
        if (User::where('email', $email)->exists()) {
            $this->error('Error: This email is already registered!');
            return;
        }

        // ৩. পাসওয়ার্ড ইনপুট নেওয়া (পাসওয়ার্ড টাইপ করার সময় স্ক্রিনে হাইড থাকবে)
        $password = $this->secret('Enter Super Admin Password');
        
        if (strlen($password) < 6) {
            $this->error('Error: Password must be at least 6 characters long!');
            return;
        }

        // 🚀 ৪. ডেটাবেজে প্রথম ইউজার তৈরি করা
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin', // আপনার সিস্টেমে রোল হবে 'admin'
            'status' => 'active',
        ]);

        $this->info('======================================');
        $this->info("Success: Super Admin [{$user->name}] created successfully!");
        $this->info("Now add this email [{$user->email}] to your .env file as SUPER_ADMIN_EMAIL.");
        $this->info('======================================');
    }
}