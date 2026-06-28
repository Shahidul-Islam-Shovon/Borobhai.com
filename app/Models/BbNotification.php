<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BbNotification extends Model
{
    protected $table = 'bb_notifications';

    protected $fillable = [
        'user_id', 'actor_id', 'type',
        'notifiable_type', 'notifiable_id',
        'message', 'is_read', 'seen',
    ];

    protected $casts = ['is_read' => 'boolean', 'seen' => 'boolean'];

    public function user()   { return $this->belongsTo(User::class); }
    public function actor()  { return $this->belongsTo(User::class, 'actor_id'); }

    // ==========================================
    // STATIC CREATOR — সব জায়গা থেকে call করা যাবে
    // ==========================================
    public static function send(int $toUserId, int $actorId, string $type, string $message, string $notifiableType = null, int $notifiableId = null): void
    {
        if ($toUserId === $actorId) return; // নিজেকে notification নয়

        static::create([
            'user_id'         => $toUserId,
            'actor_id'        => $actorId,
            'type'            => $type,
            'message'         => $message,
            'notifiable_type' => $notifiableType,
            'notifiable_id'   => $notifiableId,
        ]);
    }

    public static function unreadCount(int $userId): int
    {
        return static::where('user_id', $userId)->where('is_read', false)->count();
    }
}