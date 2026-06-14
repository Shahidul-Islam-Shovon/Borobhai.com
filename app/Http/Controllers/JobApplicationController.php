<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\JobPost;
use App\Models\JobApplication;

class JobApplicationController extends Controller
{
    // ==========================================
    // job এ আবেদন (in-app বা external track)
    // ==========================================
    public function apply(Request $request, $jobId)
    {
        $job = JobPost::findOrFail($jobId);
        $user = Auth::user();

        // নিজের পোস্ট করা job এ নিজে apply করা যাবে না
        if ($job->user_id === $user->id) {
            return response()->json(['success' => false, 'message' => 'You cannot apply to your own job posting.'], 403);
        }

        // ডেডলাইন শেষ হলে আবেদন বন্ধ
        if ($job->is_expired) {
            return response()->json(['success' => false, 'message' => 'The deadline for this job has passed.'], 422);
        }

        // আগে আবেদন করেছে কিনা
        $already = JobApplication::where('user_id', $user->id)->where('job_post_id', $job->id)->first();
        if ($already) {
            return response()->json(['success' => false, 'message' => 'You have already applied to this job.'], 409);
        }

        $method = $request->input('apply_method', 'inapp');

        // external হলে শুধু track — বিস্তারিত লাগবে না
        if ($method === 'external') {
            $application = JobApplication::create([
                'user_id'         => $user->id,
                'job_post_id'     => $job->id,
                'applicant_name'  => $user->name,
                'applicant_email' => $user->email,
                'apply_method'    => 'external',
                'status'          => 'pending',
                'applied_at'      => now(),
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Application tracked! Continue on the company site.',
                'method'   => 'external',
                'app_id'   => $application->id,
            ]);
        }

        // in-app — পূর্ণ validation
        try {
            $data = $request->validate([
                'applicant_name'  => 'required|string|max:255',
                'applicant_email' => 'required|email:rfc|max:255',
                'phone'           => 'nullable|string|max:30',
                'cover_note'      => 'nullable|string|max:2000',
                'resume'          => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB
            ], [
                'resume.mimes' => 'Resume must be a PDF or Word file.',
                'resume.max'   => 'Resume cannot exceed 5MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        $application = JobApplication::create([
            'user_id'         => $user->id,
            'job_post_id'     => $job->id,
            'applicant_name'  => $data['applicant_name'],
            'applicant_email' => $data['applicant_email'],
            'phone'           => $data['phone'] ?? null,
            'cover_note'      => $data['cover_note'] ?? null,
            'resume_path'     => $resumePath,
            'apply_method'    => 'inapp',
            'status'          => 'pending',
            'applied_at'      => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully!',
            'method'  => 'inapp',
            'app_id'  => $application->id,
        ]);
    }

    // ==========================================
    // আবেদন প্রত্যাহার (withdraw)
    // ==========================================
    public function withdraw($jobId)
    {
        $user = Auth::user();
        $application = JobApplication::where('user_id', $user->id)
            ->where('job_post_id', $jobId)
            ->firstOrFail();

        // alumni এগিয়ে নিলে (shortlisted/accepted/rejected) আর withdraw নয়
        if (!in_array($application->status, ['pending', 'reviewed'])) {
            return response()->json([
                'success' => false,
                'message' => 'This application can no longer be withdrawn.',
            ], 422);
        }

        // resume মুছে দাও
        if ($application->resume_path && Storage::disk('public')->exists($application->resume_path)) {
            Storage::disk('public')->delete($application->resume_path);
        }

        $application->delete();

        return response()->json(['success' => true, 'message' => 'Application withdrawn.']);
    }

    // ==========================================
    // Student — নিজের সব আবেদন (Job History পেজ)
    // ==========================================
    public function myApplications(Request $request)
    {
        $user = Auth::user();

        $query = JobApplication::with(['jobPost' => function ($q) {
                $q->withTrashed(); // auto-deleted (archived) job ও দেখাবে
            }])
            ->where('user_id', $user->id);

        // filter
        $filter = $request->input('filter');
        if ($filter && in_array($filter, ['pending', 'reviewed', 'shortlisted', 'accepted', 'rejected'])) {
            $query->where('status', $filter);
        }

        $applications = $query->latest('applied_at')->get();

        // stats
        $all = JobApplication::where('user_id', $user->id)->get();
        $stats = [
            'total'       => $all->count(),
            'pending'     => $all->whereIn('status', ['pending', 'reviewed'])->count(),
            'shortlisted' => $all->whereIn('status', ['shortlisted', 'accepted'])->count(),
            'rejected'    => $all->where('status', 'rejected')->count(),
        ];

        return view('jobs.my-applications', compact('applications', 'stats', 'filter'));
    }

    // ==========================================
    // Alumni — নিজের job এ যারা আবেদন করেছে
    // ==========================================
    public function applicants($jobId)
    {
        $job = JobPost::withTrashed()->with(['applications.user'])->findOrFail($jobId);

        // শুধু job এর মালিক দেখতে পারবে
        if ($job->user_id !== Auth::id()) {
            abort(403);
        }

        return view('jobs.applicants', compact('job'));
    }

    // ==========================================
    // Alumni — আবেদনের status পরিবর্তন
    // ==========================================
    public function updateStatus(Request $request, $appId)
    {
        $application = JobApplication::with(['jobPost' => fn($q) => $q->withTrashed()])->findOrFail($appId);

        // শুধু job এর মালিক
        if (!$application->jobPost || $application->jobPost->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,accepted,rejected',
        ]);

        $application->status = $data['status'];
        $application->save();

        return response()->json([
            'success'     => true,
            'message'     => 'Status updated.',
            'status'      => $application->status,
            'status_meta' => $application->status_meta,
        ]);
    }
}