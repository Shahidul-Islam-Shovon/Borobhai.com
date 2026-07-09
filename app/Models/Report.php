<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id', 'type', 'target_id', 'reason', 'details', 'status',
        'admin_note', 'admin_id', 'action_taken', 'reviewed_at',
        'appeal_message', 'appeal_status', 'appealed_at',
        'admin_seen', 'history_seen', 'was_warned',
    ];

    protected $casts = [
        'reviewed_at'  => 'datetime',
        'appealed_at'  => 'datetime',
        'admin_seen'   => 'boolean',
        'history_seen' => 'boolean',
        'was_warned'   => 'boolean',
    ];

    public function reporter() { return $this->belongsTo(User::class, 'reporter_id'); }

    public function target()
    {
        return match($this->type) {
            'post' => $this->belongsTo(Post::class, 'target_id'),
            'job'  => $this->belongsTo(JobPost::class, 'target_id'),
            'user' => $this->belongsTo(User::class, 'target_id'),
            default => null,
        };
    }

    public function getTargetNameAttribute(): string
    {
        return match($this->type) {
            'post' => 'Post #' . $this->target_id,
            'job'  => 'Job #' . $this->target_id,
            'user' => User::find($this->target_id)?->name ?? 'Unknown User',
        };
    }

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

    public static function isReportedByMe($reporterId, $type, $targetId): bool
    {
        if (!$reporterId) return false;
        return static::where('reporter_id', $reporterId)
            ->where('type', $type)
            ->where('target_id', $targetId)
            ->where('status', 'pending')
            ->exists();
    }
    
}