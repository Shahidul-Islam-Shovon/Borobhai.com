<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Carbon\Carbon;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $reports = \App\Models\Report::where(function ($q) {
        $q->where('status', 'pending')->orWhere('appeal_status', 'pending');
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($r) => $this->hydrateActiveReport($r));

        // ✅ একই রিপোর্টেড ইউজারের একাধিক pending রিপোর্ট (user/post/job যেকোনো টাইপ) একসাথে গ্রুপ করা
        $groupedByUser = $reports->filter(fn($r) => $r->targetUser)->groupBy(fn($r) => $r->targetUser->id);

        $completedReports = \App\Models\Report::whereIn('status', ['dismissed', 'completed'])
            ->where(function ($q) {
                $q->whereNull('appeal_status')->orWhere('appeal_status', '!=', 'pending');
            })
            ->latest('updated_at')
            ->limit(80)
            ->get()
            ->map(fn($r) => $this->hydrateHistoryReport($r));

        return view('admin.dashboard', compact('counters', 'users', 'circulars', 'reports', 'completedReports', 'groupedByUser'));
    }

    // ---------- HYDRATION HELPERS ----------

    private function hydrateActiveReport($r)
    {
        $targetTitle = ''; $targetLink = null; $targetUser = null;

        if ($r->type === 'post') {
            $post = \App\Models\Post::withTrashed()->find($r->target_id);
            $targetTitle = $post ? \Illuminate\Support\Str::limit($post->content ?: '[Media Post]', 60) : '[Deleted]';
            $targetLink  = $post ? route('admin.post.review', $post->hashid) : null;
            $targetUser  = $post?->user;
        } elseif ($r->type === 'job') {
            $job = \App\Models\JobPost::withTrashed()->find($r->target_id);
            $targetTitle = $job ? $job->title . ' — ' . $job->company : '[Deleted]';
            $targetLink  = $job && !$job->trashed() ? route('jobs.show', $job) : null;
            $targetUser  = $job?->user;
        } elseif ($r->type === 'user') {
            $user = \App\Models\User::find($r->target_id);
            $targetTitle = $user ? $user->name . ' (' . ucfirst($user->role) . ')' : '[Deleted]';
            $targetLink  = $user ? route('profile.view', $user) : null;
            $targetUser  = $user;
        }

        $r->targetTitle = $targetTitle;
        $r->targetLink  = $targetLink;
        $r->targetUser  = $targetUser;
        $r->targetUserStatus = $targetUser?->status ?? 'active';
        return $r;
    }

    private function hydrateHistoryReport($r)
    {
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
        $r->targetTitle = $targetTitle;
        return $r;
    }

    private function renderHistoryRow($report)
    {
        $hydrated = $this->hydrateHistoryReport($report);
        return trim(view('admin.partials.history-row', ['r' => $hydrated])->render());
    }

    // ---------- SEEN TRACKING (single endpoint, handles active + history) ----------

    public function markSeen($reportId)
    {
        $report = \App\Models\Report::findOrFail($reportId);
        if (in_array($report->status, ['dismissed', 'completed'])) {
            $report->update(['history_seen' => true]);
        } else {
            $report->update(['admin_seen' => true]);
        }
        return response()->json(['success' => true]);
    }

    // ---------- REPORT ACTIONS ----------

    public function warnUser(Request $request, $reportId)
    {
        $report = \App\Models\Report::findOrFail($reportId);
        $note   = $request->input('note');

        $targetUserId = match($report->type) {
            'post'  => \App\Models\Post::withTrashed()->find($report->target_id)?->user_id,
            'job'   => \App\Models\JobPost::withTrashed()->find($report->target_id)?->user_id,
            'user'  => $report->target_id,
            default => null,
        };

        if (!$targetUserId) {
            return response()->json(['success' => false, 'message' => 'Target user not found.'], 404);
        }

        $report->update([
            'admin_id'    => auth()->id(),
            'admin_note'  => $note,
            'was_warned'  => true,
            'reviewed_at' => now(),
        ]);

        \App\Models\BbNotification::send(
            $targetUserId, auth()->id(), 'admin_decision',
            '⚠️ A warning has been issued regarding your ' . $report->type . '. Click to view details.',
            'report', $report->id
        );

        return response()->json(['success' => true, 'message' => 'Warning sent. Report remains active.']);
    }

   public function dismissReport($reportId)
{
    $report = \App\Models\Report::findOrFail($reportId);
    $wasAppealPending = $report->appeal_status === 'pending';

    $report->update([
        'status'        => 'dismissed',
        'action_taken'  => 'dismissed_no_violation',
        'admin_id'      => auth()->id(),
        'reviewed_at'   => now(),
        'history_seen'  => false,
        'appeal_status' => $wasAppealPending ? 'ignored' : $report->appeal_status, // ✅ নতুন
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Report dismissed.',
        'history_row' => $this->renderHistoryRow($report->fresh()),
    ]);
}

