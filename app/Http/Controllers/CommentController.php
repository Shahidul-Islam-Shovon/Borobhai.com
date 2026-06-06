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
            'comment_count' => $post->comments()->count(),
            'created_at'    => $comment->created_at->diffForHumans(),  // নতুন
            'user_initial'  => strtoupper(substr($comment->user->name, 0, 1)),  // নতুন

        ]);
    }

    public function loadMore(Request $request, $postId)
    {
        $offset = (int) $request->query('offset', 0);

        // সর্বশেষ থেকে পুরনোর দিকে, ১০টা করে
        $comments = \App\Models\Comment::where('post_id', $postId)
                        ->with('user')
                        ->latest()
                        ->skip($offset)
                        ->take(10)
                        ->get();

        $totalComments = \App\Models\Comment::where('post_id', $postId)->count();
        $loadedSoFar   = $offset + $comments->count();

        $html = '';
        foreach ($comments as $comment) {
            $html .= view('partials.comment-item', compact('comment'))->render();
        }

        return response()->json([
            'success'    => true,
            'html'       => $html,
            'has_more'   => $loadedSoFar < $totalComments,
            'next_offset'=> $loadedSoFar,
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['content' => 'required|string|max:1000']);
        $comment->update(['content' => $request->content]);

        return response()->json([
            'success' => true,
            'content' => e($comment->content),         // নতুন — নিরাপদ edit display
            'updated_at' => $comment->updated_at->diffForHumans(),  // নতুন           
        ]);
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