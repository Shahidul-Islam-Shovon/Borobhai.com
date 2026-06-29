<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Hashidable;

class Friendship extends Model
{

    use Hashidable;
    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    // ===== Relations =====
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // ===== Static Helpers =====

    /**
     * দুজনের মধ্যে friendship record খোঁজা (যে কেউ sender হতে পারে)
     */
    public static function between($userId, $otherId)
    {
        return self::where(function ($q) use ($userId, $otherId) {
            $q->where('sender_id', $userId)->where('receiver_id', $otherId);
        })->orWhere(function ($q) use ($userId, $otherId) {
            $q->where('sender_id', $otherId)->where('receiver_id', $userId);
        })->first();
    }

    /**
     * দুজন friend কিনা
     */
    public static function areFriends(int $userId, int $otherId): bool
    {
        return static::where('status', 'accepted')
            ->where(function ($q) use ($userId, $otherId) {
                $q->where(function ($inner) use ($userId, $otherId) {
                    $inner->where('sender_id', $userId)->where('receiver_id', $otherId);
                })->orWhere(function ($inner) use ($userId, $otherId) {
                    $inner->where('sender_id', $otherId)->where('receiver_id', $userId);
                });
            })
            ->exists();
    }

    /**
     * কেউ blocked কিনা
     */
    public static function isBlocked($userId, $otherId): bool
    {
        return self::where('status', 'blocked')
            ->where(function ($q) use ($userId, $otherId) {
                $q->where('sender_id', $userId)->where('receiver_id', $otherId);
            })->orWhere(function ($q) use ($userId, $otherId) {
                $q->where('sender_id', $otherId)->where('receiver_id', $userId);
            })->exists();
    }

    /**
     * আমার সব friend এর id list
     */
    public static function friendIds($userId): array
    {
        $sent = self::where('sender_id', $userId)->where('status', 'accepted')->pluck('receiver_id');
        $recv = self::where('receiver_id', $userId)->where('status', 'accepted')->pluck('sender_id');
        return $sent->merge($recv)->unique()->values()->toArray();
    }

    /**
     * mutual friends count
     */
    public static function mutualCount($userId, $otherId): int
    {
        $myFriends    = self::friendIds($userId);
        $theirFriends = self::friendIds($otherId);
        return count(array_intersect($myFriends, $theirFriends));
    }

    /**
     * আমার সাথে কারো friendship status
     * return: 'none' | 'pending_sent' | 'pending_received' | 'accepted' | 'blocked'
     */
    public static function statusWith($meId, $otherId): string
    {
        $record = self::between($meId, $otherId);
        if (!$record) return 'none';

        if ($record->status === 'accepted') return 'accepted';
        if ($record->status === 'blocked')  return 'blocked';

        // pending — আমি পাঠিয়েছি নাকি পেয়েছি?
        if ($record->sender_id == $meId) return 'pending_sent';
        return 'pending_received';
    }

    public static function mutualFriends(int $userId, int $otherUserId): \Illuminate\Support\Collection
    {
        $myFriends    = static::friendIds($userId);
        $otherFriends = static::friendIds($otherUserId);
        $mutualIds    = array_intersect($myFriends, $otherFriends);
    
        if (empty($mutualIds)) return collect();
    
        return \App\Models\User::whereIn('id', $mutualIds)
            ->select('id', 'name', 'role', 'department', 'profile_picture')
            ->limit(10)
            ->get();
    }

    // public function messengerContacts()
    // {
    //     $userId = Auth::id();

    //     $contacts = \App\Models\User::whereIn('id', function($q) use ($userId) {
    //         $q->select('sender_id')
    //         ->from('friendships')
    //         ->where('receiver_id', $userId)
    //         ->where('status', 'accepted')
    //         ->union(
    //             \DB::table('friendships')
    //                 ->select('receiver_id')
    //                 ->where('sender_id', $userId)
    //                 ->where('status', 'accepted')
    //         );
    //     })
    //     ->select('id', 'name', 'profile_picture')
    //     ->orderBy('name')
    //     ->limit(20)
    //     ->get();

    //     return response()->json(['contacts' => $contacts]);
    // }

}