public function deleteReportedContent(Request $request, $reportId)
{
    $report = \App\Models\Report::findOrFail($reportId);
    $note   = $request->input('note');
    $userId = null;
    $wasAppealPending = $report->appeal_status === 'pending'; // ✅ নতুন

    if ($report->type === 'post') {
        $post = \App\Models\Post::find($report->target_id);
        if ($post) {
            $userId = $post->user_id;
            $post->update(['deleted_by_admin_id' => auth()->id(), 'admin_delete_note' => $note]);
            $post->delete();
        }
    } elseif ($report->type === 'job') {
        $job = \App\Models\JobPost::find($report->target_id);
        if ($job) {
            $userId = $job->user_id;
            $job->update(['deleted_by_admin_id' => auth()->id(), 'admin_delete_note' => $note]);
            $job->delete();
        }
    }

    $report->update([
        'admin_id'      => auth()->id(),
        'admin_note'    => $note,
        'action_taken'  => 'deleted',
        'reviewed_at'   => now(),
        'status'        => 'dismissed',
        'history_seen'  => false,
        'appeal_status' => $wasAppealPending ? 'ignored' : $report->appeal_status, // ✅ নতুন
    ]);

    if ($userId) {
        \App\Models\BbNotification::send(
            $userId, auth()->id(), 'admin_decision',
            '🗑️ Your ' . $report->type . ' was removed by an admin. Click to view the reason.',
            'report', $report->id
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Content deleted successfully.',
        'history_row' => $this->renderHistoryRow($report->fresh()),
    ]);
}

