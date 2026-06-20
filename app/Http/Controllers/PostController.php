<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // প্রতি পেজে কতগুলো feed আইটেম (post + job মিলিয়ে)
    private const FEED_PER_PAGE = 6;

    public function index()
    {
        // মেয়াদোত্তীর্ণ (৫ দিন গ্রেস শেষ) job গুলো পরিষ্কার করো
        JobController::cleanupExpired();

        $user = Auth::user();

        // page 1 — প্রথম ব্যাচ
        $feed = $this->buildFeed(1);

        $appliedJobIds = \App\Models\JobApplication::where('user_id', $user->id)
            ->pluck('job_post_id')->toArray();

        // সাইডবারের জন্য Recent Jobs — শুধু ACTIVE (deadline শেষ হয়নি এমন)
        // Internship আগে, তারপর Part-time, তারপর বাকি; প্রতিটির ভেতরে নতুন আগে
        $recentJobs = JobPost::with('user')
                ->visible()
                ->where(function ($q) {
                    $q->whereNull('deadline')
                      ->orWhereDate('deadline', '>=', now()->toDateString());
                })
                ->orderByRaw("CASE
                        WHEN LOWER(job_type) LIKE '%intern%' THEN 1
                        WHEN LOWER(job_type) LIKE '%part%' THEN 2
                        ELSE 3 END")
                ->latest()
                ->take(5)
                ->get();

        $feedItems = $feed['items'];
        $hasMore   = $feed['has_more'];

        $data = compact('feedItems', 'hasMore', 'recentJobs', 'appliedJobIds');

        if ($user->role === 'alumni') {
            return view('alumni.dashboard', $data);
        }
        return view('student.dashboard', $data);
    }

    public function loadMore(Request $request)
    {
        $page = max(1, (int) $request->query('page', 2));

        $feed = $this->buildFeed($page);

        $appliedJobIds = \App\Models\JobApplication::where('user_id', Auth::id())
            ->pluck('job_post_id')->toArray();

        $html = '';
        foreach ($feed['items'] as $item) {
            if ($item['type'] === 'job') {
                $html .= view('partials.job-card', [
                    'job' => $item['model'],
                    'appliedJobIds' => $appliedJobIds,
                ])->render();
            } else {
                $html .= view('partials.post-card', [
                    'post' => $item['model'],
                ])->render();
            }
        }

        return response()->json([
            'html'      => $html,
            'has_more'  => $feed['has_more'],
            'next_page' => $page + 1,
        ]);
    }

    /**
     * Unified time-sorted feed (offset/page based)।
     * post + job একসাথে created_at দিয়ে sort করে নির্দিষ্ট পেজের slice ফেরত দেয়।
     * নির্ভরযোগ্য — duplicate timestamp বা edge case এ আটকায় না।
     */
    private function buildFeed(int $page): array
    {
        $perPage = self::FEED_PER_PAGE;
        $offset  = ($page - 1) * $perPage;

        // এই পেজ পর্যন্ত যত লাগবে তত (+1) করে আনি — পুরো টেবিল নয় (পারফরম্যান্স)
        $need = $offset + $perPage + 1;

        // ---- Posts ----
        $posts = Post::with([
                    'user',
                    'parentPost.user',
                    'likes',
                    'comments' => function ($q) {
                        $q->with('user')->latest()->limit(10);
                    }
                ])
                ->withCount('comments')
                ->latest('created_at')
                ->take($need)
                ->get();

        // ---- Jobs (feed-visible only) ----
        $jobs = JobPost::with('user')
                ->withCount(['savedByUsers as is_saved_by_me' => function ($q) {
                    $q->where('user_id', Auth::id());
                }])
                ->visible()
                ->latest('created_at')
                ->take($need)
                ->get();

        // ---- Merge ----
        $items = collect();

        foreach ($posts as $p) {
            $items->push([
                'type'       => 'post',
                'model'      => $p,
                'created_at' => $p->created_at,
            ]);
        }
        foreach ($jobs as $j) {
            $items->push([
                'type'       => 'job',
                'model'      => $j,
                'created_at' => $j->created_at,
            ]);
        }

        // newest first; একই সময় হলে job আগে
        $sorted = $items->sort(function ($a, $b) {
            $cmp = $b['created_at']->timestamp <=> $a['created_at']->timestamp;
            if ($cmp !== 0) return $cmp;
            return ($a['type'] === 'job' ? 0 : 1) <=> ($b['type'] === 'job' ? 0 : 1);
        })->values();

        // এই পেজের slice
        $pageItems = $sorted->slice($offset, $perPage)->values();

        // আরো আছে কিনা
        $hasMore = $sorted->count() > ($offset + $perPage);

        return [
            'items'    => $pageItems->all(),
            'has_more' => $hasMore,
        ];
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