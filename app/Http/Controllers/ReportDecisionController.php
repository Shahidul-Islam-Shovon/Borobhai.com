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

        $targetUserId = null;
        $content      = null;
        $images       = [];
        $isDeleted    = false;
        $contentType  = $report->type;

        if ($report->type === 'post') {
            $post = \App\Models\Post::withTrashed()->find($report->target_id);
            $targetUserId = $post?->user_id;
            $content      = $post?->content;
            $images       = $post?->images ?? [];
            $isDeleted    = $post?->trashed() ?? false;
        } elseif ($report->type === 'job') {
            $job = \App\Models\JobPost::withTrashed()->find($report->target_id);
            $targetUserId = $job?->user_id;
            $content      = $job ? ($job->title . ' — ' . $job->company) : null;
            $isDeleted    = $job?->trashed() ?? false;
        } elseif ($report->type === 'user') {
            $targetUserId = $report->target_id;
        }

        // শুধু নিজের রিপোর্টের ডিসিশন দেখতে পারবে (বা admin)
        $user = Auth::user();
        if ($targetUserId !== Auth::id() && !($user->role === 'admin' || $user->is_super_admin)) {
            abort(403);
        }

        return view('reports.decision', compact('report', 'content', 'images', 'isDeleted', 'contentType'));
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