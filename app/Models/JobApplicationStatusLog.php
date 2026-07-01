<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicationStatusLog extends Model
{
    protected $fillable = [
        'job_application_id', 'status', 'changed_by', 'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    // status এর label/color/icon — JobApplication এর সাথে মিল রেখে
    public function getMetaAttribute()
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
}