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
        // ১. ভ্যালিডেশন (১০০ মেগা বাইট = ১০২৪০০ কিলোবাইট)
        $request->validate([
            'content' => 'nullable|string',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm,wmv|max:102400',
        ], [
            'media.*.max' => 'Each file maximum size cannot exceed 100MB.',
            'media.*.mimes' => 'Unsupported file format.'
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content ?? '';

        // 🎨 কালার ব্যাকগ্রাউন্ড লজিক (মিডিয়া না থাকলেই কেবল এটি কাজ করবে)
        if ($request->filled('bg_color') && !$request->hasFile('media')) {
            $post->bg_color = $request->bg_color;
        }

        // 📸 🎥 মাল্টিপল মিডিয়া (ইмеется এবং ভিডিও) প্রসেসিং লজিক
        if ($request->hasFile('media')) {
            $imagePaths = [];
            $videoPaths = [];

            foreach ($request->file('media') as $file) {
                $mimeType = $file->getMimeType();
                
                if (str_starts_with($mimeType, 'video/')) {
                    $videoPaths[] = $file->store('posts/videos', 'public');
                } else {
                    $imagePaths[] = $file->store('posts/images', 'public');
                }
            }

            // ইমেজ সেভ করা
            if (count($imagePaths) > 0) {
                $post->images = $imagePaths;
            }
            
            // ভিডিও সেভ করা (সিঙ্গেল ভিডিও হলে ডিরেক্ট পাথ, মাল্টিপল হলে JSON অ্যারে স্ট্রিং)
            if (count($videoPaths) > 0) {
                if (count($videoPaths) === 1) {
                    $post->video = $videoPaths[0];
                } else {
                    $post->video = json_encode($videoPaths);
                }
            }
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
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    // ১. ভ্যালিডেশন
    $request->validate([
        'content' => 'nullable|string',
        'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm,wmv|max:102400',
    ]);

    $post->content = $request->content ?? '';
    
    if ($request->has('bg_color')) {
        $post->bg_color = $request->bg_color;
    }

    // 🛠️ ২. এক্সিস্টিং ইমেজ রিমুভ করার লজিক (ফ্রন্টএন্ড থেকে বাদ দেওয়া ছবিগুলো)
    $currentImages = $post->images ?? [];
    if ($request->has('removed_images')) {
        $removedImages = json_decode($request->removed_images, true) ?? [];
        foreach ($removedImages as $img) {
            // স্টোরেজ থেকে ডিলিট করা
            if (Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
            // অ্যারে থেকে বাদ দেওয়া
            if (($key = array_search($img, $currentImages)) !== false) {
                unset($currentImages[$key]);
            }
        }
        $currentImages = array_values($currentImages); // ইনডেক্স রিসেট
    }

    // 🛠️ ৩. এক্সিস্টিং ভিডিও রিমুভ করার লজিক
    $currentVideos = [];
    if (!empty($post->video) && $post->video !== 'null') {
        $currentVideos = is_array($post->video) ? $post->video : (json_decode($post->video, true) ?: [$post->video]);
    }

    if ($request->has('removed_videos')) {
        $removedVideos = json_decode($request->removed_videos, true) ?? [];
        foreach ($removedVideos as $vid) {
            if (Storage::disk('public')->exists($vid)) {
                Storage::disk('public')->delete($vid);
            }
            if (($key = array_search($vid, $currentVideos)) !== false) {
                unset($currentVideos[$key]);
            }
        }
        $currentVideos = array_values($currentVideos);
    }

    // 🛠️ ৪. নতুন কোনো মিডিয়া (ছবি/ভিডিও) আপলোড দিলে তা যোগ করা
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            $mimeType = $file->getMimeType();
            if (str_starts_with($mimeType, 'video/')) {
                $currentVideos[] = $file->store('posts/videos', 'public');
            } else {
                $currentImages[] = $file->store('posts/images', 'public');
            }
        }
    }

    // ডাটাবেজে অ্যাসাইন করা
    $post->images = !empty($currentImages) ? $currentImages : null;

    if (!empty($currentVideos)) {
        if (count($currentVideos) === 1) {
            $post->video = $currentVideos[0];
        } else {
            $post->video = json_encode($currentVideos);
        }
    } else {
        $post->video = null;
    }

    $post->save();

    return response()->json([
        'success' => true,
        'message' => 'Post updated successfully!',
        'post' => $post
    ]);
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