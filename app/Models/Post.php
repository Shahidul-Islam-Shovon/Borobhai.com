<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Hashidable;

class Post extends Model
{
    use Hashidable;
    use HasFactory;

    // এই কলামগুলোতে ডাটা ইনসার্ট করা যাবে
    protected $fillable = [
        'user_id', 
        'content', 
        'parent_id', 
        'images', 
        'video', 
        'bg_color',
        'privacy',
    ];

    // ডাটাবেজের JSON কে অ্যারে হিসেবে ব্যবহার করার জন্য
    protected $casts = [
        'images' => 'array',
        'video' => 'string',
        'bg_color' => 'string'
    ];

    // পোস্টটি কোন ইউজারের তা জানার রিলেশনশিপ
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parentPost()
    {
        return $this->belongsTo(Post::class, 'parent_id')->with('user');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    // বর্তমান ইউজার পোস্টটিতে লাইক দিয়েছে কি না তা চেক করার জন্য
    public function isLikedByAuthUser()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    // এই পোস্ট যারা সেভ করেছে
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_posts')->withTimestamps();
    }

    // বর্তমান ইউজার এই পোস্ট সেভ করেছে কিনা (হেল্পার)
    public function isSavedByCurrentUser()
    {
        if (!auth()->check()) return false;
        return $this->savedByUsers()->where('user_id', auth()->id())->exists();
    }
}