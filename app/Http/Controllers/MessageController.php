<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MessageController extends Controller
{
    /**
     * Send Message
     */
    public function send(Request $request)
{
    $request->validate([
        'receiver_id'  => ['required', 'exists:users,id'],
        'message'      => ['nullable', 'string'],
        'attachments.*'=> ['nullable', 'file', 'max:51200'],
    ]);

    if (
        trim((string)$request->message) === '' &&
        !$request->hasFile('attachments')
    ) {
        return response()->json([
            'success' => false,
            'message' => 'Nothing to send.'
        ], 422);
    }

    $messages = [];

    // Text
    if (trim((string)$request->message) !== '') {

        $msg = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => Crypt::encryptString($request->message),
        ]);

        $messages[] = [
            'id' => $msg->id,
            'type' => 'text',
            'message' => $request->message,
            'time' => $msg->created_at->format('h:i A'),
            'mine' => true,
        ];
    }

    // Files
    if ($request->hasFile('attachments')) {

        foreach ($request->file('attachments') as $file) {

            $type = 'file';

            if (str_starts_with($file->getMimeType(), 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($file->getMimeType(), 'video/')) {
                $type = 'video';
            } elseif (str_starts_with($file->getMimeType(), 'audio/')) {
                $type = 'voice';
            }

            $path = $file->store('chat', 'public');

            $msg = Message::create([
                'sender_id'       => Auth::id(),
                'receiver_id'     => $request->receiver_id,
                'attachment'      => $path,
                'attachment_type' => $type,
                'attachment_name' => $file->getClientOriginalName(),
                'attachment_size' => $file->getSize(),
            ]);

            $messages[] = [
                'id' => $msg->id,
                'type' => $type,
                'attachment' => asset('storage/'.$path),
                'attachment_name' => $file->getClientOriginalName(),
                'attachment_size' => $file->getSize(),
                'time' => $msg->created_at->format('h:i A'),
                'mine' => true,
            ];
        }
    }

    return response()->json([
        'success' => true,
        'messages' => $messages
    ]);
}

    /**
     * Load Conversation
     */
    public function load($userId)
{
    $messages = Message::where(function ($q) use ($userId) {

        $q->where('sender_id', Auth::id())
          ->where('receiver_id', $userId)
          ->where('deleted_by_sender', false);

    })->orWhere(function ($q) use ($userId) {

        $q->where('sender_id', $userId)
          ->where('receiver_id', Auth::id())
          ->where('deleted_by_receiver', false);

    })
    ->orderBy('id')
    ->get();

    foreach ($messages as $m) {

        $m->mine = $m->sender_id == Auth::id();
        $m->time = $m->created_at->format('h:i A');

        if ($m->is_unsent) {

            $m->type = 'unsent';
            $m->message = 'This message was unsent.';
            continue;
        }

        if ($m->attachment) {

            $m->attachment = asset('storage/'.$m->attachment);
            $m->type = $m->attachment_type ?: 'file';

        } else {

            $m->type = 'text';

            try {
                $m->message = Crypt::decryptString($m->message);
            } catch (\Exception $e) {
                $m->message = '';
            }
        }
    }

    Message::where('sender_id', $userId)
        ->where('receiver_id', Auth::id())
        ->where('is_seen', false)
        ->update([
            'is_seen' => true,
            'seen_at' => now(),
        ]);

    return response()->json([
        'success' => true,
        'messages' => $messages,
    ]);
}

    /**
     * Mark Seen
     */
    public function markSeen($userId)
    {
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_seen', false)
            ->update([
                'is_seen' => true,
                'seen_at' => now(),
            ]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Unsend
     */
    public function unsend(Message $message)
    {
        abort_if($message->sender_id != Auth::id(), 403);

        $message->update([
            'is_unsent' => true,
            'unsent_at' => now(),
            'message' => null,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Delete For Me
     */
    public function deleteForMe(Message $message)
    {
        if ($message->sender_id == Auth::id()) {

            $message->update([
                'deleted_by_sender' => true
            ]);

        } elseif ($message->receiver_id == Auth::id()) {

            $message->update([
                'deleted_by_receiver' => true
            ]);

        }

        return response()->json([
            'success' => true
        ]);
    }
}