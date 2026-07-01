<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasHashid;
use App\Models\JobApplicationStatusLog;


class JobApplication extends Model
{

    use HasHashid;
    
    protected $fillable = [
        'user_id', 'job_post_id', 'applicant_name', 'applicant_email',
        'phone', 'cover_note', 'resume_path', 'apply_method', 'status', 'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    // ===== Relations =====

    // যে student আবেদন করেছে
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // কোন job এ আবেদন
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    // ===== Accessors / Helpers =====

    // status এর রঙ ও লেবেল (UI badge এর জন্য)
    public function getStatusMetaAttribute()
    {
        return match ($this->status) {
            'pending'     => ['label' => 'Pending',     'color' => '#6b7280', 'bg' => '#f3f4f6', 'icon' => 'bi-hourglass-split'],
            'reviewed'    => ['label' => 'Under Review', 'color' => '#2563eb', 'bg' => '#eff6ff', 'icon' => 'bi-eye'],
            'shortlisted' => ['label' => 'Shortlisted',  'color' => '#16a34a', 'bg' => '#dcfce7', 'icon' => 'bi-star-fill'],
            'accepted'    => ['label' => 'Accepted',     'color' => '#15803d', 'bg' => '#dcfce7', 'icon' => 'bi-check-circle-fill'],
            'rejected'    => ['label' => 'Not Selected', 'color' => '#dc2626', 'bg' => '#fef2f2', 'icon' => 'bi-x-circle'],
            default       => ['label' => ucfirst($this->status), 'color' => '#6b7280', 'bg' => '#f3f4f6', 'icon' => 'bi-circle'],
        };
    }

    // resume এর পূর্ণ URL
    public function getResumeUrlAttribute()
    {
        return $this->resume_path ? asset('storage/' . $this->resume_path) : null;
    }

    // status change history (timeline)
    public function statusLogs()
    {
        return $this->hasMany(JobApplicationStatusLog::class, 'job_application_id')->orderBy('changed_at');
    }
}