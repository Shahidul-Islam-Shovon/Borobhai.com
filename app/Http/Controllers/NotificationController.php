<?php

namespace App\Http\Controllers;

use App\Models\BbNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // ১) Dropdown list — latest 20
    public function index()
    {
        $meId = Auth::id();

        $notifications = BbNotification::where('user_id', $meId)
            ->with('actor:id,name,profile_picture,role')
            ->latest()->limit(20)->get();

        // FB এর মত: খুললে শুধু "seen" → badge সরবে, কিন্তু is_read অপরিবর্তিত
        BbNotification::where('user_id', $meId)
            ->where('seen', false)->update(['seen' => true]);

        return response()->json(['html' => $this->buildItems($notifications)]);
    }

    // ২) Badge — unseen count
    public function poll()
    {
        return response()->json([
            'count' => BbNotification::where('user_id', Auth::id())
                ->where('seen', false)->count(),
        ]);
    }

    // ৩) একটা read
    public function markOneRead($id)
    {
        BbNotification::where('id', $id)->where('user_id', Auth::id())
            ->update(['is_read' => true, 'seen' => true]);
        return response()->json(['success' => true]);
    }

    // ৪) সব read
    public function markAllRead()
    {
        BbNotification::where('user_id', Auth::id())
            ->update(['is_read' => true, 'seen' => true]);
        return response()->json(['success' => true]);
    }

    // ৫) See all — full page (Today / This Week / Earlier grouping)
    public function all()
    {
        $meId = Auth::id();

        $notifications = BbNotification::where('user_id', $meId)
            ->with('actor:id,name,profile_picture,role')
            ->latest()->paginate(20);

        // খোলার সাথে badge clear (seen → true)
        BbNotification::where('user_id', $meId)
            ->where('seen', false)->update(['seen' => true]);

        // current page items কে bucket-এ ভাগ করি
        $groups = ['Today' => [], 'This Week' => [], 'Earlier' => []];
        foreach ($notifications as $n) {
            $created = $n->created_at;
            if ($created->isToday())            $groups['Today'][]     = $n;
            elseif ($created->greaterThan(now()->subDays(7))) $groups['This Week'][] = $n;
            else                                $groups['Earlier'][]   = $n;
        }
        $groups = array_filter($groups, fn ($g) => count($g) > 0);

        return view('notifications.index', compact('notifications', 'groups'));
    }

    // ===== item builder =====
    private function buildItems($list): string
    {
        $html = '';
        foreach ($list as $n) $html .= $this->itemHtml($n);
        return $html;
    }

    private function itemHtml($n): string
    {
        $actor  = $n->actor;
        $pic    = $actor?->profile_picture ? asset('storage/' . $actor->profile_picture) : null;
        $avatar = $pic
            ? '<img src="' . $pic . '" style="width:100%;height:100%;object-fit:cover;">'
            : strtoupper(substr($actor?->name ?? 'U', 0, 1));

        [$action, $target] = $this->resolveTarget($n);
        $unread = $n->is_read ? '' : 'bb-notif-unread';
        $dot    = $n->is_read ? '' : '<span class="bb-notif-dot"></span>';

        return '
        <div class="bb-notif-item ' . $unread . '"
             data-id="' . $n->id . '" data-action="' . $action . '"
             data-target="' . $target . '" data-read="' . ($n->is_read ? 1 : 0) . '"
             onclick="onNotifClick(this)">
            <div class="bb-notif-avatar">' . $avatar . '</div>
            <div class="bb-notif-body">
                <div class="bb-notif-msg">' . e($n->message) . '</div>
                <div class="bb-notif-time">' . $this->iconFor($n->type) . ' ' . $n->created_at->diffForHumans() . '</div>
            </div>' . $dot . '
        </div>';
    }

    // ⚠️ comment-জাতীয় type এ notifiable_id = POST id হতে হবে (নিচে নোট দেখো)
    private function resolveTarget($n): array
    {
        return match ($n->type) {
            'friend_request', 'friend_accept', 'new_message' => ['profile', $n->actor_id],
            'post_like', 'post_share'                        => ['post', $n->notifiable_id],
            'post_comment', 'comment_reply', 'comment_like'  => ['comments', $n->notifiable_id],
            'job_status'                                     => ['myapplications', 0],
            'new_job', 'job_apply',
            'job_deadline_soon', 'job_deadline_expired'      => ['job', $this->jobHashid($n->notifiable_id)],
            default                                          => ['home', 0],
        };
    }

    // raw job id → hashid string, route এ বসানোর জন্য
    // job ডিলিট হয়ে গেলে notification থেকে যেতে পারে — তখন find() null দেবে,
    // সেক্ষেত্রে raw id fallback করছি (লিংক কাজ নাও করতে পারে, কিন্তু পেজ crash করবে না)
    private function jobHashid($rawId): string
    {
        $job = \App\Models\JobPost::find($rawId);
        return $job ? $job->getRouteKey() : (string) $rawId;
    }

    private function iconFor($type): string
    {
        return match ($type) {
            'friend_request'       => '<i class="bi bi-person-plus-fill text-primary"></i>',
            'friend_accept'        => '<i class="bi bi-people-fill text-success"></i>',
            'post_like'            => '<i class="bi bi-hand-thumbs-up-fill text-primary"></i>',
            'post_comment'         => '<i class="bi bi-chat-fill text-info"></i>',
            'comment_reply'        => '<i class="bi bi-reply-fill text-info"></i>',
            'comment_like'         => '<i class="bi bi-hand-thumbs-up-fill text-warning"></i>',
            'post_share'           => '<i class="bi bi-share-fill text-primary"></i>',
            'new_job'              => '<i class="bi bi-briefcase-fill text-success"></i>',
            'job_apply'            => '<i class="bi bi-file-earmark-person-fill text-success"></i>',
            'job_status'           => '<i class="bi bi-briefcase-fill text-warning"></i>',
            'job_deadline_soon'    => '<i class="bi bi-clock-fill text-warning"></i>',
            'job_deadline_expired' => '<i class="bi bi-hourglass-bottom text-danger"></i>',
            'report_submitted'     => '<i class="bi bi-flag-fill text-danger"></i>',
            'new_message'          => '<i class="bi bi-chat-dots-fill text-primary"></i>',
            default                => '<i class="bi bi-bell-fill text-muted"></i>',
        };
    }

    // blade থেকে একটা notification এর data পাওয়ার জন্য
    public function metaFor($n): array
    {
        [$action, $target] = $this->resolveTarget($n);
        return [
            'action' => $action,
            'target' => $target,
            'icon'   => $this->iconFor($n->type),
        ];
    }
}