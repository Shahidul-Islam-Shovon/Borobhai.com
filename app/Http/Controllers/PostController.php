<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\JobPost;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\BbNotification;

class PostController extends Controller
{
    private const FEED_PER_PAGE = 6;

    public function index()
    {
        JobController::cleanupExpired();

        $user = Auth::user();
        $meId = Auth::id();

        // last_seen update
        DB::table('users')->where('id', $meId)->update(['last_seen' => now()]);
        Cache::forget('last_seen_' . $meId);

        $friendIds  = Friendship::friendIds($meId);
        $blockedIds = $this->getBlockedIds($meId);

        $feed = $this->buildFeed(1, $meId, $friendIds, 'all');

        $appliedJobIds = \App\Models\JobApplication::where('user_id', $meId)
            ->pluck('job_post_id')->toArray();

        $recentJobs = JobPost::with('user')
            ->visible()
            ->where(function ($q) {
                $q->whereNull('deadline')
                  ->orWhereDate('deadline', '>=', now()->toDateString());
            })
            ->orderByRaw("CASE
                WHEN LOWER(job_type) LIKE '%intern%' THEN 1
                WHEN LOWER(job_type) LIKE '%part%'   THEN 2
                ELSE 3 END")
            ->latest()
            ->take(5)
            ->get();

        $feedItems   = $feed['items'];
        $hasMore     = $feed['has_more'];
        $canPostJobs = $user->role === 'alumni';

        // Active Now — 3 ঘন্টার মধ্যে active friends
        $activeUsers = \App\Models\User::whereIn('id', $friendIds)
            ->whereNotIn('id', $blockedIds)
            ->where('last_seen', '>=', now()->subHours(3))
            ->select('id', 'name', 'role', 'profile_picture', 'last_seen')
            ->orderByDesc('last_seen')
            ->limit(8)
            ->get();

        // Suggested Contact
        $suggested = $this->getSuggested($meId, $friendIds, $blockedIds);

        // Pending friend requests (left sidebar)
        $pendingRequests = Friendship::where('receiver_id', $meId)
            ->where('status', 'pending')
            ->with('sender:id,name,role,profile_picture')
            ->latest()
            ->limit(10)
            ->get();

        $data = compact(
            'feedItems', 'hasMore', 'recentJobs', 'appliedJobIds',
            'canPostJobs', 'activeUsers', 'suggested', 'pendingRequests'
        );

        if (in_array($user->role, ['alumni', 'teacher'])) {
            return view('alumni.dashboard', $data);
        }
        return view('student.dashboard', $data);
    }

    public function loadMore(Request $request)
    {
        $page      = max(1, (int) $request->query('page', 2));
        $filter    = $request->query('filter', 'all');
        $meId      = Auth::id();
        $friendIds = Friendship::friendIds($meId);
        $feed      = $this->buildFeed($page, $meId, $friendIds, $filter);

        $appliedJobIds = \App\Models\JobApplication::where('user_id', $meId)
            ->pluck('job_post_id')->toArray();

        $html = '';
        foreach ($feed['items'] as $item) {
            if ($item['type'] === 'job') {
                $html .= view('partials.job-card', [
                    'job'           => $item['model'],
                    'appliedJobIds' => $appliedJobIds,
                ])->render();
            } else {
                $html .= view('partials.post-card', ['post' => $item['model']])->render();
            }
        }

        return response()->json([
            'html'      => $html,
            'has_more'  => $feed['has_more'],
            'next_page' => $page + 1,
        ]);
    }

    private function buildFeed(int $page, int $meId, array $friendIds, string $filter = 'all'): array
    {
        $perPage = self::FEED_PER_PAGE;
        $offset  = ($page - 1) * $perPage;
        $need    = $offset + $perPage + 1;

        $posts = Post::with([
                    'user', 'parentPost.user', 'likes',
                    'comments' => fn($q) => $q->with('user')->latest()->limit(10),
                ])
                ->withCount('comments')
                ->where(function ($q) use ($meId, $friendIds, $filter) {
                    if ($filter === 'friends') {
                        // শুধু friends এর post
                        $q->whereIn('user_id', $friendIds)
                          ->whereIn('privacy', ['public', 'friends']);
                    } elseif ($filter === 'public') {
                        // শুধু public post
                        $q->where('privacy', 'public');
                    } else {
                        // all — নিজের সব + friends এর public/friends + অন্যদের public
                        $q->where('user_id', $meId)
                          ->orWhere(function ($sub) use ($friendIds) {
                              $sub->whereIn('user_id', $friendIds)
                                  ->whereIn('privacy', ['public', 'friends']);
                          })
                          ->orWhere(function ($sub) use ($meId, $friendIds) {
                              $sub->whereNotIn('user_id', array_merge($friendIds, [$meId]))
                                  ->where('privacy', 'public');
                          });
                    }
                })
                ->latest('created_at')
                ->take($need)
                ->get();

        // Jobs — শুধু 'all' filter এ
        $jobs = collect();
        if ($filter === 'all') {
            $jobs = JobPost::with('user')
                    ->withCount(['savedByUsers as is_saved_by_me' => fn($q) => $q->where('user_id', Auth::id())])
                    ->visible()
                    ->latest('created_at')
                    ->take($need)
                    ->get();
        }

        $items = collect();
        foreach ($posts as $p) {
            $items->push(['type' => 'post', 'model' => $p, 'created_at' => $p->created_at]);
        }
        foreach ($jobs as $j) {
            $items->push(['type' => 'job', 'model' => $j, 'created_at' => $j->created_at]);
        }

        $sorted = $items->sort(function ($a, $b) {
            $cmp = $b['created_at']->timestamp <=> $a['created_at']->timestamp;
            if ($cmp !== 0) return $cmp;
            return ($a['type'] === 'job' ? 0 : 1) <=> ($b['type'] === 'job' ? 0 : 1);
        })->values();

        return [
            'items'    => $sorted->slice($offset, $perPage)->values()->all(),
            'has_more' => $sorted->count() > ($offset + $perPage),
        ];
    }

    private function getSuggested(int $meId, array $friendIds, array $blockedIds): \Illuminate\Support\Collection
    {
        $pendingSentIds = Friendship::where('sender_id', $meId)
            ->where('status', 'pending')
            ->pluck('receiver_id')
            ->toArray();

        $notInterestedIds = DB::table('not_interested_users')
            ->where('user_id', $meId)
            ->pluck('ignored_user_id')
            ->toArray();

        $hardExclude  = array_values(array_unique(
            array_merge($friendIds, $blockedIds, [$meId], $notInterestedIds)
        ));
        $finalExclude = array_values(array_diff($hardExclude, $pendingSentIds));

        return \App\Models\User::whereNotIn('id', $finalExclude)
            ->where('role', '!=', 'admin')
            ->orderBy('id')
            ->select('id', 'name', 'role', 'department', 'section', 'profile_picture')
            ->with([
                'experiences' => fn($q) => $q->where('is_current', true)
                    ->select('user_id', 'company', 'designation')
                    ->limit(1),
            ])
            ->limit(5)
            ->get()
            ->map(function ($u) use ($meId, $pendingSentIds) {
                $u->mutual          = Friendship::mutualCount($meId, $u->id);
                $u->is_pending      = in_array($u->id, $pendingSentIds);
                $exp                = $u->experiences->first();
                $u->current_company = $exp?->company;
                return $u;
            });
    }

    private function getBlockedIds(int $meId): array
    {
        return Friendship::where('status', 'blocked')
            ->where(fn($q) => $q->where('sender_id', $meId)->orWhere('receiver_id', $meId))
            ->get()
            ->flatMap(fn($f) => [$f->sender_id, $f->receiver_id])
            ->filter(fn($id) => $id != $meId)
            ->unique()->values()->toArray();
    }

    // ==========================================
    // STORE
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm,wmv|max:102400',
        ]);

        $post          = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content ?? '';
        $post->privacy = in_array($request->privacy, ['public', 'friends', 'only_me'])
            ? $request->privacy : 'public';

        if ($request->filled('bg_color') && !$request->hasFile('media')) {
            $post->bg_color = $request->bg_color;
        }

        if ($request->hasFile('media')) {
            $images = []; $videos = [];
            foreach ($request->file('media') as $file) {
                if (str_starts_with($file->getMimeType(), 'video/')) {
                    $videos[] = $file->store('posts/videos', 'public');
                } else {
                    $images[] = $file->store('posts/images', 'public');
                }
            }
            if ($images) $post->images = $images;
            if ($videos) $post->video  = count($videos) === 1 ? $videos[0] : json_encode($videos);
        }

        $post->save();
        $post->load(['user', 'parentPost.user', 'likes', 'comments.user']);
        $post->loadCount('comments');

        return response()->json([
            'success' => true,
            'message' => 'Published successfully!',
            'post'    => $post,
            'html'    => view('partials.post-card', ['post' => $post])->render(),
        ]);
    }

    // ==========================================
    // SHARE
    // ==========================================
    public function share(Request $request, $id)
    {
        $targetPost      = Post::findOrFail($id);
        $post            = new Post();
        $post->user_id   = Auth::id();
        $post->content   = $request->content ?? '';
        $post->parent_id = $targetPost->parent_id ?: $targetPost->id;
        $post->privacy   = in_array($request->privacy, ['public', 'friends', 'only_me'])
            ? $request->privacy : 'public';
        $post->save();

        if ($targetPost->user_id !== Auth::id()) {
            \App\Models\BbNotification::send(
                $targetPost->user_id,
                Auth::id(),
                'post_share',
                Auth::user()->name . ' shared your post.',
                'post',
                $targetPost->id
            );
        }

        $post->load(['user', 'parentPost.user', 'likes', 'comments.user']);
        $post->loadCount('comments');

        return response()->json([
            'success' => true,
            'html'    => view('partials.post-card', ['post' => $post])->render(),
        ]);
        
    }

    // ==========================================
    // UPDATE
    // ==========================================
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
        if ($request->has('bg_color')) $post->bg_color = $request->bg_color;
        if ($request->has('privacy') && in_array($request->privacy, ['public', 'friends', 'only_me'])) {
            $post->privacy = $request->privacy;
        }

        $currentImages = $post->images ?? [];
        if ($request->has('removed_images')) {
            foreach (json_decode($request->removed_images, true) ?? [] as $img) {
                Storage::disk('public')->exists($img) && Storage::disk('public')->delete($img);
                $currentImages = array_values(array_filter($currentImages, fn($i) => $i !== $img));
            }
        }

        $currentVideos = [];
        if (!empty($post->video) && $post->video !== 'null') {
            $currentVideos = is_array($post->video)
                ? $post->video
                : (json_decode($post->video, true) ?: [$post->video]);
        }
        if ($request->has('removed_videos')) {
            foreach (json_decode($request->removed_videos, true) ?? [] as $vid) {
                Storage::disk('public')->exists($vid) && Storage::disk('public')->delete($vid);
                $currentVideos = array_values(array_filter($currentVideos, fn($v) => $v !== $vid));
            }
        }

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                if (str_starts_with($file->getMimeType(), 'video/')) {
                    $currentVideos[] = $file->store('posts/videos', 'public');
                } else {
                    $currentImages[] = $file->store('posts/images', 'public');
                }
            }
        }

        $post->images = !empty($currentImages) ? $currentImages : null;
        $post->video  = !empty($currentVideos)
            ? (count($currentVideos) === 1 ? $currentVideos[0] : json_encode($currentVideos))
            : null;

        $post->save();
        $post->load(['user', 'parentPost.user', 'likes', 'comments.user']);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully!',
            'html'    => view('partials.post-card', ['post' => $post])->render(),
            'post'    => $post,
        ]);
    }

    // ==========================================
    // DESTROY
    // ==========================================
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }
        if ($post->video && Storage::disk('public')->exists($post->video)) {
            Storage::disk('public')->delete($post->video);
        }
        if ($post->images) {
            foreach ($post->images as $img) {
                Storage::disk('public')->exists($img) && Storage::disk('public')->delete($img);
            }
        }
        $post->delete();
        return response()->json(['success' => true]);
    }

    // ==========================================
    // ACTIVE NOW
    // ==========================================
    public function activeNow()
    {
        $meId       = Auth::id();
        $friendIds  = Friendship::friendIds($meId);
        $blockedIds = $this->getBlockedIds($meId);

        DB::table('users')->where('id', $meId)->update(['last_seen' => now()]);

        $activeUsers = \App\Models\User::whereIn('id', $friendIds)
            ->whereNotIn('id', $blockedIds)
            ->where('last_seen', '>=', now()->subMinutes(10))
            ->select('id', 'name', 'role', 'profile_picture', 'last_seen')
            ->orderByDesc('last_seen')
            ->limit(8)
            ->get();

        if ($activeUsers->isEmpty()) {
            return response()->json([
                'html' => '<div class="text-muted small px-2 py-3 text-center">No friends active right now.</div>'
            ]);
        }

        $html = '';
        foreach ($activeUsers as $au) {
            $lastSeenText = self::formatLastSeen($au->last_seen);
            $isOnline     = $au->last_seen >= now()->subMinutes(10);
            $pic          = $au->profile_picture
                ? '<img src="'.asset('storage/'.$au->profile_picture).'" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">'
                : strtoupper(substr($au->name, 0, 1));
            $dotColor   = $isOnline ? '#22c55e' : '#9ca3af';
            $roleClass  = 'bb-mini-' . $au->role;
            $badgeContent = $isOnline
                ? '<i class="bi bi-circle-fill text-success" style="font-size:7px;"></i> '.$lastSeenText
                : $lastSeenText;
            $nameJs    = addslashes($au->name);
            $picUrl    = $au->profile_picture ? asset('storage/'.$au->profile_picture) : '';
            $isOnlineJs = $isOnline ? '1' : '0';

            $html .= '
            <a href="#" class="bb-active-item" style="text-decoration:none;"
               onclick="event.preventDefault(); openChatBox('.$au->id.', \''.$nameJs.'\', \''.$picUrl.'\', \''.$lastSeenText.'\', \''.$isOnlineJs.'\')">
                <div class="bb-active-avatar" style="overflow:hidden;position:relative;">
                    '.$pic.'
                    <span style="position:absolute;bottom:0;right:0;width:11px;height:11px;border-radius:50%;background:'.$dotColor.';border:2px solid #fff;display:block;"></span>
                </div>
                <div class="bb-active-meta">
                    <span class="bb-active-name">'.e($au->name).'</span>
                    <span class="bb-mini-badge '.$roleClass.'">'.$badgeContent.'</span>
                </div>
            </a>';
        }

        return response()->json(['html' => $html]);
    }

    // ==========================================
    // LAST SEEN FORMAT
    // ==========================================
    public static function formatLastSeen($lastSeen): string
    {
        if (!$lastSeen) return 'Never';

        $lastSeen = \Carbon\Carbon::parse($lastSeen);
        $diffMin  = now()->diffInMinutes($lastSeen);
        $diffHour = now()->diffInHours($lastSeen);
        $diffDay  = now()->diffInDays($lastSeen);

        if ($diffMin < 2)   return 'Active now';
        if ($diffMin < 60)  return 'Active ' . $diffMin . 'm ago';
        if ($diffHour < 24) return 'Active ' . $diffHour . 'h ago';
        if ($diffDay < 7)   return 'Active ' . $diffDay . 'd ago';

        return 'Active ' . $lastSeen->format('M d');
    }

    public function messengerContacts()
    {
        $meId       = Auth::id();
        $friendIds  = Friendship::friendIds($meId);
        $blockedIds = $this->getBlockedIds($meId);

        $contacts = \App\Models\User::whereIn('id', $friendIds)
            ->whereNotIn('id', $blockedIds)
            ->select('id', 'name', 'profile_picture', 'last_seen')
            ->orderByDesc('last_seen')
            ->limit(20)
            ->get()
            ->map(fn($u) => [
                'id'              => $u->id,
                'name'            => $u->name,
                'profile_picture' => $u->profile_picture,
                'is_online'       => $u->last_seen && $u->last_seen >= now()->subMinutes(10),
            ]);

        return response()->json(['contacts' => $contacts]);
    }

    

}