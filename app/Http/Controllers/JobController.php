<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\JobPost;
use App\Models\BbNotification;
use App\Models\Friendship;
use Barryvdh\DomPDF\Facade\Pdf;

class JobController extends Controller
{
    // ==========================================
    // নতুন job পোস্ট (বা এডিট) — শুধু alumni
    // ==========================================
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['alumni', 'teacher'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Only alumni and teachers can post jobs.'], 403);
        }

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'company'       => 'required|string|max:255',
            'location'      => 'nullable|string|max:255',
            'job_type'      => 'required|in:Full-time,Part-time,Remote,Internship,Contract,Freelance',
            'salary'        => 'nullable|string|max:100',
            'experience'    => 'nullable|string|max:100',
            'category'      => 'nullable|string|max:100',
            'deadline'      => 'nullable|date',
            'description'   => 'required|string',
            'requirements'  => 'nullable|string',
            'skills'        => 'nullable|string',
            'apply_type'    => 'nullable|in:link,email',
            'apply_value'   => 'nullable|string|max:500',
        ]);

        $jobId = $request->input('id');

        if ($jobId) {
            // ===== UPDATE =====
            $realId = JobPost::decodeHashid($jobId);
            $job = JobPost::withTrashed()->findOrFail($realId);
            if ($job->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            $job->update($validated);
            $job = $job->fresh()->load('user');
            $message = 'Job updated successfully!';
        } else {
            // ===== CREATE =====
            $validated['user_id'] = Auth::id();
            $job = JobPost::create($validated);
            $job->load('user');
            $message = 'Job posted successfully!';
        }

        return response()->json([
            'success'      => true,
            'message'      => $message,
            'job'          => $job,
            'job_id'       => $job->getRouteKey(),
            'html'         => view('partials.job-card', ['job' => $job, 'appliedJobIds' => []])->render(),
            'profile_html' => view('partials.myjob-item', ['job' => $job])->render(),
        ]);
    }

    // ==========================================
    // Job Portal — বিস্তারিত পেজ
    // ==========================================
    public function show($id)
    {
        self::cleanupExpired();

        $jobId = JobPost::decodeHashid($id);          // ⬅️ hashid → real id
        $job   = JobPost::with('user')->findOrFail($jobId);

        // applicant থাকলে job কখনো auto-delete হবে না (history রক্ষা),
        // শুধু applicant-শূন্য + অনেক পুরোনো (৩০ দিন) হলে পরিষ্কার হবে
        if ($job->should_auto_delete) {
            $job->delete();
            abort(404);
        }

        $myApplication = \App\Models\JobApplication::where('user_id', Auth::id())
            ->where('job_post_id', $job->id)->first();

        $hasApplied  = (bool) $myApplication;
        $myAppStatus = $myApplication->status ?? null;
        $myAppMethod = $myApplication->apply_method ?? null;

        return view('jobs.show', compact('job', 'hasApplied', 'myAppStatus', 'myAppMethod'));
    }

    // ==========================================
    // সব job এক পেজে (See all) — search + filter সহ
    // ==========================================
    public function all(Request $request)
    {
        self::cleanupExpired();

        $query = JobPost::with('user')
            ->withCount('applications')
            ->visible();

        $search = trim((string) $request->input('q', ''));
        if ($search !== '') {
            $query->where(function ($qq) use ($search) {
                $qq->where('title', 'like', "%{$search}%")
                   ->orWhere('company', 'like', "%{$search}%")
                   ->orWhere('location', 'like', "%{$search}%")
                   ->orWhere('skills', 'like', "%{$search}%");
            });
        }

        $type       = $request->input('type');
        $validTypes = ['Internship', 'Part-time', 'Full-time', 'Remote', 'Contract', 'Freelance'];
        if ($type && in_array($type, $validTypes)) {
            $query->where('job_type', $type);
        }

        $sort = $request->input('sort', 'default');
        if ($sort === 'newest') {
            $query->latest();
        } elseif ($sort === 'deadline') {
            $query->orderByRaw('deadline IS NULL, deadline ASC');
        } else {
            $query->orderByRaw("CASE
                    WHEN LOWER(job_type) LIKE '%intern%' THEN 1
                    WHEN LOWER(job_type) LIKE '%part%' THEN 2
                    ELSE 3 END")
                  ->latest();
        }

        $jobs = $query->paginate(12)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            $cardsHtml = view('jobs.partials.all-cards', compact('jobs', 'search', 'type'))->render();
            return response()->json([
                'success'    => true,
                'html'       => $cardsHtml,
                'pagination' => $jobs->hasPages() ? $jobs->links()->toHtml() : '',
                'total'      => $jobs->total(),
                'total_text' => $jobs->total() . ' ' . \Illuminate\Support\Str::plural('opening', $jobs->total()),
            ]);
        }

        return view('jobs.all', compact('jobs', 'search', 'type', 'sort'));
    }

    // ==========================================
    // job save/unsave টগল
    // ==========================================
    public function toggleSave($id)
    {
        $jobId    = JobPost::decodeHashid($id);                 // ⬅️ decode
        $job      = JobPost::withTrashed()->findOrFail($jobId);
        $user     = Auth::user();
        $existing = $job->savedByUsers()->where('user_id', $user->id)->exists();

        if ($existing) {
            $job->savedByUsers()->detach($user->id);
            $saved = false;
        } else {
            $job->savedByUsers()->attach($user->id);
            $saved = true;
        }

        return response()->json([
            'success' => true,
            'saved'   => $saved,
            'message' => $saved ? 'Job saved!' : 'Removed from saved',
        ]);
    }

    // ==========================================
    // edit এর জন্য job data (JSON)
    // ==========================================
    public function getJob($id)
    {
        $jobId = JobPost::decodeHashid($id);                    // ⬅️ decode
        $job   = JobPost::where('id', $jobId)->where('user_id', Auth::id())->firstOrFail();

        return response()->json([
            'success' => true,
            'job'     => [
                'id'           => $job->getRouteKey(),          // ⬅️ hashid (edit form এ raw id যাবে না)
                'title'        => $job->title,
                'company'      => $job->company,
                'location'     => $job->location,
                'job_type'     => $job->job_type,
                'experience'   => $job->experience,
                'salary'       => $job->salary,
                'category'     => $job->category,
                'deadline'     => $job->deadline ? $job->deadline->format('Y-m-d') : '',
                'description'  => $job->description,
                'requirements' => $job->requirements,
                'skills'       => $job->skills,
                'apply_type'   => $job->apply_type,
                'apply_value'  => $job->apply_value,
            ],
        ]);
    }

    // ==========================================
    // delete (নিজের job)
    // ==========================================
    public function destroy($id)
    {
        $jobId = JobPost::decodeHashid($id);                    // ⬅️ decode
        $job   = JobPost::where('id', $jobId)->where('user_id', Auth::id())->firstOrFail();
        $job->delete();
        return response()->json(['success' => true, 'message' => 'Job deleted']);
    }

    // ==========================================
    // মেয়াদোত্তীর্ণ job পরিষ্কার
    // শুধু applicant-শূন্য + deadline এর ৩০ দিন পার হওয়া job soft-delete হবে।
    // কেউ apply করে থাকলে job কখনো মুছবে না — job history রক্ষা করতে।
    // ==========================================
    public static function cleanupExpired()
    {
        JobPost::whereNotNull('deadline')
            ->whereRaw('DATE_ADD(deadline, INTERVAL 30 DAY) < ?', [now()->toDateString()])
            ->whereDoesntHave('applications')
            ->delete();
    }
    // ==========================================
    // Job Report PDF — শুধু owner। job details + applicant list সহ
    // ==========================================
    public function downloadReport($id)
    {
        $jobId = JobPost::decodeHashid($id);                       // hashid → real id
        $job   = JobPost::withTrashed()->with('user')->findOrFail($jobId);

        // শুধু owner — অন্য কেউ URL guess করলেও 403
        if ($job->user_id !== Auth::id()) {
            abort(403);
        }

        // applicant list (সব status)
        $applicants = $job->applications()->with('user')->latest('applied_at')->get();

        $data = [
            'job'        => $job,
            'applicants' => $applicants,
            'generated'  => now(),
        ];

        $pdf = Pdf::loadView('jobs.report-pdf', $data)
                  ->setPaper('a4', 'portrait');

        $safeTitle = \Illuminate\Support\Str::slug($job->title ?: 'job');
        $fileName  = 'borobhai-report-' . $safeTitle . '-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($fileName);
    }
}