public function completeReport($reportId)
{
    $report = \App\Models\Report::findOrFail($reportId);
    $wasAppealPending = $report->appeal_status === 'pending'; // ✅ নতুন

    $finalAction = in_array($report->action_taken, ['deleted', 'dismissed_no_violation'])
        ? $report->action_taken
        : 'resolved_other';

    $report->update([
        'status'        => 'completed',
        'action_taken'  => $finalAction,
        'reviewed_at'   => $report->reviewed_at ?? now(),
        'history_seen'  => false,
        'appeal_status' => $wasAppealPending ? 'ignored' : $report->appeal_status, // ✅ নতুন
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Report marked as completed.',
        'history_row' => $this->renderHistoryRow($report->fresh()),
    ]);
}

    public function submitReview(Request $request, $reportId)
    {
        $request->validate(['note' => 'required|string|max:2000']);
        $report = \App\Models\Report::findOrFail($reportId);

        $targetUserId = match($report->type) {
            'post'  => \App\Models\Post::withTrashed()->find($report->target_id)?->user_id,
            'job'   => \App\Models\JobPost::withTrashed()->find($report->target_id)?->user_id,
            'user'  => $report->target_id,
            default => null,
        };

        $report->update([
            'admin_id'     => auth()->id(),
            'admin_note'   => $request->note,
            'action_taken' => $report->action_taken ?? 'reviewed_note',
            'reviewed_at'  => now(),
        ]);

        if ($targetUserId) {
            \App\Models\BbNotification::send(
                $targetUserId, auth()->id(), 'admin_decision',
                '📝 Admin has reviewed the content you were reported for. Click to view details.',
                'report', $report->id
            );
        }

        return response()->json(['success' => true, 'message' => 'Review note sent to user.']);
    }

    public function markReviewed($reportId)
    {
        $report = \App\Models\Report::findOrFail($reportId);

        if ($report->type === 'user') {
            \DB::table('users')->where('id', $report->target_id)->update(['status' => 'active', 'suspended_until' => null, 'updated_at' => now()]);
        }
        if ($report->type === 'post') {
            $post = \App\Models\Post::withTrashed()->find($report->target_id);
            $post?->restore();
            $post?->update(['deleted_by_admin_id' => null, 'admin_delete_note' => null]);
        }
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
            'history_seen'  => false,
        ]);

        $targetUserId = match($report->type) {
            'post'  => \App\Models\Post::withTrashed()->find($report->target_id)?->user_id,
            'job'   => \App\Models\JobPost::withTrashed()->find($report->target_id)?->user_id,
            'user'  => $report->target_id,
            default => null,
        };

        if ($targetUserId) {
            \App\Models\BbNotification::send(
                $targetUserId, auth()->id(), 'admin_decision',
                '✅ Your appeal has been reviewed. No action will be taken — everything has been restored.',
                'report', $report->id
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Report reviewed & cancelled — everything restored.',
            'history_row' => $this->renderHistoryRow($report->fresh()),
        ]);
    }

    public function suspendFromReport(Request $request, $userId)
    {
        $request->validate(['action' => 'required|in:temp,perm,active']);

        $targetUser = \App\Models\User::find($userId);
        if (!$targetUser) return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        if ($targetUser->id === auth()->id()) return response()->json(['success' => false, 'message' => 'Cannot suspend yourself.'], 403);
        if ($targetUser->role === 'admin' && !auth()->user()->isSuperAdmin()) return response()->json(['success' => false, 'message' => 'Cannot suspend admin users.'], 403);

        $action = $request->action;
        [$status, $until, $message] = match($action) {
            'temp'   => ['suspended_temp', Carbon::now()->addDays(7)->toDateTimeString(), '🚫 Your account has been suspended for 7 days.'],
            'perm'   => ['suspended_perm', null, '🚫 Your account has been permanently suspended.'],
            'active' => ['active', null, '✅ Your account suspension has been lifted.'],
        };

        \DB::table('users')->where('id', $userId)->update([
            'status' => $status, 'suspended_until' => $until, 'updated_at' => Carbon::now(),
        ]);

        \DB::table('bb_notifications')->insert([
            'user_id' => $userId, 'actor_id' => auth()->id(), 'type' => 'report_submitted',
            'notifiable_type' => 'home', 'notifiable_id' => null, 'message' => $message,
            'is_read' => false, 'seen' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated: ' . $status, 'new_status' => $status]);
    }

    // ---------- USER / ROLE MANAGEMENT ----------

    public function manageAuthority(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access!'], 403);
        }

        $request->validate(['user_id' => 'required|exists:users,id', 'type' => 'required|in:student,alumni,teacher,admin,super']);

        $targetUser = User::findOrFail($request->user_id);
        $chiefEmail = env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com');

        if ($targetUser->email === $chiefEmail) {
            return response()->json(['success' => false, 'message' => 'The Main System Administrator is fully secured!'], 422);
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

    public function changeUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $chiefEmail = env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com');
        if ($user->email === $chiefEmail) {
            return response()->json(['success' => false, 'message' => 'Main System Administrator protected!'], 403);
        }
        if ($user->role === 'admin' && !auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => "You do not have permission to change another Admin's role."], 403);
        }
        $user->update(['role' => $request->role]);
        return response()->json(['success' => true, 'message' => 'Role updated to ' . ucfirst($request->role)]);
    }

    public function getAnalyticsData()
    {
        $labels = []; $userData = []; $postData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
            $userData[] = User::whereDate('created_at', $date->format('Y-m-d'))->count();
            $postData[] = Post::whereDate('created_at', $date->format('Y-m-d'))->count();
        }
        return response()->json([
            'labels' => $labels, 'users' => $userData, 'posts' => $postData,
            'counters' => [
                'total_users'     => User::count(),
                'total_students'  => User::where('role', 'student')->count(),
                'total_alumni'    => User::where('role', 'alumni')->count(),
                'total_circulars' => \App\Models\JobPost::count(),
                'pending_reports' => \App\Models\Report::where('status', 'pending')->count(),
            ],
        ]);
    }

    public function deletePost($id)
    {
        try { Post::findOrFail($id)->delete(); return response()->json(['success' => true, 'message' => 'Post deleted.']); }
        catch (Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()], 500); }
    }

    public function deleteCircular($id)
    {
        try { \App\Models\JobPost::findOrFail($id)->delete(); return response()->json(['success' => true, 'message' => 'Job circular removed.']); }
        catch (Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()], 500); }
    }

    public function updateSuspensionStatus(Request $request, $id)
    {
        $user = \DB::table('users')->where('id', $id)->first();
        if (!$user) return response()->json(['success' => false, 'message' => 'User not found. ID: ' . $id], 404);

        $action = $request->input('action');
        $newStatus = $user->status; $newUntil = $user->suspended_until;

        if ($action === 'temp') {
            if ($user->status === 'suspended_temp') { $newStatus = 'active'; $newUntil = null; }
            else { $newStatus = 'suspended_temp'; $newUntil = Carbon::now()->addDays(7)->toDateTimeString(); }
        } elseif ($action === 'perm') {
            if ($user->status === 'suspended_perm') { $newStatus = 'active'; $newUntil = null; }
            else { $newStatus = 'suspended_perm'; $newUntil = null; }
        } elseif ($action === 'active') {
            $newStatus = 'active'; $newUntil = null;
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid action.'], 400);
        }

        \DB::table('users')->where('id', $id)->update(['status' => $newStatus, 'suspended_until' => $newUntil, 'updated_at' => Carbon::now()]);
        return response()->json(['success' => true, 'message' => 'User status updated: ' . $newStatus]);
    }

    // AdminDashboardController এ যোগ করো
    public function pollReports(Request $request)
    {
        $since = $request->input('since', now()->subSeconds(20)->toISOString());

        try {
            $sinceCarbon = \Carbon\Carbon::parse($since);
        } catch (\Exception $e) {
            $sinceCarbon = now()->subSeconds(20);
        }

        // ✅ নতুন pending reports
        $newActiveReports = \App\Models\Report::where(function($q) {
                $q->where('status', 'pending')->orWhere('appeal_status', 'pending');
            })
            ->where('created_at', '>', $sinceCarbon)
            ->get()
            ->map(fn($r) => $this->hydrateActiveReport($r));

        // ✅ নতুন completed reports (যেগুলো since এর পরে complete/dismiss হয়েছে)
        $newHistoryReports = \App\Models\Report::whereIn('status', ['dismissed', 'completed'])
            ->where(function($q) {
                $q->whereNull('appeal_status')->orWhere('appeal_status', '!=', 'pending');
            })
            ->where('updated_at', '>', $sinceCarbon)
            ->get()
            ->map(fn($r) => $this->hydrateHistoryReport($r));

        // ✅ Counts
        $pendingContent  = \App\Models\Report::where('status', 'pending')->whereNotIn('type', ['job'])->count();
        $pendingJobs     = \App\Models\Report::where('status', 'pending')->where('type', 'job')->count();
        $historyUnseen   = \App\Models\Report::whereIn('status', ['dismissed', 'completed'])->where('history_seen', false)->count();

        // ✅ Render rows as HTML
        $contentRows = [];
        $jobRows     = [];
        foreach ($newActiveReports as $r) {
            $html = view('admin.partials.active-report-row', ['report' => $r, 'groupedByUser' => collect()])->render();
            if ($r->type === 'job') {
                $jobRows[] = ['id' => $r->id, 'html' => trim($html)];
            } else {
                $contentRows[] = ['id' => $r->id, 'html' => trim($html)];
            }
        }

        $historyRows = [];
        foreach ($newHistoryReports as $r) {
            $historyRows[] = [
                'id'   => $r->id,
                'html' => trim(view('admin.partials.history-row', ['r' => $r])->render()),
            ];
        }

        return response()->json([
            'success'         => true,
            'server_time'     => now()->toISOString(),
            'content_rows'    => $contentRows,
            'job_rows'        => $jobRows,
            'history_rows'    => $historyRows,
            'pending_content' => $pendingContent,
            'pending_jobs'    => $pendingJobs,
            'history_unseen'  => $historyUnseen,
        ]);
    }

    public function downloadReport()
    {
        $counters = [
            'total_users'     => User::count(),
            'total_students'  => User::where('role', 'student')->count(),
            'total_alumni'    => User::where('role', 'alumni')->count(),
            'total_teachers'  => User::where('role', 'teacher')->count(),
            'total_circulars' => \App\Models\JobPost::count(),
            'pending_reports' => \App\Models\Report::where('status', 'pending')->count(),
            'resolved_reports'=> \App\Models\Report::whereIn('status', ['dismissed', 'completed'])->count(),
            'suspended_temp'  => User::where('status', 'suspended_temp')->count(),
            'suspended_perm'  => User::where('status', 'suspended_perm')->count(),
        ];

        $users = User::orderBy('name')->get(['id', 'name', 'email', 'role', 'status', 'created_at']);

        $reportHistory = \App\Models\Report::whereIn('status', ['dismissed', 'completed'])
            ->latest('updated_at')
            ->limit(200) // অনেক বেশি হলে PDF ভারী হয়ে যায় — প্রয়োজনে বাড়ান/কমান
            ->get()
            ->map(fn($r) => $this->hydrateHistoryReport($r));

        $pdf = Pdf::loadView('admin.partials.full-report-pdf', [
            'counters'      => $counters,
            'users'         => $users,
            'reportHistory' => $reportHistory,
            'generated'     => now(),
            'generatedBy'   => auth()->user()->name,
        ])->setPaper('a4', 'portrait');

        $fileName = 'borobhai-admin-report-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($fileName);
    }

}