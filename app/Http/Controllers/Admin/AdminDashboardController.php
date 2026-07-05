<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Circular;
use Carbon\Carbon;
use Exception;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $counters = [
            'total_users'     => User::count(),
            'total_students'  => User::where('role', 'student')->count(),
            'total_alumni'    => User::where('role', 'alumni')->count(),
            'total_circulars' => \App\Models\JobPost::count(),
            'pending_reports' => \App\Models\Report::where('status', 'pending')->count(),
        ];

        $users     = User::latest()->get();
        $circulars = \App\Models\JobPost::with('user')->latest()->get();

        $reports = \App\Models\Report::where('status', 'pending')
        ->latest()
        ->get()
        ->map(function ($r) {
            $targetTitle = '';
            $targetLink  = null;
            $targetUser  = null;

            if ($r->type === 'post') {
                $post = \App\Models\Post::withTrashed()->find($r->target_id);
                $targetTitle = $post
                    ? \Illuminate\Support\Str::limit($post->content ?: '[Media Post]', 60)
                    : '[Deleted]';
                $targetLink = $post ? route('admin.post.review', $post->hashid) : null; // ⬅️ বদলানো হয়েছে
                $targetUser  = $post?->user;
            

            } elseif ($r->type === 'job') {
                $job = \App\Models\JobPost::find($r->target_id);
                $targetTitle = $job ? $job->title . ' — ' . $job->company : '[Deleted]';
                $targetLink  = $job ? route('jobs.show', $job) : null;
                $targetUser  = $job?->user;

            } elseif ($r->type === 'user') {
                $user = \App\Models\User::find($r->target_id);
                $targetTitle = $user ? $user->name . ' (' . ucfirst($user->role) . ')' : '[Deleted]';
                $targetLink  = $user ? route('profile.view', $user) : null;
                $targetUser  = $user;
            }

            $r->targetTitle     = $targetTitle;
            $r->targetLink      = $targetLink;
            $r->targetUser      = $targetUser;
            $r->targetUserStatus = $targetUser?->status ?? 'active';
            return $r;
        });

    // ✅ Completed history আলাদা
    $completedReports = \App\Models\Report::whereIn('status', ['warned', 'dismissed', 'completed'])
    ->latest()
    ->limit(50)
    ->get()
    ->map(function ($r) {
        $targetTitle = '';
        if ($r->type === 'post') {
            $post = \App\Models\Post::withTrashed()->find($r->target_id);
            $targetTitle = $post ? \Illuminate\Support\Str::limit($post->content ?: '[Media Post]', 40) : '[Deleted]';
        } elseif ($r->type === 'job') {
            $job = \App\Models\JobPost::withTrashed()->find($r->target_id);
            $targetTitle = $job ? $job->title : '[Deleted]';
        } elseif ($r->type === 'user') {
            $user = \App\Models\User::find($r->target_id);
            $targetTitle = $user ? $user->name : '[Deleted]';
        }
        $r->targetTitle = $targetTitle; // appeal_status কলামেই আছে মডেলে, আলাদা করে সেট করার দরকার নেই
        return $r;
    });

    return view('admin.dashboard', compact('counters', 'users', 'circulars', 'reports', 'completedReports'));

        }

    // ✅ FIXED: target_id থেকে সরাসরি user_id বের করো
    public function warnUser(Request $request, $reportId)
    {
        $report = \App\Models\Report::findOrFail($reportId);
        $note   = $request->input('note');

        $targetUserId = null;
        if ($report->type === 'post') {
            $targetUserId = \App\Models\Post::withTrashed()->find($report->target_id)?->user_id;
        } elseif ($report->type === 'job') {
            $targetUserId = \App\Models\JobPost::withTrashed()->find($report->target_id)?->user_id;
        } elseif ($report->type === 'user') {
            $targetUserId = $report->target_id;
        }

        if (!$targetUserId) {
            return response()->json(['success' => false, 'message' => 'Target user not found.'], 404);
        }

        $report->update([
            'admin_id'     => auth()->id(),
            'admin_note'   => $note,
            'action_taken' => 'warned',
            'reviewed_at'  => now(),
        ]);

        // warnUser() মেথডে
        \App\Models\BbNotification::send(
            $targetUserId,
            auth()->id(),
            'admin_decision',
            '⚠️ A warning has been issued regarding your ' . $report->type . '. Click to view details.',
            'report',
            $report->id
        );

        return response()->json(['success' => true, 'message' => 'Warning sent to user.']);
    }


   public function suspendFromReport(Request $request, $userId)
{
    $request->validate(['action' => 'required|in:temp,perm,active']);

    $targetUser = \App\Models\User::find($userId);
    if (!$targetUser) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    if ($targetUser->id === auth()->id()) {
        return response()->json(['success' => false, 'message' => 'Cannot suspend yourself.'], 403);
    }
    if ($targetUser->role === 'admin' && !auth()->user()->isSuperAdmin()) {
        return response()->json(['success' => false, 'message' => 'Cannot suspend admin users.'], 403);
    }

    $action  = $request->action;
    $status  = 'active';
    $until   = null;
    $message = '';

    // ✅ toggle নয়, সরাসরি set করো
    if ($action === 'temp') {
        $status  = 'suspended_temp';
        $until   = Carbon::now()->addDays(7)->toDateTimeString();
        $message = '🚫 Your account has been suspended for 7 days.';
    } elseif ($action === 'perm') {
        $status  = 'suspended_perm';
        $message = '🚫 Your account has been permanently suspended.';
    } elseif ($action === 'active') {
        $status  = 'active';
        $message = '✅ Your account suspension has been lifted.';
    }

    \DB::table('users')->where('id', $userId)->update([
        'status'          => $status,
        'suspended_until' => $until,
        'updated_at'      => Carbon::now(),
    ]);

    \DB::table('bb_notifications')->insert([
        'user_id'         => $userId,
        'actor_id'        => auth()->id(),
        'type'            => 'report_submitted',
        'notifiable_type' => 'home',
        'notifiable_id'   => null,
        'message'         => $message,
        'is_read'         => false,
        'seen'            => false,
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Status updated: ' . $status,
        'new_status' => $status
    ]);
}

    public function dismissReport($reportId)
    {
        \App\Models\Report::findOrFail($reportId)->update(['status' => 'dismissed']);
        return response()->json(['success' => true, 'message' => 'Report dismissed.']);
    }

    public function deleteReportedContent(Request $request, $reportId)
    {
        $report = \App\Models\Report::findOrFail($reportId);
        $note   = $request->input('note');
        $userId = null;

        if ($report->type === 'post') {
            $post = \App\Models\Post::find($report->target_id);
            if ($post) {
                $userId = $post->user_id;
                $post->update([
                    'deleted_by_admin_id' => auth()->id(),
                    'admin_delete_note'   => $note,
                ]);
                $post->delete(); // soft delete — কন্টেন্ট থেকে যায়, ইউজার নোট সহ দেখতে পারবে
            }
        } elseif ($report->type === 'job') {
            $job = \App\Models\JobPost::find($report->target_id);
            if ($job) {
                $userId = $job->user_id;
                $job->update([
                    'deleted_by_admin_id' => auth()->id(),
                    'admin_delete_note'   => $note,
                ]);
                $job->delete();
            }
        }

        $report->update([
            'admin_id'     => auth()->id(),
            'admin_note'   => $note,
            'action_taken' => 'deleted',
            'reviewed_at'  => now(),
            'status'       => 'dismissed',
        ]);

        if ($userId) {
            // deleteReportedContent() মেথডে
            \App\Models\BbNotification::send(
                $userId,
                auth()->id(),
                'admin_decision',
                '🗑️ Your ' . $report->type . ' was removed by an admin. Click to view the reason.',
                'report',
                $report->id
            );
        }

        return response()->json(['success' => true, 'message' => 'Content deleted successfully.']);
    }


    public function submitReview(Request $request, $reportId)
{
    $request->validate(['note' => 'required|string|max:2000']);
    $report = \App\Models\Report::findOrFail($reportId);

    $targetUserId = null;
    if ($report->type === 'post') {
        $targetUserId = \App\Models\Post::withTrashed()->find($report->target_id)?->user_id;
    } elseif ($report->type === 'job') {
        $targetUserId = \App\Models\JobPost::withTrashed()->find($report->target_id)?->user_id;
    } elseif ($report->type === 'user') {
        $targetUserId = $report->target_id;
    }

    $report->update([
        'admin_id'     => auth()->id(),
        'admin_note'   => $request->note,
        'action_taken' => $report->action_taken ?? 'reviewed_note',
        'reviewed_at'  => now(),
    ]);

    if ($targetUserId) {
        \App\Models\BbNotification::send(
            $targetUserId,
            auth()->id(),
            'admin_decision',
            '📝 Admin has reviewed the content you were reported for. Click to view details.',
            'report',
            $report->id
        );
    }
   return response()->json(['success' => true, 'message' => 'Review note sent to user.']);
}

