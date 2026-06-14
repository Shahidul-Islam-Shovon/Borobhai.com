<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedPostController extends Controller
{
    // Save / Unsave টগল
    public function toggle(Request $request, Post $post)
    {
        $user = Auth::user();

        $alreadySaved = $user->savedPosts()->where('post_id', $post->id)->exists();

        if ($alreadySaved) {
            $user->savedPosts()->detach($post->id);
            return response()->json([
                'success' => true,
                'saved'   => false,
                'message' => 'Removed from saved',
            ]);
        } else {
            $user->savedPosts()->attach($post->id);
            return response()->json([
                'success' => true,
                'saved'   => true,
                'message' => 'Saved successfully',
            ]);
        }
    }

    // Saved পেজ — saved posts + saved jobs দুটোই
    public function index()
    {
        $user = Auth::user();

        $savedPosts = $user->savedPosts()
            ->with(['user', 'parentPost.user', 'likes', 'comments.user'])
            ->withCount('comments')
            ->paginate(10);

        // সেভ করা job গুলো (latest আগে)
        $savedJobs = $user->savedJobs()
            ->with('user')
            ->orderByPivot('created_at', 'desc')
            ->get();

        return view('saved.index', compact('savedPosts', 'savedJobs'));
    }
}