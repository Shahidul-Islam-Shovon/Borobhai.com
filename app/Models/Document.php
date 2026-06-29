<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Hashidable;

class Document extends Model

{
    use Hashidable;
    
    protected $fillable = [
        'user_id', 'title', 'type', 'description', 'topic',
        'file_path', 'file_name', 'file_type', 'file_size', 'publication_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ফাইল সাইজ সুন্দর করে দেখানোর জন্য (2.4 MB টাইপ)
    public function getReadableSizeAttribute()
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes >= 6048576) return round($bytes / 6048576, 1) . ' MB';
        if ($bytes >= 6024)    return round($bytes / 6024, 1) . ' KB';
        return $bytes . ' B';
    }
}