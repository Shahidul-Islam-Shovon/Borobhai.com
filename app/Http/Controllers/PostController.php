<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * ১. নিউজফিড ও ড্যাশবোর্ড ডাটা লোড করা
     */
    public function index()
    {
        // parentPost এবং তার ইউজার রিলেশনসহ লেটেস্ট পোস্টগুলো লোড করা
        $posts = Post::with(['user', 'parentPost.user'])->latest()->get();
        $user = Auth::user();

        if ($user->role === 'alumni') {
            return view('alumni.dashboard', compact('posts'));
        }
        return view('student.dashboard', compact('posts'));
    }

    /**
     * ২. রেগুলার নতুন পোস্ট তৈরি করা (AJAX)
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required_without:image|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('posts', 'public') : null;

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content ?? '',
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Published successfully!',
            'post' => $post
        ]);
    }

    /**
     * 🎯 ৩. ফেসবুক স্টাইল ইন্টারনাল শেয়ার লজিক (শুধুমাত্র এই একটিই থাকবে)
     */
    public function share(Request $request, $id)
    {
        // ভ্যালিডেশন চেক
        $request->validate([
            'content' => 'nullable|string|max:5000'
        ]);

        try {
            // ডাটাবেজে শেয়ার করা পোস্টটি তৈরি করা (parent_id অ্যাসাইন করে)
            $sharedPost = Post::create([
                'user_id'   => Auth::id(),
                'parent_id' => $id, // মূল পোস্টের আইডি
                'content'   => $request->input('content') ?? '',
            ]);

            // রিলেশন লোড করা ফ্রন্টএন্ডে পাঠানোর জন্য
            $sharedPost->load(['user', 'parentPost.user']);

            return response()->json([
                'success' => true,
                'message' => 'Post shared successfully!',
                'post'    => $sharedPost
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backend Error: ' . $e->getMessage()
            ], 500);
        }
    }
public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);

    // নিরাপত্তা চেক: শুধুমাত্র পোস্টের নিজস্ব মালিকই এটি পরিবর্তন করতে পারবে
    if ($post->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
    }

    // ভ্যালিডেশন (কন্টেন্ট রিকোয়ার্ড না, কারণ শুধু ছবিও থাকতে পারে)
    $request->validate([
        'content' => 'nullable|string|max:5000',
        'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // সর্বোচ্চ 5MB
    ]);

    $postData = [
        'content' => $request->content
    ];

    // ১. ইউজার যদি ক্রস বাটনে চাপ দিয়ে ছবি রিমুভ করে দেয় (delete_image == "1")
    if ($request->delete_image == "1" && $post->image) {
        if (Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image); // স্টোরেজ থেকে পুরোনো ছবি ডিলিট
        }
        $postData['image'] = null; // ডাটাবেজে নাল সেট
    }

    // ২. ইউজার যদি নতুন কোনো ছবি আপলোড করে থাকে
    if ($request->hasFile('image')) {
        // নতুন ছবি রাখার আগে পুরোনো কোনো ছবি থাকলে তা স্টোরেজ থেকে ক্লিন করা
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }
        
        // নতুন ইমেজ ফাইল সেভ করা
        $path = $request->file('image')->store('posts', 'public');
        $postData['image'] = $path;
    }

    // ডাটাবেজ আপডেট সম্পাদন করা
    $post->update($postData);

    return response()->json(['success' => true, 'message' => 'Post updated successfully']);
}

public function destroy($id)
{
    $post = Post::findOrFail($id);

    // সিকিউরিটি লক: পোস্টের মালিক ছাড়া কেউ ডিলিট করতে পারবে না
    if ($post->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
    }

    $post->delete();

    return response()->json(['success' => true, 'message' => 'Post deleted successfully']);
}

}