<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
    'sender_id',
    'receiver_id',
    'message',

    'attachment',
    'attachment_type',
    'attachment_name',
    'attachment_size',

    'is_seen',
    'seen_at',
    'is_unsent',
    'unsent_at',
    'deleted_by_sender',
    'deleted_by_receiver',
];

    protected $casts = [
        'is_seen' => 'boolean',
        'is_unsent' => 'boolean',
        'deleted_by_sender' => 'boolean',
        'deleted_by_receiver' => 'boolean',
        'seen_at' => 'datetime',
        'unsent_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }
}