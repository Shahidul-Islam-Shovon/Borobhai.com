<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        /**
     * দ্য অ্যাট্রিবিউটস দ্যাট শুড বি কাস্টেড।
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // 👑 বাগ ফিক্স: এই লাইনটি অবশ্যই যোগ করবেন
        'suspended_until' => 'datetime', 
        'is_super_admin' => 'boolean', // ডাটাবেজের ০ বা ১ কে লারাভেল true/false বানিয়ে দেবে
        
    ];

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'status',
    'is_super_admin',
    ];



public function isSuperAdmin()
{
    // .env ফাইল থেকে ইমেইল নিয়ে ডাইনামিক চেক
    if ($this->email === env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com')) {
        return true;
    }

    return (bool) $this->is_super_admin;
}




}
