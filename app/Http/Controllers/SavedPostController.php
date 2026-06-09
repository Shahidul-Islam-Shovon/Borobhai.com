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

        // ইতিমধ্যে সেভ করা আছে কিনা চেক
        $alreadySaved = $user->savedPosts()->where('post_id', $post->id)->exists();

        if ($alreadySaved) {
            // আনসেভ
            $user->savedPosts()->detach($post->id);
            return response()->json([
                'success' => true,
                'saved'   => false,
                'message' => 'Removed from saved',
            ]);
        } else {
            // সেভ
            $user->savedPosts()->attach($post->id);
            return response()->json([
                'success' => true,
                'saved'   => true,
                'message' => 'Saved successfully',
            ]);
        }
    }

    // Saved পেজ
    public function index()
    {
        $savedPosts = Auth::user()->savedPosts()
            ->with(['user', 'parentPost.user', 'likes', 'comments.user'])
            ->withCount('comments')
            ->paginate(10);

        return view('saved.index', compact('savedPosts'));
    }
}