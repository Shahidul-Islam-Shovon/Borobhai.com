<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|string|max:1000']);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'comment_id' => $comment->id,
            'user_name' => auth()->user()->name,
            'content' => e($comment->content),
            'comment_count' => $post->comments()->count()
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['content' => 'required|string|max:1000']);
        $comment->update(['content' => $request->content]);

        return response()->json(['success' => true]);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $post = $comment->post;
        $comment->delete();

        return response()->json([
            'success' => true,
            'comment_count' => $post->comments()->count()
        ]);
    }
}