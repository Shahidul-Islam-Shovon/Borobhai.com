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
        'suspended_until' => 'datetime',
        'is_super_admin' => 'boolean',
        'skills' => 'array',   // 🆕 JSON ↔ PHP array অটো কনভার্ট
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'is_super_admin',
        // Profile fields
        'profile_picture',
        'cover_photo',
        'bio',
        'phone',
        'location',
        'department',
        'session',
        'section',
        'semester',
        'skills',
        'interests',
        'linkedin_url',
        'github_url',
        'facebook_url',
    ];



public function isSuperAdmin()
{
    // .env ফাইল থেকে ইমেইল নিয়ে ডাইনামিক চেক
    if ($this->email === env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com')) {
        return true;
    }

    return (bool) $this->is_super_admin;
}


    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saved_posts')
         ->withTimestamps()
        ->orderByPivot('created_at', 'desc'); // সর্বশেষ সেভ আগে
    }

    // শিক্ষা ইতিহাস (latest আগে)
    public function educations()
    {
        return $this->hasMany(Education::class)->orderByRaw('COALESCE(end_date, start_date) DESC');
    }

    // চাকরির অভিজ্ঞতা (running আগে, তারপর latest)
    public function experiences()
    {
        return $this->hasMany(Experience::class)
                    ->orderBy('is_current', 'desc')
                    ->orderByRaw('COALESCE(end_date, start_date) DESC');
    }

    // সার্টিফিকেশন (latest আগে)
    public function certifications()
    {
        return $this->hasMany(Certification::class)->orderBy('issue_date', 'desc');
    }

    // Thesis / Project / Research documents (latest আগে)
    public function documents()
    {
        return $this->hasMany(Document::class)->latest();
    }

    // সর্বশেষ শিক্ষা (নামের নিচে দেখানোর জন্য)
    public function latestEducation()
    {
        return $this->hasOne(Education::class)->latestOfMany();
    }

    
    // is_current=true গুলোর মধ্যে latest (start_date বা id দিয়ে)
    public function currentJob()
    {
        return $this->hasOne(Experience::class)
                    ->where('is_current', true)
                    ->orderByRaw('COALESCE(start_date, created_at) DESC')
                    ->orderBy('id', 'desc');
    }

}
