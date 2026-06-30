<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Traits\Hashidable;             // ⬅️ ধাপ ১ এর trait — namespace তোমার আসলটার সাথে মিলিয়ে নাও
use Vinkla\Hashids\Facades\Hashids; 
use App\Models\Concerns\HasHashid;  // ⬅️ decode করার জন্য

class JobPost extends Model
{
    use SoftDeletes;
    use HasHashid;   // ⬅️ এক লাইনেই /jobs/{id} এখন hashid

    protected $table = 'job_posts';

    protected $fillable = [
        'user_id', 'title', 'company', 'location', 'job_type',
        'experience', 'salary', 'description', 'requirements', 'skills',
        'apply_type', 'apply_value', 'deadline', 'category', 'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // ==========================================
    // Hashid decode helper — controller এ ব্যবহার হবে
    // URL এর hashid → আসল int id; ভুল hashid হলে 404
    // ==========================================
    public static function decodeHashid($hashid): int
    {
        $decoded = Hashids::decode((string) $hashid);
        abort_if(empty($decoded), 404);
        return (int) $decoded[0];
    }

    // যে alumni পোস্ট করেছে
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // যারা এই job সেভ করেছে
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_jobs', 'job_post_id', 'user_id')->withTimestamps();
    }

    // এই job এ যত আবেদন এসেছে
    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_post_id')->latest('applied_at');
    }

    // ===== Helper / Accessor =====

    public function getIsExpiredAttribute()
    {
        if (!$this->deadline) return false;
        return $this->deadline->endOfDay()->isPast();
    }

    public function getIsExpiringSoonAttribute()
    {
        if (!$this->deadline || $this->is_expired) return false;
        $daysLeft = now()->startOfDay()->diffInDays($this->deadline->startOfDay(), false);
        return $daysLeft >= 0 && $daysLeft <= 3;
    }

    public function getShouldAutoDeleteAttribute()
    {
        if (!$this->deadline) return false;
        return $this->deadline->endOfDay()->addDays(5)->isPast();
    }

    public function getSkillsArrayAttribute()
    {
        if (!$this->skills) return [];
        return array_filter(array_map('trim', explode(',', $this->skills)));
    }

    public function getSortPriorityAttribute()
    {
        $type = strtolower($this->job_type ?? '');
        if (str_contains($type, 'intern')) return 1;
        if (str_contains($type, 'part'))   return 2;
        return 3;
    }

    // ===== Scopes =====

    public function scopeVisible($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('deadline')
              ->orWhereRaw('DATE_ADD(deadline, INTERVAL 5 DAY) >= ?', [now()->toDateString()]);
        });
    }
}