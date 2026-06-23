<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    // ১. Request পাঠানো
    public function sendRequest(Request $request)
    {
        $meId     = Auth::id();
        $otherId  = $request->user_id;

        if ($meId == $otherId) {
            return response()->json(['success' => false, 'message' => 'Invalid request.'], 422);
        }

        // আগে আছে কিনা দেখো
        $existing = Friendship::between($meId, $otherId);
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Already exists.'], 422);
        }

        Friendship::create([
            'sender_id'   => $meId,
            'receiver_id' => $otherId,
            'status'      => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'status'  => 'pending_sent',
            'message' => 'Friend request sent!',
        ]);
    }

    // ২. Accept
    public function acceptRequest(Request $request)
    {
        $meId    = Auth::id();
        $otherId = $request->user_id;

        $record = Friendship::where('sender_id', $otherId)
            ->where('receiver_id', $meId)
            ->where('status', 'pending')
            ->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
        }

        $record->update(['status' => 'accepted']);

        return response()->json([
            'success' => true,
            'status'  => 'accepted',
            'message' => 'Friend request accepted!',
        ]);
    }

    // ৩. Decline
    public function declineRequest(Request $request)
    {
        $meId    = Auth::id();
        $otherId = $request->user_id;

        Friendship::where('sender_id', $otherId)
            ->where('receiver_id', $meId)
            ->where('status', 'pending')
            ->delete();

        return response()->json([
            'success' => true,
            'status'  => 'none',
            'message' => 'Request declined.',
        ]);
    }

    // ৪. Cancel (নিজে পাঠানো request তুলে নেওয়া)
    public function cancelRequest(Request $request)
    {
        $meId    = Auth::id();
        $otherId = $request->user_id;

        Friendship::where('sender_id', $meId)
            ->where('receiver_id', $otherId)
            ->where('status', 'pending')
            ->delete();

        return response()->json([
            'success' => true,
            'status'  => 'none',
            'message' => 'Request cancelled.',
        ]);
    }

    // ৫. Unfriend
    public function unfriend(Request $request)
    {
        $meId    = Auth::id();
        $otherId = $request->user_id;

        Friendship::where('status', 'accepted')
            ->where(function ($q) use ($meId, $otherId) {
                $q->where('sender_id', $meId)->where('receiver_id', $otherId);
            })->orWhere(function ($q) use ($meId, $otherId) {
                $q->where('sender_id', $otherId)->where('receiver_id', $meId);
            })->delete();

        return response()->json([
            'success' => true,
            'status'  => 'none',
            'message' => 'Unfriended successfully.',
        ]);
    }

    // ৬. Block
    public function block(Request $request)
    {
        $meId    = Auth::id();
        $otherId = $request->user_id;

        // আগের সব record মুছে নতুন blocked record
        Friendship::where(function ($q) use ($meId, $otherId) {
            $q->where('sender_id', $meId)->where('receiver_id', $otherId);
        })->orWhere(function ($q) use ($meId, $otherId) {
            $q->where('sender_id', $otherId)->where('receiver_id', $meId);
        })->delete();

        Friendship::create([
            'sender_id'   => $meId,
            'receiver_id' => $otherId,
            'status'      => 'blocked',
        ]);

        return response()->json([
            'success' => true,
            'status'  => 'blocked',
            'message' => 'User blocked.',
        ]);
    }

    // ৭. Unblock
    public function unblock(Request $request)
    {
        $meId    = Auth::id();
        $otherId = $request->user_id;

        Friendship::where('sender_id', $meId)
            ->where('receiver_id', $otherId)
            ->where('status', 'blocked')
            ->delete();

        return response()->json([
            'success' => true,
            'status'  => 'none',
            'message' => 'User unblocked.',
        ]);
    }

    // ৮. Friends list page
    public function friendsList()
    {
        $meId = Auth::id();

        $friends = User::whereIn('id', Friendship::friendIds($meId))
            ->select('id', 'name', 'role', 'department', 'session', 'profile_picture')
            ->with('currentJob')
            ->orderBy('name')
            ->get();

        $pendingReceived = Friendship::where('receiver_id', $meId)
            ->where('status', 'pending')
            ->with('sender:id,name,role,department,profile_picture')
            ->latest()
            ->get();

        $pendingSent = Friendship::where('sender_id', $meId)
            ->where('status', 'pending')
            ->with('receiver:id,name,role,department,profile_picture')
            ->latest()
            ->get();

        return view('friends.index', compact('friends', 'pendingReceived', 'pendingSent'));
    }

    // ৯. Profile-এ button state API
    public function statusWith($userId)
    {
        $meId  = Auth::id();
        $status = Friendship::statusWith($meId, $userId);
        $mutual = Friendship::mutualCount($meId, $userId);

        return response()->json([
            'success' => true,
            'status'  => $status,
            'mutual'  => $mutual,
        ]);
    }
}