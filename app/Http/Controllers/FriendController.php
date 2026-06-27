<?php
namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use App\Models\BbNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    // ==========================================
    // FRIENDS LIST PAGE
    // ==========================================
    public function friendsList()
    {
        $meId      = Auth::id();
        $friendIds = Friendship::friendIds($meId);

        $friends = User::whereIn('id', $friendIds)
            ->select('id', 'name', 'role', 'profile_picture', 'department', 'section', 'last_seen')
            ->get()
            ->map(function ($u) use ($meId) {
                $u->mutual = Friendship::mutualCount($meId, $u->id);
                // last seen format
                $u->last_seen_text = $this->formatLastSeen($u->last_seen);
                $u->is_online = $u->last_seen && $u->last_seen >= now()->subMinutes(10);
                return $u;
            });

        // Pending sent
        $pendingSent = Friendship::where('sender_id', $meId)->where('status', 'pending')
            ->with('receiver:id,name,role,profile_picture,department,section')
            ->latest()->get();

        // Pending received
        $pendingReceived = Friendship::where('receiver_id', $meId)->where('status', 'pending')
            ->with('sender:id,name,role,profile_picture,department,section')
            ->latest()->get();

        // Blocked
        $blocked = Friendship::where('sender_id', $meId)->where('status', 'blocked')
            ->with('receiver:id,name,profile_picture')
            ->get();

        return view('friends.index', compact('friends', 'pendingSent', 'pendingReceived', 'blocked'));
    }

    // ==========================================
    // SUGGESTED CONTACTS — 5 জন (dashboard)
    // ==========================================
    public static function getSuggested(int $meId, int $limit = 5): \Illuminate\Support\Collection
    {
        $friendIds = Friendship::friendIds($meId);
        $notInterestedIds = DB::table('not_interested_users')
            ->where('user_id', $meId)->pluck('ignored_user_id')->toArray();

        // আমার সাথে যেকোনো friendship আছে তাদের id (pending/blocked সহ)
        $existingIds = Friendship::where('sender_id', $meId)->orWhere('receiver_id', $meId)
            ->get()->flatMap(fn($f) => [$f->sender_id, $f->receiver_id])
            ->filter(fn($id) => $id != $meId)->unique()->toArray();

        $excludeIds = array_unique(array_merge([$meId], $existingIds, $notInterestedIds));

        // Friend of friends (mutual connections)
        $friendOfFriendIds = [];
        if (!empty($friendIds)) {
            $fof = Friendship::where('status', 'accepted')
                ->where(function ($q) use ($friendIds) {
                    $q->whereIn('sender_id', $friendIds)->orWhereIn('receiver_id', $friendIds);
                })
                ->get()
                ->flatMap(fn($f) => [$f->sender_id, $f->receiver_id])
                ->filter(fn($id) => !in_array($id, $excludeIds))
                ->unique()->values()->toArray();
            $friendOfFriendIds = $fof;
        }

        // Priority: same dept > friend of friends > others
        $me = User::find($meId);

        $users = User::whereNotIn('id', $excludeIds)
            ->where('role', '!=', 'admin')
            ->select('id', 'name', 'role', 'profile_picture', 'department', 'section')
            ->with(['experiences' => fn($q) => $q->where('is_current', true)->select('user_id', 'company', 'designation')->limit(1)])
            ->inRandomOrder()
            ->limit(50)
            ->get()
            ->map(function ($u) use ($meId, $friendIds, $me, $friendOfFriendIds) {
                $myFriends    = Friendship::friendIds($meId);
                $theirFriends = Friendship::friendIds($u->id);
                $u->mutual    = count(array_intersect($myFriends, $theirFriends));

                // Priority score
                $score = 0;
                if ($me && $u->department && $u->department === $me->department) $score += 3;
                if (in_array($u->id, $friendOfFriendIds)) $score += 2;
                if ($u->mutual > 0) $score += $u->mutual;

                $u->priority = $score;

                // Check pending
                $u->is_pending = Friendship::where('sender_id', $meId)
                    ->where('receiver_id', $u->id)->where('status', 'pending')->exists();

                // Current job info for alumni
                $exp = $u->experiences->first();
                $u->current_company    = $exp?->company;
                $u->current_designation = $exp?->designation;

                return $u;
            })
            ->sortByDesc('priority')
            ->take($limit)
            ->values();

        return $users;
    }

    // ==========================================
    // SEE ALL SUGGESTED — Full page
    // ==========================================
    public function suggestedAll(Request $request)
    {
        $meId = Auth::id();
        $friendIds = Friendship::friendIds($meId);
        $notInterestedIds = DB::table('not_interested_users')
            ->where('user_id', $meId)->pluck('ignored_user_id')->toArray();

        $existingIds = Friendship::where('sender_id', $meId)->orWhere('receiver_id', $meId)
            ->get()->flatMap(fn($f) => [$f->sender_id, $f->receiver_id])
            ->filter(fn($id) => $id != $meId)->unique()->toArray();

        $excludeIds = array_unique(array_merge([$meId], $existingIds, $notInterestedIds));

        $me = User::find($meId);

        $query = User::whereNotIn('id', $excludeIds)
            ->where('role', '!=', 'admin')
            ->select('id', 'name', 'role', 'profile_picture', 'department', 'section')
            ->with(['experiences' => fn($q) => $q->where('is_current', true)->limit(1)]);

        // Filter by role
        if ($request->filter && $request->filter !== 'all') {
            $query->where('role', $request->filter);
        }

        $users = $query->paginate(12);

        $users->getCollection()->transform(function ($u) use ($meId, $me) {
            $myFriends    = Friendship::friendIds($meId);
            $theirFriends = Friendship::friendIds($u->id);
            $u->mutual    = count(array_intersect($myFriends, $theirFriends));
            $u->is_pending = Friendship::where('sender_id', $meId)
                ->where('receiver_id', $u->id)->where('status', 'pending')->exists();
            $exp = $u->experiences->first();
            $u->current_company = $exp?->company;
            return $u;
        });

        return view('friends.suggested', compact('users'));
    }

    // ==========================================
    // NOT INTERESTED
    // ==========================================
    public function notInterested(Request $request)
    {
        $targetId = $request->user_id;
        if (!$targetId || $targetId == Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Invalid user']);
        }
    
        DB::table('not_interested_users')->updateOrInsert(
            ['user_id' => Auth::id(), 'ignored_user_id' => $targetId],
            ['created_at' => now(), 'updated_at' => now()]
        );
    
        return response()->json(['success' => true, 'message' => 'Removed from suggestions']);
    }
    
   public function messengerContacts()
    {
        $friends = Auth::user()->friends()
            ->select('users.id', 'users.name', 'users.profile_picture', 'users.last_seen')
            ->get()
            ->map(function($u) {
                return [
                    'id'              => $u->id,
                    'name'            => $u->name,
                    'profile_picture' => $u->profile_picture,
                    'is_online'       => $u->last_seen && $u->last_seen >= now()->subMinutes(10),
                ];
            });

        return response()->json(['contacts' => $friends]);
    }

    // ==========================================
    // SEND REQUEST
    // ==========================================
    public function sendRequest(Request $request)
    {
        $request->validate(['user_id' => 'required|integer|exists:users,id']);
        $meId     = Auth::id();
        $otherId  = $request->user_id;

        if ($meId === $otherId) return response()->json(['success' => false, 'message' => 'Cannot send request to yourself.']);
        if (Friendship::areFriends($meId, $otherId)) return response()->json(['success' => false, 'message' => 'Already friends.']);

        $existing = Friendship::between($meId, $otherId);
        if ($existing) return response()->json(['success' => false, 'message' => 'Request already exists.']);

        Friendship::create(['sender_id' => $meId, 'receiver_id' => $otherId, 'status' => 'pending']);

        // Notification
        $me = Auth::user();
        BbNotification::send($otherId, $meId, 'friend_request', $me->name . ' sent you a friend request.', 'user', $meId);

        return response()->json(['success' => true, 'message' => 'Friend request sent!', 'status' => 'pending_sent']);
    }

    // ==========================================
    // ACCEPT
    // ==========================================
    public function acceptRequest(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $meId = Auth::id();

        $record = Friendship::where('sender_id', $request->user_id)->where('receiver_id', $meId)->where('status', 'pending')->first();
        if (!$record) return response()->json(['success' => false, 'message' => 'Request not found.']);

        $record->update(['status' => 'accepted']);

        // Notification to sender
        $me = Auth::user();
        BbNotification::send($request->user_id, $meId, 'friend_accept', $me->name . ' accepted your friend request.', 'user', $meId);

        return response()->json(['success' => true, 'message' => 'Friend request accepted!', 'status' => 'accepted']);
    }

    // ==========================================
    // DECLINE
    // ==========================================
    public function declineRequest(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $meId = Auth::id();

        Friendship::where('sender_id', $request->user_id)->where('receiver_id', $meId)->where('status', 'pending')->delete();

        return response()->json(['success' => true, 'message' => 'Request declined.']);
    }

    // ==========================================
    // CANCEL
    // ==========================================
    public function cancelRequest(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $meId = Auth::id();

        Friendship::where('sender_id', $meId)->where('receiver_id', $request->user_id)->where('status', 'pending')->delete();

        return response()->json(['success' => true, 'message' => 'Request cancelled.', 'status' => 'none']);
    }

    // ==========================================
    // UNFRIEND
    // ==========================================
    public function unfriend(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $meId = Auth::id();

        Friendship::where('status', 'accepted')
            ->where(fn($q) => $q->where('sender_id', $meId)->where('receiver_id', $request->user_id))
            ->orWhere(fn($q) => $q->where('sender_id', $request->user_id)->where('receiver_id', $meId)->where('status', 'accepted'))
            ->delete();

        return response()->json(['success' => true, 'message' => 'Removed from friends.', 'status' => 'none']);
    }

    // ==========================================
    // BLOCK
    // ==========================================
    public function block(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $meId = Auth::id();

        $existing = Friendship::between($meId, $request->user_id);
        if ($existing) {
            $existing->update(['sender_id' => $meId, 'receiver_id' => $request->user_id, 'status' => 'blocked']);
        } else {
            Friendship::create(['sender_id' => $meId, 'receiver_id' => $request->user_id, 'status' => 'blocked']);
        }

        return response()->json(['success' => true, 'message' => 'User blocked.', 'status' => 'blocked']);
    }

    // ==========================================
    // UNBLOCK
    // ==========================================
    public function unblock(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $meId = Auth::id();

        Friendship::where('sender_id', $meId)->where('receiver_id', $request->user_id)->where('status', 'blocked')->delete();

        return response()->json(['success' => true, 'message' => 'User unblocked.', 'status' => 'none']);
    }

    // ==========================================
    // STATUS CHECK
    // ==========================================
    public function statusWith($userId)
    {
        $status = Friendship::statusWith(Auth::id(), $userId);
        $mutual = Friendship::mutualCount(Auth::id(), $userId);
        return response()->json(['status' => $status, 'mutual' => $mutual]);
    }

    // ==========================================
    // MUTUAL FRIENDS LIST
    // ==========================================
    public function mutualFriends(Request $request, $userId)
    {
        $meId         = Auth::id();
        $myFriends    = Friendship::friendIds($meId);
        $theirFriends = Friendship::friendIds($userId);
        $mutualIds    = array_intersect($myFriends, $theirFriends);

        $mutuals = User::whereIn('id', $mutualIds)
            ->select('id', 'name', 'role', 'profile_picture', 'department')
            ->get();

        return response()->json(['mutuals' => $mutuals, 'count' => count($mutualIds)]);
    }

    // ==========================================
    // LAST SEEN FORMAT (Facebook style)
    // ==========================================
    public static function formatLastSeen($lastSeen): string
    {
        if (!$lastSeen) return 'Never';
        $lastSeen = \Carbon\Carbon::parse($lastSeen);
        $diffMin  = now()->diffInMinutes($lastSeen);
        $diffHour = now()->diffInHours($lastSeen);
        $diffDay  = now()->diffInDays($lastSeen);

        if ($diffMin < 10)  return 'Active now';
        if ($diffMin < 60)  return 'Active ' . $diffMin . 'm ago';
        if ($diffHour < 24) return 'Active ' . $diffHour . 'h ago';
        if ($diffDay < 7)   return 'Active ' . $diffDay . 'd ago';
        return 'Active ' . $lastSeen->format('M d');
    }

    private function getBlockedIds(int $meId): array
    {
        return Friendship::where('status', 'blocked')
            ->where(fn($q) => $q->where('sender_id', $meId)->orWhere('receiver_id', $meId))
            ->get()->flatMap(fn($f) => [$f->sender_id, $f->receiver_id])
            ->filter(fn($id) => $id != $meId)->unique()->values()->toArray();
    }
}