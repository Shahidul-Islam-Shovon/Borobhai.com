<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Hashidable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{

    use Hashidable;
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
        'provider',
        'provider_id',
        'last_seen',
    ];



public function isSuperAdmin()
{
    // .env ফাইল থেকে ইমেইল নিয়ে ডাইনামিক চেক
    if ($this->email === env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com')) {
        return true;
    }

    return (bool) $this->is_super_admin;
}

    // ==========================================
    // ROLE HELPERS (Teacher সহ)
    // ==========================================

    /**
     * নামের নিচে দেখানোর label।
     * student → Student, teacher → Teacher, alumni → Alumni, admin → Admin
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'student' => 'Student',
            'teacher' => 'Teacher',
            'alumni'  => 'Alumni',
            'admin'   => 'Admin',
            default   => ucfirst((string) $this->role),
        };
    }

    /** Teacher রোল কিনা */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /** Alumni রোল কিনা */
    public function isAlumni(): bool
    {
        return $this->role === 'alumni';
    }

    /** Student রোল কিনা */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Job তৈরি (post) করার অনুমতি আছে কিনা।
     * শুধু alumni পারবে। Teacher, Student, Admin পারবে না।
     */
    public function canPostJobs(): bool
    {
        return $this->role === 'alumni';
    }

    /**
     * Feed এ post/share করার অনুমতি — teacher সহ সবাই পারবে (admin বাদে)।
     * (Teacher শুধু job তৈরি করতে পারে না, কিন্তু সাধারণ post করতে পারে)
     */
    public function canPost(): bool
    {
        return in_array($this->role, ['student', 'alumni', 'teacher']);
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

    // এই user যেসব job সেভ করেছে
    // এই user যেসব job সেভ করেছে
    public function savedJobs()
    {
        return $this->belongsToMany(\App\Models\JobPost::class, 'saved_jobs', 'user_id', 'job_post_id')
                    ->withPivot('created_at')
                    ->withTimestamps();
    }

    // এই user (student) যেসব job এ আবেদন করেছে
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class)->latest('applied_at');
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

    // এই user যেসব job পোস্ট করেছে
    public function jobPosts()
    {
        return $this->hasMany(JobPost::class)->latest();
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

    public function friendships()
    {
        return $this->hasMany(\App\Models\Friendship::class, 'sender_id')
            ->orWhere('receiver_id', $this->id);
    }

}