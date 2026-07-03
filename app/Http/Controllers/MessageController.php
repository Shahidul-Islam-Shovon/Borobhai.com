<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'recipient_id' => 'required|integer|exists:users,id',
                'message' => 'required|string|min:1|max:5000',
            ]);

            $msg = Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $validated['recipient_id'],
                'message' => $validated['message'],
            ]);

            return response()->json(['success' => true, 'message' => 'Message sent']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

    public function thread($userId)
    {
        try {
            $me = Auth::id();
            $userId = (int) $userId;

            $messages = Message::where(function($q) use ($me, $userId) {
                $q->where('sender_id', $me)->where('recipient_id', $userId)
                  ->orWhere('sender_id', $userId)->where('recipient_id', $me);
            })
            ->orderBy('created_at', 'asc')
            ->limit(100)
            ->get();

            // Mark received messages as read
            Message::where('recipient_id', $me)->where('sender_id', $userId)
                ->whereNull('read_at')->update(['read_at' => now()]);

            return response()->json(['messages' => $messages->map(function($m) use ($me) {
                return [
                    'id' => $m->id,
                    'sender_id' => $m->sender_id,
                    'message' => $m->message,
                    'created_at' => $m->created_at->format('g:i A'),
                    'is_mine' => $m->sender_id === $me,
                ];
            })]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function unreadCount()
    {
        $count = Message::where('recipient_id', Auth::id())->whereNull('read_at')->count();
        return response()->json(['count' => $count]);
    }
}