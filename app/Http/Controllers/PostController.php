<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'parentPost.user', 'likes', 'comments.user'])->latest()->get();
        $user = Auth::user();

        if ($user->role === 'alumni') {
            return view('alumni.dashboard', compact('posts'));
        }
        return view('student.dashboard', compact('posts'));
    }

    public function store(Request $request)
    {
        // ভ্যালিডেশন
        $request->validate([
        'content' => 'nullable|string',

        'images.*' => 'nullable|image|max:10240',

        'video' => 'nullable|mimes:mp4,mov,avi,webm|max:102400',
    ], [
        'images.*.max' => 'Each image maximum size is 10MB.',
        'video.max' => 'Video maximum size is 100MB.'
    ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content ?? '';

        // 🎨 কালার ব্যাকগ্রাউন্ড লজিক (ছবি/ভিডিও না থাকলেই কেবল এটি কাজ করবে)
        if ($request->filled('bg_color') && !$request->hasFile('images') && !$request->hasFile('video')) {
            $post->bg_color = $request->bg_color;
        }

        // 📸 মাল্টিপল ইমেজ লজিক (লুপ চালিয়ে সেভ করা)
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('posts/images', 'public');
            }
            $post->images = $imagePaths; // Model casting এর কারণে অটোমেটিক JSON হয়ে যাবে
        }

        // 🎥 ভিডিও লজিক
        if ($request->hasFile('video')) {
            $post->video = $request->file('video')->store('posts/videos', 'public');
        }

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Published successfully!',
            'post'    => $post
        ]);         
    }

    public function share(Request $request, $id)
    {
        $targetPost = Post::findOrFail($id);
        $actualParentId = $targetPost->parent_id ? $targetPost->parent_id : $targetPost->id;

        $post = new Post();
        $post->user_id   = Auth::id();
        $post->content   = $request->content;
        $post->parent_id = $actualParentId;
        $post->save();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        $post->content = $request->content;

        // মিডিয়া রিমুভ রিকোয়েস্ট (এডিট মডাল থেকে ডিলিট করলে)
        if ($request->delete_image == "1") {
            $this->deleteMediaFromStorage($post);
            $post->images = null;
            $post->video = null;
            $post->bg_color = null;
        }

        // নতুন ব্যাকগ্রাউন্ড কালার আসলে
        if ($request->filled('bg_color') && !$request->hasFile('images') && !$request->hasFile('video')) {
            $this->deleteMediaFromStorage($post);
            $post->images = null;
            $post->video = null;
            $post->bg_color = $request->bg_color;
        }

        // নতুন ছবি আসলে
        if ($request->hasFile('images')) {
            $this->deleteMediaFromStorage($post);
            $post->video = null;
            $post->bg_color = null;

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('posts/images', 'public');
            }
            $post->images = $imagePaths;
        }

        // নতুন ভিডিও আসলে
        if ($request->hasFile('video')) {
            $this->deleteMediaFromStorage($post);
            $post->images = null;
            $post->bg_color = null;
            $post->video = $request->file('video')->store('posts/videos', 'public');
        }

        $post->save();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $this->deleteMediaFromStorage($post);
        $post->delete();

        return response()->json(['success' => true]);
    }

    /**
     * 🛠️ হেল্পার ফাংশন: স্টোরেজ থেকে মিডিয়া ডিলিট করা
     */
    private function deleteMediaFromStorage($post)
    {
        if ($post->video && Storage::disk('public')->exists($post->video)) {
            Storage::disk('public')->delete($post->video);
        }

        if ($post->images && is_array($post->images)) {
            foreach ($post->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }
    }
}