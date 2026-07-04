<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    // ===== SEND MESSAGE (with optional media) =====
    public function send(Request $request)
{
    $validated = $request->validate([
        'recipient_id' => 'required|integer|exists:users,id',
        'message' => 'nullable|string|max:5000',
        'media' => 'nullable|array|max:5',
        'media.*' => 'file|max:26214400',  // 25MB
    ]);

    $me = Auth::id();
    if ($validated['recipient_id'] == $me) abort(403);

    $conv = Conversation::getOrCreate($me, $validated['recipient_id']);

    $msg = new Message([
        'sender_id' => $me,
        'recipient_id' => $validated['recipient_id'],
        'message' => $validated['message'] ?: '',
        'conversation_id' => $conv->id,
        'delivered_at' => now(),
    ]);

    // Media upload
    if ($request->hasFile('media')) {
        $files = [];
        foreach ($request->file('media') as $file) {
            $path = $file->store('messages/' . date('Y/m'), 'public');
            $files[] = [
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'type' => in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp']) ? 'image' : (str_contains($file->getMimeType(), 'video') ? 'video' : 'file'),
                'size' => $file->getSize(),
            ];
        }
        $msg->file_path = json_encode($files);
    }

    $msg->save();
    $conv->update(['last_message_at' => now()]);

    return response()->json(['success' => true, 'message_id' => $msg->id]);
}

    // ===== FETCH THREAD (paginated) =====
    public function thread($userId)
{
    try {
        $me = Auth::id();
        $userId = (int) $userId;

        // Conversation খোঁজো
        $conv = Conversation::whereRaw("
            (user_id_1 = ? AND user_id_2 = ?) OR 
            (user_id_1 = ? AND user_id_2 = ?)
        ", [$me, $userId, $userId, $me])->first();

        // না থাকলে create করো
        if (!$conv) {
            $conv = Conversation::getOrCreate($me, $userId);
        }

        // Get messages
        $messages = Message::where('conversation_id', $conv->id)
            ->where('is_deleted', false)
            // পরে (ঠিক — desc তারপর reverse = oldest-first of last 50)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        // Mark as read
        Message::where('conversation_id', $conv->id)
            ->where('recipient_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now(), 'seen_at' => now()]);

        return response()->json([
            'messages' => $messages->map(function($m) use ($me) {
                return [
                    'id' => $m->id,
                    'sender_id' => $m->sender_id,
                    'message' => $m->message,
                    'created_at' => $m->created_at->format('g:i A'),
                    'is_mine' => $m->sender_id === $me,
                    'status' => 'sent',
                ];
            })
        ]);
    } catch (\Exception $e) {
        \Log::error('Message thread error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
        

    // ===== CONVERSATION LIST (all users, last message) =====
    public function conversations(Request $request)
    {
        $me = Auth::id();
        $search = $request->get('q', '');

        $convs = Conversation::where(function($q) use ($me) {
            $q->where('user_id_1', $me)->orWhere('user_id_2', $me);
        })
        ->with(['user1', 'user2', 'messages' => function($q) { $q->latest()->limit(1); }])
        ->orderByDesc('last_message_at')
        ->get();

        $list = $convs->map(function($c) use ($me, $search) {
            $other = $c->getOtherUser($me);
            if ($search && !str_contains(strtolower($other->name), strtolower($search))) return null;

            $lastMsg = $c->messages->first();
            $unread = Message::where('conversation_id', $c->id)
                ->where('recipient_id', $me)
                ->whereNull('read_at')
                ->count();

            return [
                'user_id' => $other->id,
                'name' => $other->name,
                'avatar' => $other->profile_picture ? asset('storage/' . $other->profile_picture) : null,
                'last_message' => $lastMsg ? ($lastMsg->sender_id === $me ? 'You: ' : '') . substr($lastMsg->message ?: '[Media]', 0, 50) : '',
                'last_at' => $lastMsg ? $lastMsg->created_at->diffForHumans() : '',
                'unread' => $unread,
            ];
        })->filter()->values();

        return response()->json(['conversations' => $list]);
    }

    // ===== EDIT MESSAGE =====
    public function editMessage($id, Request $request)
    {
        $msg = Message::findOrFail($id);
        if ($msg->sender_id !== Auth::id()) abort(403);

        $msg->update(['message' => $request->validate(['message' => 'required|string|max:5000'])['message']]);
        return response()->json(['success' => true]);
    }

    // ===== DELETE (soft: hide for sender) =====
    public function deleteMessage($id, Request $request)
    {
        $msg = Message::findOrFail($id);
        if ($msg->sender_id !== Auth::id()) abort(403);

        $action = $request->get('action', 'me');  // me | everyone
        if ($action === 'me') {
            $msg->update(['is_deleted' => true]);
        } else {
            $msg->update(['message' => '[This message was deleted]', 'file_path' => null]);
        }
        return response()->json(['success' => true]);
    }

    // ===== UNREAD COUNT (navbar badge) =====
    public function unreadCount()
    {
        $count = Message::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->distinct('conversation_id')
            ->count('conversation_id');
        return response()->json(['count' => $count]);
    }

    // ===== REACT TO MESSAGE =====
    public function reactMessage($id, Request $request)
    {
        $msg = Message::findOrFail($id);
        $emoji = $request->validate(['emoji' => 'required|string|max:2'])['emoji'];

        $reactions = json_decode($msg->message_reactions ?? '{}', true);
        $me = Auth::id();
        if (isset($reactions[$emoji])) {
            if (in_array($me, $reactions[$emoji])) {
                $reactions[$emoji] = array_filter($reactions[$emoji], fn($id) => $id !== $me);
                if (empty($reactions[$emoji])) unset($reactions[$emoji]);
            } else {
                $reactions[$emoji][] = $me;
            }
        } else {
            $reactions[$emoji] = [$me];
        }

        $msg->update(['message_reactions' => json_encode($reactions)]);
        return response()->json(['success' => true, 'reactions' => $reactions]);
    }
}