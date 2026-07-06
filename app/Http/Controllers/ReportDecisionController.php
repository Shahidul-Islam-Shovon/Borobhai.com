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
        $videos       = [];
        $isDeleted    = false;
        $contentType  = $report->type;
        $jobLink      = null;
        $likesCount   = 0;
        $commentsCount = 0;

        if ($report->type === 'post') {
            $post = \App\Models\Post::withTrashed()->withCount(['likes','comments'])->find($report->target_id);
            $targetUserId = $post?->user_id;
            $content      = $post?->content;
            $images       = $post?->images ?? [];
            $isDeleted    = $post?->trashed() ?? false;
            $likesCount    = $post?->likes_count ?? 0;
            $commentsCount = $post?->comments_count ?? 0;

            if (!empty($post?->video) && $post->video !== 'null') {
                $videos = is_array($post->video) ? $post->video : (json_decode($post->video, true) ?: [$post->video]);
            }
        } elseif ($report->type === 'job') {
            $job = \App\Models\JobPost::withTrashed()->find($report->target_id);
            $targetUserId = $job?->user_id;
            $content      = $job ? ($job->title . ' — ' . $job->company) : null;
            $isDeleted    = $job?->trashed() ?? false;
            $jobLink      = ($job && !$isDeleted) ? route('jobs.show', $job) : null;
        } elseif ($report->type === 'user') {
            $targetUserId = $report->target_id;
        }

        $viewer = Auth::user();
        $isAdminViewer = $viewer->role === 'admin' || $viewer->is_super_admin;

        if ($targetUserId !== Auth::id() && !$isAdminViewer) {
            abort(403);
        }

        return view('reports.decision', compact(
            'report', 'content', 'images', 'videos', 'isDeleted', 'contentType',
            'jobLink', 'likesCount', 'commentsCount', 'isAdminViewer'
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