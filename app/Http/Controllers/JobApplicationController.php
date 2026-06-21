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
    // আবেদন প্রত্যাহার (withdraw) — শুধু in-app
    // ==========================================
    public function withdraw($jobId)
    {
        $user = Auth::user();

        // আগে application বের করি, তারপর গার্ড চেক করি
        $application = JobApplication::where('user_id', $user->id)
            ->where('job_post_id', $jobId)
            ->firstOrFail();

        // External apply withdraw করা যায় না — Borobhai প্রতিষ্ঠানে কিছু submit করেনি, শুধু track
        if ($application->apply_method === 'external') {
            return response()->json([
                'success' => false,
                'message' => 'External applications cannot be withdrawn — they are managed by the company.',
            ], 422);
        }

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

        // search — job title বা company দিয়ে (archived job ও মিলবে)
        $search = trim((string) $request->input('q', ''));
        if ($search !== '') {
            $query->whereHas('jobPost', function ($q) use ($search) {
                $q->withTrashed()
                  ->where(function ($qq) use ($search) {
                      $qq->where('title', 'like', "%{$search}%")
                         ->orWhere('company', 'like', "%{$search}%");
                  });
            });
        }

        // filter
        $filter = $request->input('filter');
        if ($filter && in_array($filter, ['pending', 'reviewed', 'shortlisted', 'accepted', 'rejected'])) {
            $query->where('status', $filter);
        }

        // pagination (search/filter সহ পেজ লিংকে থাকবে)
        $applications = $query->latest('applied_at')
            ->paginate(10)
            ->withQueryString();

        // stats (সবসময় পুরো হিসাব — search/filter এর বাইরে)
        $all = JobApplication::where('user_id', $user->id)->get();
        $stats = [
            'total'       => $all->count(),
            'pending'     => $all->whereIn('status', ['pending', 'reviewed'])->count(),
            'shortlisted' => $all->whereIn('status', ['shortlisted', 'accepted'])->count(),
            'rejected'    => $all->where('status', 'rejected')->count(),
        ];

        return view('jobs.my-applications', compact('applications', 'stats', 'filter', 'search'));
    }

    // ==========================================
    // Alumni — নিজের job এ যারা আবেদন করেছে (search + filter + pagination)
    // ==========================================
    public function applicants(Request $request, $jobId)
    {
        $job = JobPost::withTrashed()->findOrFail($jobId);

        // শুধু job এর মালিক দেখতে পারবে
        if ($job->user_id !== Auth::id()) {
            abort(403);
        }

        // আবেদনকারীদের query (search/filter/pagination এর জন্য আলাদা)
        $query = $job->applications()->with('user');

        // search — আবেদনকারীর নাম বা ইমেইল দিয়ে
        $search = trim((string) $request->input('q', ''));
        if ($search !== '') {
            $query->where(function ($qq) use ($search) {
                $qq->where('applicant_name', 'like', "%{$search}%")
                   ->orWhere('applicant_email', 'like', "%{$search}%");
            });
        }

        // filter — status অনুযায়ী
        $filter = $request->input('status');
        if ($filter && in_array($filter, ['pending', 'reviewed', 'shortlisted', 'accepted', 'rejected'])) {
            $query->where('status', $filter);
        }

        $applicants = $query->latest('applied_at')->paginate(10)->withQueryString();

        // মোট সংখ্যা (filter এর বাইরে — header এ দেখানোর জন্য)
        $totalCount = $job->applications()->count();

        return view('jobs.applicants', compact('job', 'applicants', 'search', 'filter', 'totalCount'));
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

        // External apply এর status alumni বদলাতে পারবে না — প্রতিষ্ঠান নিজের সিস্টেমে handle করে
        if ($application->apply_method === 'external') {
            return response()->json([
                'success' => false,
                'message' => 'External applications are managed on the company site — status cannot be changed here.',
            ], 422);
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