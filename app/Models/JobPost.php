<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Traits\Hashidable;             // ⬅️ ধাপ ১ এর trait — namespace তোমার আসলটার সাথে মিলিয়ে নাও
use Vinkla\Hashids\Facades\Hashids;
use App\Models\Concerns\HasHashid;     // ⬅️ decode করার জন্য

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

    // ==========================================
    // Auto-delete শুধু তখনই — deadline অনেক পুরোনো (৩০ দিন)
    // আর কেউ apply করেনি। applicant থাকলে কখনো auto-delete নয়
    // (job history রক্ষা করতে)।
    // ==========================================
    public function getShouldAutoDeleteAttribute()
    {
        if (!$this->deadline) return false;

        // applicant থাকলে কখনোই auto-delete নয় — history রক্ষা
        if ($this->applications()->exists()) return false;

        // শুধু applicant-শূন্য job, deadline এর ৩০ দিন পর
        return $this->deadline->endOfDay()->addDays(30)->isPast();
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

    // Feed এ দেখাবে — deadline এর ২ দিন পর পর্যন্ত, তারপর feed থেকে বাদ
    // (DB তে থাকবে, শুধু feed এ দেখাবে না)
    public function scopeVisible($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('deadline')
              ->orWhereRaw('DATE_ADD(deadline, INTERVAL 2 DAY) >= ?', [now()->toDateString()]);
        });
    }

    // ✅ Hashid — id লুকিয়ে রাখার জন্য, সিগনেচার-ভেরিফাইড (tamper-proof)
    public function getHashidAttribute()
    {
        return static::encodeId($this->id);
    }

    public static function encodeId($id)
    {
        $sig = substr(hash_hmac('sha256', $id, config('app.key')), 0, 10);
        $raw = $id . '.' . $sig;
        return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
    }

    public static function decodeId($hash)
    {
        try {
            $raw = base64_decode(strtr($hash, '-_', '+/'));
            [$id, $sig] = explode('.', $raw, 2);
            if (!ctype_digit($id)) return null;
            $expected = substr(hash_hmac('sha256', $id, config('app.key')), 0, 10);
            return hash_equals($expected, $sig) ? (int) $id : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}