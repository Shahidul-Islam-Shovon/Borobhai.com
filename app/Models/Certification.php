<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{

    protected $table = 'certifications';
    protected $fillable = [
        'user_id', 'title', 'organization', 'issue_date', 'credential_url',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}