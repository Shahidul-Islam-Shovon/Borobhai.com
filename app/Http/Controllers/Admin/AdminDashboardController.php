<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
                    $post = \App\Models\Post::find($r->target_id);
                    $targetTitle = $post
                        ? \Illuminate\Support\Str::limit($post->content ?: '[Media Post]', 60)
                        : '[Deleted]';
                    // ✅ hash anchor — new tab এ খুলবে
                    // Post
                    $targetLink = $post ? url('/') . 'postCard-' . $r->target_id : null;

                    $targetUser = $post?->user;

                } elseif ($r->type === 'job') {
                    $job = \App\Models\JobPost::find($r->target_id);
                    $targetTitle = $job ? $job->title . ' — ' . $job->company : '[Deleted]';
                    $targetLink  = $job ? route('jobs.show', $job) : null;
                    $targetUser  = $job?->user;

                } elseif ($r->type === 'user') {
                    $user = User::find($r->target_id);
                    $targetTitle = $user ? $user->name . ' (' . ucfirst($user->role) . ')' : '[Deleted]';
                    $targetLink  = $user ? route('profile.view', $user) : null;
                    $targetUser  = $user;
                }

                $r->targetTitle = $targetTitle;
                $r->targetLink  = $targetLink;
                $r->targetUser  = $targetUser;
                return $r;
            });

        return view('admin.dashboard', compact('counters', 'users', 'circulars', 'reports'));
    }

    // ✅ FIXED: target_id থেকে সরাসরি user_id বের করো
    public function warnUser(Request $request, $reportId)
    {
        $report = \App\Models\Report::findOrFail($reportId);

        $targetUserId = null;

        if ($report->type === 'post') {
            $targetUserId = \App\Models\Post::find($report->target_id)?->user_id;
        } elseif ($report->type === 'job') {
            $targetUserId = \App\Models\JobPost::find($report->target_id)?->user_id;
        } elseif ($report->type === 'user') {
            $targetUserId = $report->target_id;
        }

        if (!$targetUserId) {
            return response()->json([
                'success' => false,
                'message' => 'Target user not found.'
            ], 404);
        }

        // ✅ Migration এর সব column সহ insert
        \DB::table('bb_notifications')->insert([
            'user_id'         => $targetUserId,
            'actor_id'        => auth()->id(),
            'type'            => 'report_submitted',
            'notifiable_type' => 'home',
            'notifiable_id'   => null,
            'message'         => '⚠️ Admin Warning: Your ' . $report->type . ' has been reported for violating community guidelines. Further violations may result in account suspension.',
            'is_read'         => false,
            'seen'            => false,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ✅ status pending রাখো — row গায়েব হবে না
        // warn হয়েছে এটা note করতে চাইলে details এ রাখো
        $report->update(['status' => 'pending']);

        return response()->json([
            'success' => true,
            'message' => 'Warning notification sent successfully.'
        ]);
    }

   public function suspendFromReport(Request $request, $userId)
    {
        $request->validate([
            'action' => 'required|in:temp,perm,active'
        ]);

        $targetUser = \App\Models\User::find($userId);

        if (!$targetUser) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // ✅ Security — admin নিজেকে বা অন্য admin কে suspend করতে পারবে না
        if ($targetUser->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Cannot suspend yourself.'], 403);
        }
        if ($targetUser->role === 'admin' && !auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Cannot suspend admin users.'], 403);
        }

        $action   = $request->action;
        $status   = 'active';
        $until    = null;
        $message  = '';

        if ($action === 'temp') {
            $status  = 'suspended_temp';
            $until   = Carbon::now()->addDays(7)->toDateTimeString();
            $message = '🚫 Your account has been suspended for 7 days due to policy violations.';
        } elseif ($action === 'perm') {
            $status  = 'suspended_perm';
            $message = '🚫 Your account has been permanently suspended due to repeated policy violations.';
        } elseif ($action === 'active') {
            $status  = 'active';
            $message = '✅ Your account suspension has been lifted by admin.';
        }

        \DB::table('users')->where('id', $userId)->update([
            'status'          => $status,
            'suspended_until' => $until,
            'updated_at'      => Carbon::now(),
        ]);

        // ✅ Notify user
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
            'message' => 'User suspended: ' . $status
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

        if ($report->type === 'post') {
            \App\Models\Post::find($report->target_id)?->delete();
        } elseif ($report->type === 'job') {
            \App\Models\JobPost::find($report->target_id)?->delete();
        }

        $report->update(['status' => 'dismissed']);
        return response()->json(['success' => true, 'message' => 'Content deleted successfully.']);
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
            return response()->json(['success' => true, 'message' => "{$targetUser->name} সাধারণ সদস্য করা হয়েছে!"]);
        }

        if ($request->type === 'admin') {
            $targetUser->update(['role' => 'admin', 'is_super_admin' => false]);
            return response()->json(['success' => true, 'message' => "{$targetUser->name} কে Admin করা হয়েছে!"]);
        }

        if ($request->type === 'super') {
            $superAdminCount = User::where('is_super_admin', true)->count();
            if ($superAdminCount >= 2 && !$targetUser->isSuperAdmin()) {
                return response()->json(['success' => false, 'message' => 'সর্বোচ্চ ২ জন Super Admin হতে পারবে!'], 422);
            }
            $targetUser->update(['role' => 'admin', 'is_super_admin' => true]);
            return response()->json(['success' => true, 'message' => "{$targetUser->name} এখন Super Admin!"]);
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
            return response()->json(['success' => false, 'message' => 'অন্য Admin এর role পরিবর্তন করার অনুমতি নেই।'], 403);
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

        return response()->json(['labels' => $labels, 'users' => $userData, 'posts' => $postData]);
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
    
}