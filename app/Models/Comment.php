<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Hashidable;

class Comment extends Model
{

    use Hashidable;
    
    protected $fillable = ['post_id', 'user_id', 'content', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // reply গুলো (এই কমেন্টের সন্তান) — পুরনো আগে
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user')->oldest();
    }

    // parent কমেন্ট (reply হলে)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // এই কমেন্টের সব like
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    // বর্তমান ইউজার like দিয়েছে কিনা
    public function isLikedByCurrentUser()
    {
        if (!auth()->check()) return false;
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

}