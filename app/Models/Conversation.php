<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_id_1', 'user_id_2', 'last_message_at', 'muted_by', 'deleted_by'];
    protected $casts = ['last_message_at' => 'datetime'];

    public function user1() { return $this->belongsTo(User::class, 'user_id_1'); }
    public function user2() { return $this->belongsTo(User::class, 'user_id_2'); }
    public function messages() { return $this->hasMany(Message::class); }

    // Conversation uniquely get/create করা
    public static function getOrCreate($userId1, $userId2)
    {
        if ($userId1 > $userId2) [$userId1, $userId2] = [$userId2, $userId1];
        return self::firstOrCreate(['user_id_1' => $userId1, 'user_id_2' => $userId2]);
    }

    // Other user get করা (current user থেকে opposite)
    public function getOtherUser($currentId)
    {
        return $this->user_id_1 === $currentId ? $this->user2 : $this->user1;
    }
}