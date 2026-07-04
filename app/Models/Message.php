<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'sender_id', 'recipient_id', 'message', 'file_path', 'file_type', 'file_size', 'read_at', 'delivered_at', 'seen_at', 'is_deleted'];

    protected $casts = ['read_at' => 'datetime', 'delivered_at' => 'datetime', 'seen_at' => 'datetime', 'created_at' => 'datetime',  'message' => 'encrypted',];

    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
    public function recipient() { return $this->belongsTo(User::class, 'recipient_id'); }
    public function conversation() { return $this->belongsTo(Conversation::class); }

    // Message soft delete (শুধু current user-এর জন্য লুকায়)
    public function scopeVisible($query) { return $query->where('is_deleted', false); }
}