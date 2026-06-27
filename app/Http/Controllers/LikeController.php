<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\BbNotification;
use Illuminate\Support\Facades\Auth;


class LikeController extends Controller
{
    public function toggle(Post $post)
{
    $like = $post->likes()->where('user_id', auth()->id())->first();

    if ($like) {
        $like->delete();
        $liked = false;
    } else {
        $post->likes()->create([
            'user_id' => auth()->id()
        ]);

        $liked = true;

        // Like notification
        BbNotification::send(
            $post->user_id,
            Auth::id(),
            'post_like',
            Auth::user()->name . ' liked your post',
            'post',
            $post->id
        );
    }

    return response()->json([
        'success' => true,
        'liked' => $liked,
        'like_count' => $post->likes()->count()
    ]);
}
}