<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content', 'image'];

    // পোস্টটি কোন ইউজারের তা জানার রিলেশনশিপ
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}