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
    // ===== SEND MESSAGE (with optional media + reply) =====
    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|integer|exists:users,id',
            'message' => 'nullable|string|max:5000',
            'media' => 'nullable|array|max:5',
            'media.*' => 'file|max:26214400',
            'reply_to_id' => 'nullable|integer|exists:messages,id',
        ]);

        $me = Auth::id();
        if ($validated['recipient_id'] == $me) abort(403);

        $conv = Conversation::getOrCreate($me, $validated['recipient_id']);
        $rawMessage = $validated['message'] ?: '';

        $msg = new Message([
            'sender_id' => $me,
            'recipient_id' => $validated['recipient_id'],
            'message' => $rawMessage,
            'conversation_id' => $conv->id,
            'reply_to_id' => $validated['reply_to_id'] ?? null,
            'delivered_at' => now(),
        ]);

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
        } elseif ($rawMessage) {
            // মিডিয়া নেই — টেক্সটে নিজের সাইটের post/job লিংক আছে কিনা চেক করো
            $meta = $this->extractShareMeta($rawMessage);
            if ($meta) {
                $msg->share_meta = json_encode($meta);
                // শুধু লিংকই পাঠানো হলে raw টেক্সট আর দেখানোর দরকার নেই, কার্ডই যথেষ্ট
                if (trim($rawMessage) === $meta['url'] || preg_match('~^https?://\S+$~', trim($rawMessage))) {
                    $msg->message = '';
                }
            }
        }

        $msg->save();
        $conv->update(['last_message_at' => now(), 'deleted_by' => '[]']);

        return response()->json(['success' => true, 'message_id' => $msg->id]);
    }

    private function formatMedia($m)
    {
        $media = [];
        if ($m->file_path) {
            $decoded = json_decode($m->file_path, true);
            if (is_array($decoded)) {
                foreach ($decoded as $f) {
                    $media[] = [
                        'url'  => asset('storage/' . $f['path']),
                        'name' => $f['name'] ?? basename($f['path']),
                        'type' => $f['type'] ?? 'file',
                        'size' => $f['size'] ?? 0,
                    ];
                }
            }
        }
        return $media;
    }

    private function formatReactions($m, $me)
    {
        $raw = json_decode($m->message_reactions ?? '{}', true);
        if (!$raw) return [];
        $allIds = [];
        foreach ($raw as $ids) $allIds = array_merge($allIds, $ids);
        $allIds = array_unique($allIds);
        $names = User::whereIn('id', $allIds)->pluck('name', 'id');

        $out = [];
        foreach ($raw as $emoji => $ids) {
            if (!$ids) continue;
            $out[$emoji] = array_map(function ($id) use ($names, $me) {
                return ['id' => $id, 'name' => $id == $me ? 'You' : ($names[$id] ?? 'User')];
            }, $ids);
        }
        return $out;
    }

    private function formatMessage($m, $me)
    {
        $reply = null;
        if ($m->reply_to_id) {
            $r = Message::find($m->reply_to_id);
            if ($r) {
                $reply = [
                    'id' => $r->id,
                    'message' => $r->is_deleted ? '[Deleted message]' : ($r->message ?: '[Media]'),
                    'sender_name' => $r->sender_id === $me ? 'You' : ($r->sender->name ?? 'User'),
                ];
            }
        }

        return [
            'id' => $m->id,
            'sender_id' => $m->sender_id,
            'message' => $m->is_deleted ? '' : $m->message,
            'is_deleted' => (bool) $m->is_deleted,
            'is_edited' => (bool) $m->is_edited,
            'forwarded' => (bool) $m->forwarded,
            'media' => $m->is_deleted ? [] : $this->formatMedia($m),
            'reply_to' => $reply,
            'reactions' => $this->formatReactions($m, $me),
            // ফ্রন্টএন্ডে "১৫ মিনিটের পর এডিট বন্ধ" লজিকের জন্য মিলিসেকেন্ড টাইমস্ট্যাম্প
            'created_at' => $m->created_at->format('g:i A'),
            'is_mine' => $m->sender_id === $me,
            'delivered' => (bool) $m->delivered_at,
            'seen' => (bool) $m->seen_at,
            'created_at_ts' => $m->created_at->timestamp * 1000,
            'share' => $m->share_meta ? json_decode($m->share_meta, true) : null,
        ];
    }

    // ===== FETCH THREAD (latest 30) =====
    public function thread($userId)
    {
        try {
            $me = Auth::id();
            $userId = (int) $userId;

            $conv = Conversation::whereRaw("
                (user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)
            ", [$me, $userId, $userId, $me])->first();
            if (!$conv) $conv = Conversation::getOrCreate($me, $userId);

            $pool = Message::where('conversation_id', $conv->id)
                ->orderBy('created_at', 'desc')
                ->limit(80)
                ->get();

            $visible = $pool->filter(function ($m) use ($me) {
                $df = json_decode($m->deleted_for ?? '[]', true) ?: [];
                return !in_array($me, $df);
            })->values();

            $hasMore = $visible->count() > 30;
            $messages = $visible->take(30)->reverse()->values();

            Message::where('conversation_id', $conv->id)
                ->where('recipient_id', $me)
                ->whereNull('read_at')
                ->update(['read_at' => now(), 'seen_at' => now()]);

            return response()->json([
                'messages' => $messages->map(fn($m) => $this->formatMessage($m, $me)),
                'has_more' => $hasMore,
            ]);
        } catch (\Exception $e) {
            \Log::error('Message thread error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ===== OLDER MESSAGES (infinite scroll up) =====
    public function olderMessages($userId, Request $request)
    {
        $me = Auth::id();
        $userId = (int) $userId;
        $beforeId = (int) $request->get('before_id', 0);

        $conv = Conversation::whereRaw("
            (user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)
        ", [$me, $userId, $userId, $me])->first();
        if (!$conv || !$beforeId) return response()->json(['messages' => [], 'has_more' => false]);

        $pool = Message::where('conversation_id', $conv->id)
            ->where('id', '<', $beforeId)
            ->orderBy('created_at', 'desc')
            ->limit(80)
            ->get();

        $visible = $pool->filter(function ($m) use ($me) {
            $df = json_decode($m->deleted_for ?? '[]', true) ?: [];
            return !in_array($me, $df);
        })->values();

        $hasMore = $visible->count() > 30;
        $messages = $visible->take(30)->reverse()->values();

        return response()->json([
            'messages' => $messages->map(fn($m) => $this->formatMessage($m, $me)),
            'has_more' => $hasMore,
        ]);
    }

    // ===== SEARCH WITHIN CONVERSATION =====
    public function searchThread($userId, Request $request)
    {
        $me = Auth::id();
        $userId = (int) $userId;
        $q = trim($request->get('q', ''));
        if (!$q) return response()->json(['messages' => []]);

        $conv = Conversation::whereRaw("
            (user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)
        ", [$me, $userId, $userId, $me])->first();

        if (!$conv) return response()->json(['messages' => []]);

        $messages = Message::where('conversation_id', $conv->id)
            ->where('is_deleted', false)
            ->where('message', 'like', '%' . $q . '%')
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        return response()->json([
            'messages' => $messages->map(fn($m) => $this->formatMessage($m, $me)),
        ]);
    }

    // ===== ALL MEDIA IN A CONVERSATION (gallery) =====
    public function threadMedia($userId)
    {
        $me = Auth::id();
        $userId = (int) $userId;

        $conv = Conversation::whereRaw("
            (user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)
        ", [$me, $userId, $userId, $me])->first();

        if (!$conv) return response()->json(['media' => []]);

        $messages = Message::where('conversation_id', $conv->id)
            ->where('is_deleted', false)
            ->whereNotNull('file_path')
            ->orderBy('created_at', 'desc')
            ->get();

        $allMedia = [];
        foreach ($messages as $m) {
            foreach ($this->formatMedia($m) as $f) {
                $f['message_id'] = $m->id;
                $f['created_at'] = $m->created_at->format('M d, Y');
                $allMedia[] = $f;
            }
        }

        return response()->json(['media' => $allMedia]);
    }

    // ===== FORWARD MESSAGE =====
    public function forwardMessage($id, Request $request)
    {
        $validated = $request->validate(['recipient_id' => 'required|integer|exists:users,id']);
        $me = Auth::id();
        $original = Message::findOrFail($id);

        if ($original->sender_id !== $me && $original->recipient_id !== $me) abort(403);
        if ($validated['recipient_id'] == $me) abort(403);

        $conv = Conversation::getOrCreate($me, $validated['recipient_id']);

        $newMsg = Message::create([
            'sender_id' => $me,
            'recipient_id' => $validated['recipient_id'],
            'message' => $original->message,
            'file_path' => $original->file_path,
            'share_meta' => $original->share_meta,
            'conversation_id' => $conv->id,
            'forwarded' => true,
            'delivered_at' => now(),
        ]);

        $conv->update(['last_message_at' => now(), 'deleted_by' => '[]']);
        return response()->json(['success' => true, 'message_id' => $newMsg->id]);
    }

    // ===== CONVERSATION LIST =====
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
            $deleted = json_decode($c->deleted_by ?? '[]', true) ?: [];
            if (in_array($me, $deleted)) return null;

            $other = $c->getOtherUser($me);
            if ($search && !str_contains(strtolower($other->name), strtolower($search))) return null;

            $lastMsg = $c->messages->first();
            $unread = Message::where('conversation_id', $c->id)
                ->where('recipient_id', $me)
                ->whereNull('read_at')
                ->count();

            $isOnline = $other->last_seen && $other->last_seen >= now()->subSeconds(40);
            $lastSeenText = $other->last_seen
                ? \App\Http\Controllers\PostController::formatLastSeen($other->last_seen)
                : 'Offline';

            $muted = json_decode($c->muted_by ?? '[]', true) ?: [];

            return [
                'user_id' => $other->id,
                'name' => $other->name,
                'avatar' => $other->profile_picture ? asset('storage/' . $other->profile_picture) : null,
                'hash' => method_exists($other, 'getHashidAttribute') ? $other->hashid : $other->id,
                'is_online' => $isOnline,
                'last_seen_text' => $lastSeenText,
                'last_message' => $lastMsg ? ($lastMsg->sender_id === $me ? 'You: ' : '') . substr($lastMsg->message ?: '[Media]', 0, 50) : '',
                'last_at' => $lastMsg ? $lastMsg->created_at->diffForHumans() : '',
                'unread' => $unread,
                'muted' => in_array($me, $muted),
            ];
        })->filter()->values();

        return response()->json(['conversations' => $list]);
    }

    // ===== EDIT MESSAGE =====
    public function editMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string|max:5000']);

        $message = Message::where('id', $id)->where('sender_id', Auth::id())->firstOrFail();

        if ($message->is_deleted) {
            return response()->json(['success' => false, 'error' => 'Cannot edit a deleted message.'], 403);
        }

        // Facebook-style: বার্তা পাঠানোর ১৫ মিনিটের মধ্যেই শুধু এডিট করা যাবে
        if ($message->created_at->diffInMinutes(now()) > 15) {
            return response()->json(['success' => false, 'error' => 'Edit time expired (15 minutes limit).'], 403);
        }

        $message->update([
            'message' => $request->message,
            'is_edited' => true,
        ]);

        return response()->json(['success' => true]);
    }

    // ===== DELETE (me | everyone) =====
    public function deleteMessage($id, Request $request)
    {
        $msg = Message::findOrFail($id);
        $me = Auth::id();
        $action = $request->get('action', 'me');

        if ($action === 'everyone') {
            if ($msg->sender_id !== $me) abort(403);
            $msg->update(['message' => '', 'file_path' => null, 'is_deleted' => true]);
        } else {
            if ($msg->sender_id !== $me && $msg->recipient_id !== $me) abort(403);
            $df = json_decode($msg->deleted_for ?? '[]', true) ?: [];
            if (!in_array($me, $df)) $df[] = $me;
            $msg->update(['deleted_for' => json_encode($df)]);
        }
        return response()->json(['success' => true]);
    }

    // ===== UNREAD COUNT =====
    public function unreadCount()
    {
        $count = Message::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->distinct('conversation_id')
            ->count('conversation_id');
        return response()->json(['count' => $count]);
    }

    // ===== REACT =====
    public function reactMessage($id, Request $request)
    {
        $msg = Message::findOrFail($id);
        $emoji = $request->validate(['emoji' => 'required|string|max:10'])['emoji'];

        $reactions = json_decode($msg->message_reactions ?? '{}', true);
        $me = Auth::id();

        foreach ($reactions as $e => $ids) {
            $reactions[$e] = array_values(array_filter($ids, fn($id) => $id != $me));
            if (empty($reactions[$e])) unset($reactions[$e]);
        }

        if (isset($reactions[$emoji]) && in_array($me, json_decode($msg->message_reactions ?? '{}', true)[$emoji] ?? [])) {
            // already removed above (toggle off)
        } else {
            $reactions[$emoji][] = $me;
        }

        $msg->update(['message_reactions' => json_encode($reactions)]);
        return response()->json(['success' => true, 'reactions' => $reactions]);
    }

    public function shareToMessenger(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|integer|exists:users,id',
            'type' => 'required|in:post,job',
            'item_id' => 'required',
            'comment' => 'nullable|string|max:1000',
        ]);

        $me = Auth::id();
        if ($validated['recipient_id'] == $me) abort(403);

        $meta = null;

        if ($validated['type'] === 'post') {
            $post = \App\Models\Post::find($validated['item_id']);
            if (!$post) return response()->json(['success' => false, 'message' => 'Post not found'], 404);
            $meta = $this->buildPostMeta($post);
        } else {
            $job = \App\Models\JobPost::find($validated['item_id']);
            if (!$job) return response()->json(['success' => false, 'message' => 'Job not found'], 404);
            $meta = $this->buildJobMeta($job);
        }

        $conv = Conversation::getOrCreate($me, $validated['recipient_id']);
        $msg = Message::create([
            'sender_id'    => $me,
            'recipient_id' => $validated['recipient_id'],
            'message'      => $validated['comment'] ?? '',
            'conversation_id' => $conv->id,
            'share_meta'   => json_encode($meta),
            'delivered_at' => now(),
        ]);
        $conv->update(['last_message_at' => now(), 'deleted_by' => '[]']);

        return response()->json(['success' => true, 'message_id' => $msg->id]);
    }

    private function buildPostMeta($post)
    {
        $author = $post->user;
        $firstImage = null;
        if ($post->images) {
            $imgs = is_array($post->images) ? $post->images : json_decode($post->images, true);
            if (is_array($imgs) && count($imgs)) $firstImage = asset('storage/' . $imgs[0]);
        }
        return [
            'type'     => 'post',
            'id'       => $post->id,
            'title'    => ($author->name ?? 'User') . "'s post",
            'subtitle' => \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 80) ?: 'Shared a post',
            'image'    => $firstImage,
            'url'      => url('/?goto_post=' . $post->id),
        ];
    }

    private function buildJobMeta($job)
    {
        return [
            'type'     => 'job',
            'id'       => $job->id,
            'title'    => $job->title,
            'subtitle' => $job->company . ($job->location ? ' · ' . $job->location : ''),
            'image'    => null,
            'url'      => url('/jobs/' . $job->id),
        ];
    }

    // পেস্ট করা মেসেজ টেক্সটের ভেতর নিজের সাইটের post/job লিংক থাকলে auto-detect করে preview meta বানায়
    private function extractShareMeta($text)
    {
        if (!$text) return null;

        if (preg_match('/#postCard-(\d+)/', $text, $m) || preg_match('/goto_post=(\d+)/', $text, $m)) {
            $post = \App\Models\Post::find($m[1]);
            if ($post) return $this->buildPostMeta($post);
        }

        if (preg_match('~/jobs/(\d+)~', $text, $m)) {
            $job = \App\Models\JobPost::find($m[1]);
            if ($job) return $this->buildJobMeta($job);
        }

        return null;
    }

    private function getConv($me, $userId)
    {
        $conv = Conversation::whereRaw("
            (user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)
        ", [$me, $userId, $userId, $me])->first();
        if (!$conv) $conv = Conversation::getOrCreate($me, $userId);
        return $conv;
    }

    public function toggleMute($userId)
    {
        $me = Auth::id();
        $conv = $this->getConv($me, (int) $userId);
        $muted = json_decode($conv->muted_by ?? '[]', true) ?: [];

        if (in_array($me, $muted)) {
            $muted = array_values(array_filter($muted, fn($id) => $id != $me));
            $isMuted = false;
        } else {
            $muted[] = $me;
            $isMuted = true;
        }
        $conv->update(['muted_by' => json_encode($muted)]);
        return response()->json(['success' => true, 'muted' => $isMuted]);
    }
  
    // ===== DELETE (me | everyone) ===== এর ঠিক পরে, deleteChatForMe() replace করো:

public function deleteChatForMe($userId)
{
    $me = Auth::id();
    $conv = $this->getConv($me, (int) $userId);

    // ১. কনভারসেশন লিস্ট থেকে হাইড করো (শুধু নিজের সাইডে)
    $deleted = json_decode($conv->deleted_by ?? '[]', true) ?: [];
    if (!in_array($me, $deleted)) $deleted[] = $me;
    $conv->update(['deleted_by' => json_encode($deleted)]);

    // ২. এই কনভারসেশনের সব existing মেসেজ নিজের ভিউ থেকে delete করো
    // (Facebook স্টাইল — শুধু নিজের কাছ থেকে যাবে, অন্যজনের কাছে থেকে যাবে)
    Message::where('conversation_id', $conv->id)
        ->chunkById(200, function ($chunk) use ($me) {
            foreach ($chunk as $m) {
                $df = json_decode($m->deleted_for ?? '[]', true) ?: [];
                if (!in_array($me, $df)) {
                    $df[] = $me;
                    $m->update(['deleted_for' => json_encode($df)]);
                }
            }
        });

    return response()->json(['success' => true]);
}

    public function exportChatHtml($userId)
    {
        $me = Auth::id();
        $other = User::findOrFail((int) $userId);
        $conv = $this->getConv($me, (int) $userId);

        $messages = Message::where('conversation_id', $conv->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Chat with ' . e($other->name) . '</title>'
            . '<style>body{font-family:sans-serif;background:#f0f2f5;padding:20px;}'
            . '.msg{max-width:70%;padding:8px 14px;border-radius:14px;margin-bottom:8px;word-wrap:break-word;}'
            . '.mine{background:#4f46e5;color:#fff;margin-left:auto;}'
            . '.theirs{background:#e5e7eb;color:#1e1f24;}'
            . '.time{font-size:11px;opacity:.7;margin-top:3px;}'
            . '.wrap{display:flex;flex-direction:column;}</style></head><body>'
            . '<h3>Chat between ' . e(Auth::user()->name) . ' and ' . e($other->name) . '</h3><div class="wrap">';

        foreach ($messages as $m) {
            $isMine = $m->sender_id === $me;
            $text = $m->is_deleted ? '[Deleted message]' : e($m->message ?: '[Media]');
            $html .= '<div class="msg ' . ($isMine ? 'mine' : 'theirs') . '">' . nl2br($text)
                . '<div class="time">' . $m->created_at->format('M d, Y g:i A') . '</div></div>';
        }
        $html .= '</div></body></html>';

        $filename = 'chat_with_' . \Illuminate\Support\Str::slug($other->name) . '_' . date('Ymd') . '.html';
        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}