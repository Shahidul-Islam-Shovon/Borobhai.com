<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\JobPost;
use App\Models\BbNotification;
use App\Models\Friendship;

class JobController extends Controller
{
    // ==========================================
    // নতুন job পোস্ট (বা এডিট) — শুধু alumni
    // ==========================================
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'alumni') {
            return response()->json(['success' => false, 'message' => 'Only alumni can post jobs.'], 403);
        }

        $request->merge([
            'deadline' => $request->deadline ?: null,
        ]);

        $applyRule = $request->apply_type === 'email'
            ? 'required|email:rfc|max:255'
            : 'required|url|max:255';

        if ($request->apply_type === 'link' && $request->filled('apply_value')) {
            $val = trim($request->apply_value);
            if (!preg_match('#^https?://#i', $val)) {
                $request->merge(['apply_value' => 'https://' . $val]);
            }
        }

        try {
            $data = $request->validate([
                'id'           => 'nullable|string',   // ⬅️ এখন hashid string (raw int নয়)
                'title'        => 'required|string|max:255',
                'company'      => 'required|string|max:255',
                'location'     => 'nullable|string|max:255',
                'job_type'     => 'required|string|max:50',
                'experience'   => 'nullable|string|max:100',
                'salary'       => 'nullable|string|max:100',
                'description'  => 'required|string|max:5000',
                'requirements' => 'nullable|string|max:5000',
                'skills'       => 'nullable|string|max:500',
                'apply_type'   => 'required|in:link,email',
                'apply_value'  => $applyRule,
                'deadline'     => 'nullable|date|after_or_equal:today',
                'category'     => 'nullable|string|max:100',
            ], [
                'apply_value.email'       => 'Please enter a valid email address.',
                'apply_value.url'         => 'Please enter a valid website link (e.g. https://...).',
                'deadline.after_or_equal' => 'Deadline must be today or a future date.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        $isNew = !$request->filled('id');

        if (!$isNew) {
            // edit — hidden field এর hashid decode করে নিজের job খুঁজি
            $realId = JobPost::decodeHashid($request->id);
            $job = JobPost::where('id', $realId)->where('user_id', Auth::id())->firstOrFail();
        } else {
            $job = new JobPost();
            $job->user_id = Auth::id();
        }

        $job->fill($data);          // 'id' fillable নয় → নিরাপদে উপেক্ষা হবে
        $job->status = 'active';
        $job->save();

        if ($isNew) {
            $friendIds = Friendship::friendIds(Auth::id());
            foreach ($friendIds as $friendId) {
                BbNotification::send(
                    $friendId,
                    Auth::id(),
                    'new_job',
                    Auth::user()->name . ' posted a new job: ' . Str::limit($job->title, 50),
                    'job',
                    $job->id
                );
            }
        }

        $fresh = $job->fresh();
        $fresh->load('user');

        $html        = view('partials.job-card', ['job' => $fresh])->render();
        $profileHtml = view('partials.myjob-item', ['job' => $fresh])->render();

        return response()->json([
            'success'      => true,
            'message'      => 'Job posted successfully!',
            'job_id'       => $job->getRouteKey(),   // ⬅️ hashid (DOM card targeting consistent থাকবে)
            'html'         => $html,
            'profile_html' => $profileHtml,
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

        $query = JobPost::with('user')->visible();

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
    // ==========================================
    public static function cleanupExpired()
    {
        JobPost::whereNotNull('deadline')
            ->whereRaw('DATE_ADD(deadline, INTERVAL 5 DAY) < ?', [now()->toDateString()])
            ->delete();
    }
}