<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        // মেয়াদোত্তীর্ণ (৫ দিন গ্রেস শেষ) job গুলো পরিষ্কার করো
        JobController::cleanupExpired();

        $posts = Post::with([
                    'user',
                    'parentPost.user',
                    'likes',
                    'comments' => function ($q) {
                        $q->with('user')->latest()->limit(10);
                    }
                ])
                ->withCount('comments')
                ->latest()
                ->paginate(5);

        $user = Auth::user();

        // ফিডে দেখানোর জন্য দৃশ্যমান job (সীমিত — পারফরম্যান্স)
        $feedJobs = JobPost::with('user')
                ->withCount(['savedByUsers as is_saved_by_me' => function ($q) {
                    $q->where('user_id', Auth::id());
                }])
                ->visible()
                ->latest()
                ->take(15)
                ->get();

        // সাইডবারের জন্য Recent Jobs (Internship/Part-time আগে, তারপর নতুন)
        $recentJobs = JobPost::with('user')
                ->visible()
                ->orderByRaw("CASE
                        WHEN LOWER(job_type) LIKE '%intern%' THEN 1
                        WHEN LOWER(job_type) LIKE '%part%' THEN 2
                        ELSE 3 END")
                ->latest()
                ->take(5)
                ->get();

        if ($user->role === 'alumni') {
            return view('alumni.dashboard', compact('posts', 'recentJobs', 'feedJobs'));
        }
        return view('student.dashboard', compact('posts', 'recentJobs', 'feedJobs'));
    }

    public function loadMore(Request $request)
    {
        $posts = Post::with([
                    'user',
                    'parentPost.user',
                    'likes',
                    'comments' => function ($q) {
                        $q->with('user')->latest()->limit(10);
                    }
                ])
                ->withCount('comments')
                ->latest()
                ->paginate(5);

        $html = '';
        foreach ($posts as $post) {
            $html .= view('partials.post-card', compact('post'))->render();
        }

        return response()->json([
            'html'      => $html,
            'has_more'  => $posts->hasMorePages(),
            'next_page' => $posts->currentPage() + 1,
        ]);
    }

    public function store(Request $request)
    {
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

        if ($request->filled('bg_color') && !$request->hasFile('media')) {
            $post->bg_color = $request->bg_color;
        }

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

            if (count($imagePaths) > 0) {
                $post->images = $imagePaths;
            }

            if (count($videoPaths) > 0) {
                if (count($videoPaths) === 1) {
                    $post->video = $videoPaths[0];
                } else {
                    $post->video = json_encode($videoPaths);
                }
            }
        }

        $post->save();

        $post->load(['user', 'parentPost.user', 'likes', 'comments.user']);
        $post->loadCount('comments');

        $html = view('partials.post-card', ['post' => $post])->render();

        return response()->json([
            'success' => true,
            'message' => 'Published successfully!',
            'post'    => $post,
            'html'    => $html,
        ]);
    }

    public function share(Request $request, $id)
    {
        $targetPost = Post::findOrFail($id);
        $actualParentId = $targetPost->parent_id ?: $targetPost->id;

        $post = new Post();
        $post->user_id   = Auth::id();
        $post->content   = $request->content ?? '';
        $post->parent_id = $actualParentId;
        $post->save();

        $post->load(['user', 'parentPost.user', 'likes', 'comments.user']);
        $post->loadCount('comments');
        $html = view('partials.post-card', ['post' => $post])->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'nullable|string',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm,wmv|max:102400',
        ]);

        $post->content = $request->content ?? '';

        if ($request->has('bg_color')) {
            $post->bg_color = $request->bg_color;
        }

        $currentImages = $post->images ?? [];
        if ($request->has('removed_images')) {
            $removedImages = json_decode($request->removed_images, true) ?? [];
            foreach ($removedImages as $img) {
                if (Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
                if (($key = array_search($img, $currentImages)) !== false) {
                    unset($currentImages[$key]);
                }
            }
            $currentImages = array_values($currentImages);
        }

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

        $post->load(['user', 'parentPost.user', 'likes', 'comments.user']);

        $html = view('partials.post-card', ['post' => $post])->render();

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully!',
            'html'    => $html,
            'post'    => $post
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