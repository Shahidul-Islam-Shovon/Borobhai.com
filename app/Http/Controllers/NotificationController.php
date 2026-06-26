<?php
namespace App\Http\Controllers;

use App\Models\BbNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // ==========================================
    // Notification list (dropdown)
    // ==========================================
    public function index()
    {
        $meId = Auth::id();
        $notifications = BbNotification::where('user_id', $meId)
            ->with('actor:id,name,profile_picture,role')
            ->latest()
            ->limit(20)
            ->get();

        // সব unread → read mark করি
        BbNotification::where('user_id', $meId)->where('is_read', false)->update(['is_read' => true]);

        $html = '';
        foreach ($notifications as $n) {
            $actor   = $n->actor;
            $pic     = $actor->profile_picture
                ? asset('storage/' . $actor->profile_picture)
                : null;
            $initial = strtoupper(substr($actor->name, 0, 1));
            $avatar  = $pic
                ? '<img src="'.$pic.'" style="width:100%;height:100%;object-fit:cover;">'
                : $initial;

            $timeAgo = $n->created_at->diffForHumans();
            $unreadClass = $n->is_read ? '' : 'bb-notif-unread';

            // Link — type অনুযায়ী
            $link = match ($n->notifiable_type) {
                'post'    => '#postCard-' . $n->notifiable_id,
                'job'     => route('jobs.show', $n->notifiable_id),
                'comment' => '#postCard-' . $n->notifiable_id,
                'user'    => route('profile.view', $n->actor_id),
                default   => route('home'),
            };

            $icon = match ($n->type) {
                'friend_request' => '<i class="bi bi-person-plus-fill text-primary"></i>',
                'friend_accept'  => '<i class="bi bi-people-fill text-success"></i>',
                'post_like'      => '<i class="bi bi-hand-thumbs-up-fill text-primary"></i>',
                'post_comment'   => '<i class="bi bi-chat-fill text-info"></i>',
                'comment_like'   => '<i class="bi bi-hand-thumbs-up-fill text-warning"></i>',
                'post_share'     => '<i class="bi bi-share-fill text-primary"></i>',
                'job_apply'      => '<i class="bi bi-briefcase-fill text-success"></i>',
                'job_status'     => '<i class="bi bi-briefcase-fill text-warning"></i>',
                default          => '<i class="bi bi-bell-fill text-muted"></i>',
            };

            $html .= '
            <a href="'.$link.'" class="bb-notif-item '.$unreadClass.'" style="text-decoration:none;">
                <div class="bb-notif-avatar" style="overflow:hidden;">'.$avatar.'</div>
                <div class="bb-notif-body">
                    <div class="bb-notif-msg">'.e($n->message).'</div>
                    <div class="bb-notif-time">'.$icon.' '.$timeAgo.'</div>
                </div>
            </a>';
        }

        if (!$html) {
            $html = '<div class="bb-notif-empty"><i class="bi bi-bell-slash"></i><p>No notifications yet</p></div>';
        }

        return response()->json(['html' => $html]);
    }

    // ==========================================
    // Poll — unread count (every 15s)
    // ==========================================
    public function poll()
    {
        $count = BbNotification::unreadCount(Auth::id());
        return response()->json(['count' => $count]);
    }

    // ==========================================
    // Mark all as read
    // ==========================================
    public function markAllRead()
    {
        BbNotification::where('user_id', Auth::id())->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}