public function markReviewed($reportId)
{
    $report = \App\Models\Report::findOrFail($reportId);

    // ইউজারের সাসপেনশন প্রত্যাহার
    if ($report->type === 'user') {
        \DB::table('users')->where('id', $report->target_id)->update([
            'status' => 'active', 'suspended_until' => null, 'updated_at' => now(),
        ]);
    }

    // পোস্ট রিস্টোর
    if ($report->type === 'post') {
        $post = \App\Models\Post::withTrashed()->find($report->target_id);
        $post?->restore();
        $post?->update(['deleted_by_admin_id' => null, 'admin_delete_note' => null]);
    }

    // জব রিস্টোর
    if ($report->type === 'job') {
        $job = \App\Models\JobPost::withTrashed()->find($report->target_id);
        $job?->restore();
        $job?->update(['deleted_by_admin_id' => null, 'admin_delete_note' => null]);
    }

    $report->update([
        'status'        => 'dismissed',
        'appeal_status' => 'reviewed',
        'admin_id'      => auth()->id(),
        'reviewed_at'   => now(),
    ]);

    $targetUserId = null;
    if ($report->type === 'post') {
        $targetUserId = \App\Models\Post::withTrashed()->find($report->target_id)?->user_id;
    } elseif ($report->type === 'job') {
        $targetUserId = \App\Models\JobPost::withTrashed()->find($report->target_id)?->user_id;
    } elseif ($report->type === 'user') {
        $targetUserId = $report->target_id;
    }

    if ($targetUserId) {
        \App\Models\BbNotification::send(
            $targetUserId,
            auth()->id(),
            'admin_decision',
            '✅ Your appeal has been reviewed. No action will be taken — everything has been restored.',
            'report',
            $report->id
        );
    }

    return response()->json(['success' => true, 'message' => 'Report reviewed & cancelled — everything restored.']);
}

    public function manageAuthority(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access!'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type'    => 'required|in:student,alumni,teacher,admin,super'
        ]);

        $targetUser = User::findOrFail($request->user_id);
        $chiefEmail = env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com');

        if ($targetUser->email === $chiefEmail) {
            return response()->json([
                'success' => false,
                'message' => 'The Main System Administrator is fully secured!'
            ], 422);
        }

        if (in_array($request->type, ['student', 'alumni', 'teacher'])) {
            $targetUser->update(['role' => $request->type, 'is_super_admin' => false]);
            return response()->json(['success' => true, 'message' => "{$targetUser->name} has been set as a regular member!"]);
        }

        if ($request->type === 'admin') {
            $targetUser->update(['role' => 'admin', 'is_super_admin' => false]);
            return response()->json(['success' => true, 'message' => "{$targetUser->name} has been made an Admin!"]);
        }

        if ($request->type === 'super') {
            $superAdminCount = User::where('is_super_admin', true)->count();
            if ($superAdminCount >= 2 && !$targetUser->isSuperAdmin()) {
                return response()->json(['success' => false, 'message' => 'Maximum of 2 Super Admins allowed!'], 422);
            }
            $targetUser->update(['role' => 'admin', 'is_super_admin' => true]);
            return response()->json(['success' => true, 'message' => "{$targetUser->name} is now a Super Admin!"]);
        }
    }

    public function changeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role'    => 'required|in:student,alumni,teacher,admin'
        ]);

        $user = User::findOrFail($request->user_id);
        $chiefEmail = env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com');

        if ($user->email === $chiefEmail) {
            return response()->json(['success' => false, 'message' => 'Main System Administrator protected!'], 422);
        }

        if ($user->role === 'admin' && !auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to change another Admin\'s role.'], 403);
        }

        $isSuper = ($request->role === 'admin') ? $user->is_super_admin : false;
        $user->update(['role' => $request->role, 'is_super_admin' => $isSuper]);

        return response()->json(['success' => true, 'message' => 'Role updated successfully!']);
    }

    public function getAnalyticsData()
    {
        $labels = [];
        $userData = [];
        $postData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date     = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
            $userData[] = User::whereDate('created_at', $date->format('Y-m-d'))->count();
            $postData[] = Post::whereDate('created_at', $date->format('Y-m-d'))->count();
        }

        return response()->json([
            'labels' => $labels,
            'users'  => $userData,
            'posts'  => $postData,
            'counters' => [
                'total_users'     => User::count(),
                'total_students'  => User::where('role', 'student')->count(),
                'total_alumni'    => User::where('role', 'alumni')->count(),
                'total_circulars' => \App\Models\JobPost::count(),
                'pending_reports' => \App\Models\Report::where('status', 'pending')->count(),
            ],
        ]);
    }

    public function changeUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $chiefEmail = env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com');

        if ($user->email === $chiefEmail) {
            return response()->json(['success' => false, 'message' => 'Main System Administrator protected!'], 403);
        }

        $user->update(['role' => $request->role]);
        return response()->json(['success' => true, 'message' => 'Role updated to ' . ucfirst($request->role)]);
    }

    public function deletePost($id)
    {
        try {
            Post::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Post deleted.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteCircular($id)
    {
        try {
            \App\Models\JobPost::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Job circular removed.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ✅ FIXED: Toggle logic সহ
    public function updateSuspensionStatus(Request $request, $id)
{
    // ✅ raw integer id দিয়ে খোঁজো
    $user = \DB::table('users')->where('id', $id)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found. ID: ' . $id
        ], 404);
    }

    $action     = $request->input('action');
    $newStatus  = $user->status;
    $newUntil   = $user->suspended_until;

    if ($action === 'temp') {
        if ($user->status === 'suspended_temp') {
            // ✅ Toggle: already temp → restore
            $newStatus = 'active';
            $newUntil  = null;
        } else {
            $newStatus = 'suspended_temp';
            $newUntil  = Carbon::now()->addDays(7)->toDateTimeString();
        }
    } elseif ($action === 'perm') {
        if ($user->status === 'suspended_perm') {
            // ✅ Toggle: already perm → restore
            $newStatus = 'active';
            $newUntil  = null;
        } else {
            $newStatus = 'suspended_perm';
            $newUntil  = null;
        }
    } elseif ($action === 'active') {
        $newStatus = 'active';
        $newUntil  = null;
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid action.'], 400);
    }

    \DB::table('users')->where('id', $id)->update([
        'status'          => $newStatus,
        'suspended_until' => $newUntil,
        'updated_at'      => Carbon::now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'User status updated: ' . $newStatus
    ]);
}

    // completeReport route for marking a report as completed
    public function completeReport($reportId)
    {
        \App\Models\Report::findOrFail($reportId)->update(['status' => 'completed']);
        return response()->json(['success' => true, 'message' => 'Report marked as completed.']);
    }
    
}