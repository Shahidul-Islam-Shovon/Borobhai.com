<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class MutedUserController extends Controller
{
    // মিউট করা ইউজারদের লিস্ট
    public function index(Request $request)
    {
        $meId = Auth::id();

        $muted = DB::table('muted_users')
            ->where('user_id', $meId)
            ->where(function ($q) {
                $q->whereNull('muted_until')->orWhere('muted_until', '>=', now());
            })
            ->orderByDesc('created_at')
            ->get();

        $mutedUserIds = $muted->pluck('muted_user_id')->toArray();

        $users = User::whereIn('id', $mutedUserIds)
            ->select('id', 'name', 'profile_picture', 'role')
            ->get()
            ->keyBy('id');

        $list = $muted->map(function ($m) use ($users) {
            $u = $users->get($m->muted_user_id);
            if (!$u) return null; // ইউজার ডিলিট হয়ে গেলে স্কিপ
            return [
                'id'              => $u->id,
                'name'            => $u->name,
                'profile_picture' => $u->profile_picture,
                'role'            => $u->role,
                'muted_until'     => $m->muted_until,
            ];
        })->filter()->values();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'muted' => $list]);
        }

        return view('profile.muted', ['mutedUsers' => $list]);
    }

    // নির্দিষ্ট ইউজারকে আনমিউট
    public function unmute($userId)
    {
        $meId = Auth::id();

        $deleted = DB::table('muted_users')
            ->where('user_id', $meId)
            ->where('muted_user_id', $userId)
            ->delete();

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'This user was not muted.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Unmuted! You will see their posts again.']);
    }
}