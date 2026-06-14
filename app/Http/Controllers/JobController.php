<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;

class JobController extends Controller
{
    // ==========================================
    // নতুন job পোস্ট (বা এডিট) — শুধু alumni
    // ==========================================
    public function store(Request $request)
    {
        // শুধু alumni job পোস্ট করতে পারবে
        if (Auth::user()->role !== 'alumni') {
            return response()->json(['success' => false, 'message' => 'Only alumni can post jobs.'], 403);
        }

        $request->merge([
            'deadline' => $request->deadline ?: null,
        ]);

        // apply_value কে type অনুযায়ী যাচাই (email হলে valid email, link হলে valid URL)
        $applyRule = $request->apply_type === 'email'
            ? 'required|email:rfc|max:255'
            : 'required|url|max:255';

        // link হলে http(s) না থাকলে যোগ করি (যাতে url validation পাস করে)
        if ($request->apply_type === 'link' && $request->filled('apply_value')) {
            $val = trim($request->apply_value);
            if (!preg_match('#^https?://#i', $val)) {
                $request->merge(['apply_value' => 'https://' . $val]);
            }
        }

        try {
            $data = $request->validate([
                'id'           => 'nullable|integer',
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
                'apply_value.email' => 'Please enter a valid email address.',
                'apply_value.url'   => 'Please enter a valid website link (e.g. https://...).',
                'deadline.after_or_equal' => 'Deadline must be today or a future date.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        // এডিট না নতুন
        if ($request->filled('id')) {
            $job = JobPost::where('id', $request->id)->where('user_id', Auth::id())->firstOrFail();
        } else {
            $job = new JobPost();
            $job->user_id = Auth::id();
        }

        $job->fill($data);
        $job->status = 'active';
        $job->save();

        $fresh = $job->fresh();
        $fresh->load('user');

        // ফিডের জন্য job card HTML
        $html = view('partials.job-card', ['job' => $fresh])->render();

        // profile Job Posts সেকশনের item HTML
        $profileHtml = view('partials.myjob-item', ['job' => $fresh])->render();

        return response()->json([
            'success'      => true,
            'message'      => 'Job posted successfully!',
            'job_id'       => $job->id,
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

        $job = JobPost::with('user')->findOrFail($id);

        if ($job->should_auto_delete) {
            $job->delete();
            abort(404);
        }

        return view('jobs.show', compact('job'));
    }

    // ==========================================
    // সব job এক পেজে (See all)
    // ==========================================
    public function all(Request $request)
    {
        self::cleanupExpired();

        $jobs = JobPost::with('user')
                ->visible()
                ->orderByRaw("CASE
                        WHEN LOWER(job_type) LIKE '%intern%' THEN 1
                        WHEN LOWER(job_type) LIKE '%part%' THEN 2
                        ELSE 3 END")
                ->latest()
                ->paginate(12);

        return view('jobs.all', compact('jobs'));
    }

    // ==========================================
    // job save/unsave টগল
    // ==========================================
    public function toggleSave($id)
    {
        $job = JobPost::findOrFail($id);
        $user = Auth::user();

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
        $job = JobPost::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return response()->json([
            'success' => true,
            'job' => [
                'id'           => $job->id,
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
        $job = JobPost::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $job->delete();
        return response()->json(['success' => true, 'message' => 'Job deleted']);
    }

    // ==========================================
    // মেয়াদোত্তীর্ণ (৫ দিন গ্রেস শেষ) job গুলো পরিষ্কার
    // feed/portal লোডের সময় ডাকা হবে (cron ছাড়াই auto-clean)
    // ==========================================
    public static function cleanupExpired()
    {
        // deadline + 5 দিন < আজ → ডিলিট
        JobPost::whereNotNull('deadline')
            ->whereRaw('DATE_ADD(deadline, INTERVAL 5 DAY) < ?', [now()->toDateString()])
            ->delete();
    }
}