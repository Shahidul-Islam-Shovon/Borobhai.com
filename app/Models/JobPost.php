<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JobPost extends Model
{
    protected $table = 'job_posts';

    protected $fillable = [
        'user_id', 'title', 'company', 'location', 'job_type',
        'experience', 'salary', 'description', 'requirements', 'skills',
        'apply_type', 'apply_value', 'deadline', 'category', 'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // যে alumni পোস্ট করেছে
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ===== Helper / Accessor =====

    // ডেডলাইন কি শেষ?
    public function getIsExpiredAttribute()
    {
        if (!$this->deadline) return false;
        return $this->deadline->endOfDay()->isPast();
    }

    // ডেডলাইন কি ঘনিয়ে আসছে? (৩ দিনের মধ্যে)
    public function getIsExpiringSoonAttribute()
    {
        if (!$this->deadline || $this->is_expired) return false;
        return $this->deadline->endOfDay()->diffInDays(now()) <= 3;
    }

    // ডেডলাইন শেষ হওয়ার কত দিন পর অটো-ডিলিট হবে (৫ দিন গ্রেস)
    public function getShouldAutoDeleteAttribute()
    {
        if (!$this->deadline) return false;
        return $this->deadline->endOfDay()->addDays(5)->isPast();
    }

    // skills কে array তে (tag দেখানোর জন্য)
    public function getSkillsArrayAttribute()
    {
        if (!$this->skills) return [];
        return array_filter(array_map('trim', explode(',', $this->skills)));
    }

    // ক্যাটাগরি priority (Popular/Internship/Part-time আগে দেখাতে)
    public function getSortPriorityAttribute()
    {
        $type = strtolower($this->job_type ?? '');
        if (str_contains($type, 'intern')) return 1;
        if (str_contains($type, 'part'))   return 2;
        return 3;
    }

    // ===== Scopes =====

    // শুধু সক্রিয় (auto-delete হওয়ার যোগ্য নয় এমন) job
    public function scopeVisible($query)
    {
        // deadline null অথবা deadline+5day এখনো future
        return $query->where(function ($q) {
            $q->whereNull('deadline')
              ->orWhereRaw('DATE_ADD(deadline, INTERVAL 5 DAY) >= ?', [now()->toDateString()]);
        });
    }
}