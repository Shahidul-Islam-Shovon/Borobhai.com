<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['reporter_id', 'type', 'target_id', 'reason', 'details', 'status'];

    public function reporter() { return $this->belongsTo(User::class, 'reporter_id'); }

    // target মডেল dynamic resolve
    public function target()
    {
        return match($this->type) {
            'post' => $this->belongsTo(Post::class, 'target_id'),
            'job'  => $this->belongsTo(JobPost::class, 'target_id'),
            'user' => $this->belongsTo(User::class, 'target_id'),
            default => null,
        };
    }

    // target নামের সহজ accessor
    public function getTargetNameAttribute(): string
    {
        return match($this->type) {
            'post' => 'Post #' . $this->target_id,
            'job'  => 'Job #' . $this->target_id,
            'user' => User::find($this->target_id)?->name ?? 'Unknown User',
        };
    }
}