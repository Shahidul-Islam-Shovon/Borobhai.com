<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportDecisionController extends Controller
{
    
    public function show($hashid)
{
    $id = Report::decodeId($hashid);
    if (!$id) abort(404);

    $report = Report::findOrFail($id);

    $targetUserId  = null;
    $post          = null;
    $job           = null;
    $isDeleted     = false;
    $contentType   = $report->type;

    if ($report->type === 'post') {
        $post = \App\Models\Post::withTrashed()
            ->with('user')
            ->withCount(['likes', 'comments'])
            ->find($report->target_id);
        $targetUserId = $post?->user_id;
        $isDeleted    = $post?->trashed() ?? false;
    } elseif ($report->type === 'job') {
        $job = \App\Models\JobPost::withTrashed()->with('user')->find($report->target_id);
        $targetUserId = $job?->user_id;
        $isDeleted    = $job?->trashed() ?? false;
    } elseif ($report->type === 'user') {
        $targetUserId = $report->target_id;
    }

    $viewer = Auth::user();
    $isAdminViewer = $viewer->role === 'admin' || $viewer->is_super_admin;

    if ($targetUserId !== Auth::id() && !$isAdminViewer) {
        abort(403);
    }

    return view('reports.decision', compact(
        'report', 'post', 'job', 'isDeleted', 'contentType', 'isAdminViewer'
    ));
}

    public function appeal(Request $request, $hashid)
    {
        $id = Report::decodeId($hashid);
        if (!$id) abort(404);

        $request->validate(['message' => 'required|string|max:1000']);
        $report = Report::findOrFail($id);

        $report->update([
            'appeal_message' => $request->message,
            'appeal_status'  => 'pending',
            'appealed_at'    => now(),
        ]);

        $adminIds = \App\Models\User::where('role', 'admin')->orWhere('is_super_admin', true)->pluck('id');
        foreach ($adminIds as $adminId) {
            \App\Models\BbNotification::send(
                $adminId,
                Auth::id(),
                'appeal_filed',
                '📩 ' . Auth::user()->name . ' has filed an appeal. Please review.',
                'report',
                $report->id
            );
        }

        return response()->json(['success' => true, 'message' => 'Your appeal has been submitted. An admin will review it shortly.']);
    }
}