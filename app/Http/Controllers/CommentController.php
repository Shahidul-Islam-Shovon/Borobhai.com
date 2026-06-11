<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // ==========================================
    // নতুন কমেন্ট বা reply যোগ
    // ==========================================
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content'   => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);

        $comment = $post->comments()->create([
            'user_id'   => auth()->id(),
            'content'   => $request->content,
            'parent_id' => $request->parent_id ?: null,
        ]);

        // মোট কমেন্ট = মূল কমেন্ট + reply (সব)
        $totalCount = $post->comments()->count();

        return response()->json([
            'success'       => true,
            'comment_id'    => $comment->id,
            'parent_id'     => $comment->parent_id,
            'user_name'     => auth()->user()->name,
            'content'       => e($comment->content),
            'comment_count' => $totalCount,
            'created_at'    => $comment->created_at->diffForHumans(),
            'user_initial'  => strtoupper(substr($comment->user->name, 0, 1)),
            'user_picture'  => auth()->user()->profile_picture ? asset('storage/'.auth()->user()->profile_picture) : null,
        ]);
    }

    // ==========================================
    // কমেন্ট Like টগল
    // ==========================================
    public function toggleLike(Comment $comment)
    {
        $userId = auth()->id();
        $existing = CommentLike::where('comment_id', $comment->id)->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            CommentLike::create(['comment_id' => $comment->id, 'user_id' => $userId]);
            $liked = true;
        }

        return response()->json([
            'success'    => true,
            'liked'      => $liked,
            'like_count' => $comment->likes()->count(),
        ]);
    }

    // ==========================================
    // কমেন্ট লোড (মূল কমেন্ট + তাদের reply)
    // ==========================================
    public function loadMore(Request $request, $postId)
    {
        $offset = (int) $request->query('offset', 0);

        // শুধু মূল কমেন্ট (parent_id null), সর্বশেষ আগে
        $comments = Comment::where('post_id', $postId)
                        ->whereNull('parent_id')
                        ->with(['user', 'likes', 'replies.user', 'replies.likes'])
                        ->latest()
                        ->skip($offset)
                        ->take(10)
                        ->get();

        // মোট মূল কমেন্ট (offset হিসাবের জন্য)
        $totalParents = Comment::where('post_id', $postId)->whereNull('parent_id')->count();
        $loadedSoFar  = $offset + $comments->count();

        $html = '';
        foreach ($comments as $comment) {
            $html .= view('partials.comment-item', compact('comment'))->render();
        }

        return response()->json([
            'success'    => true,
            'html'       => $html,
            'has_more'   => $loadedSoFar < $totalParents,
            'next_offset'=> $loadedSoFar,
        ]);
    }

    // ==========================================
    // কমেন্ট এডিট
    // ==========================================
    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['content' => 'required|string|max:1000']);
        $comment->update(['content' => $request->content]);

        return response()->json([
            'success'    => true,
            'content'    => e($comment->content),
            'updated_at' => $comment->updated_at->diffForHumans(),
        ]);
    }

    // ==========================================
    // কমেন্ট ডিলিট (reply সহ cascade)
    // ==========================================
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $post = $comment->post;
        $comment->delete();  // reply গুলো cascade এ মুছবে

        return response()->json([
            'success'       => true,
            'comment_count' => $post->comments()->count(),
        ]);
    }
}