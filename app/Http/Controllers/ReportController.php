<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Post;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function store(Request $request)
{
    $data = $request->validate([
        'type'       => 'required|in:post,job,user',
        'id'         => 'required|integer',
        'reason'     => 'required|string',
        'details'    => 'nullable|string|max:500',
        'mute_user'  => 'nullable|boolean',
        'hide_post'  => 'nullable|boolean', // ✅ নতুন — শুধু এই পোস্টটা হাইড
    ]);

    $meId = Auth::id();

    if ($data['type'] === 'user' && (int) $data['id'] === $meId) {
        return response()->json(['success' => false, 'message' => 'You cannot report yourself.']);
    }

    // ✅ Report করা পোস্ট/জব এর মালিক বের করা — dedupe এর জন্য দরকার
    $ownerId = match($data['type']) {
        'post' => Post::withTrashed()->find($data['id'])?->user_id,
        'job'  => JobPost::withTrashed()->find($data['id'])?->user_id,
        'user' => (int) $data['id'],
        default => null,
    };

    // ✅ ফেসবুক-স্টাইল dedupe: একই ইউজারের বিরুদ্ধে (পোস্ট হোক বা প্রোফাইল হোক) আগে থেকেই pending রিপোর্ট থাকলে
    // নতুন করে case না খুলে বিদ্যমান কেসে merge করা হবে (details এ যোগ হবে, নতুন row হবে ঠিকই কিন্তু admin dashboard এ গ্রুপড দেখাবে)
    $duplicateSameItem = Report::where('reporter_id', $meId)
        ->where('type', $data['type'])
        ->where('target_id', $data['id'])
        ->where('status', 'pending')
        ->exists();

    if ($duplicateSameItem) {
        return response()->json(['success' => false, 'message' => 'You have already reported this. Our team is reviewing it.']);
    }

    Report::create([
        'reporter_id' => $meId,
        'type'        => $data['type'],
        'target_id'   => $data['id'],
        'reason'      => $data['reason'],
        'details'     => $data['details'] ?? null,
        'status'      => 'pending',
    ]);

    $extraMsg = '';

    // ✅ Mute — সব পোস্ট, ৩০ দিন
    if (in_array($data['type'], ['post', 'job', 'user']) && $request->boolean('mute_user') && $ownerId && $ownerId !== $meId) {
        DB::table('muted_users')->updateOrInsert(
            ['user_id' => $meId, 'muted_user_id' => $ownerId],
            ['muted_until' => now()->addDays(30), 'updated_at' => now(), 'created_at' => now()]
        );
        $extraMsg .= " You won't see posts from this person for 30 days.";
    }

    // ✅ Hide — শুধু এই একটা পোস্ট
    if ($data['type'] === 'post' && $request->boolean('hide_post')) {
        DB::table('hidden_posts')->updateOrInsert(
            ['user_id' => $meId, 'post_id' => $data['id']],
            ['updated_at' => now(), 'created_at' => now()]
        );
        $extraMsg .= ' This post is now hidden from your feed.';
    }

    return response()->json(['success' => true, 'message' => 'Report submitted.' . $extraMsg]);
}
}