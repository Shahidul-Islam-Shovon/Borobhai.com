<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Circular;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ১. কার্ড কাউন্টারস
        $counters = [
            'total_users'     => User::count(),
            'total_students'  => User::where('role', 'student')->count(),
            'total_alumni'    => User::where('role', 'alumni')->count(),
            'total_posts'     => Post::count(),
            'total_circulars' => Circular::count(),
        ];

        // ২. ইউজার লিস্ট
        $users = User::latest()->get();

        // ৩. পোস্ট লিস্ট মডারেশন
        $posts = Post::with('user')->latest()->get();

        // 👑 বাগ ফিক্স: জব সার্কুলার লিস্ট মডারেশন (যা ব্লেড ফাইলে খোঁজা হচ্ছিল)
        $circulars = Circular::with('user')->latest()->get();

        // 👑 জাদুকরী সমাধান: compact-এর ভেতর 'circulars' ভেরিয়েবলটি পাস করা হলো
        return view('admin.dashboard', compact('counters', 'users', 'posts', 'circulars'));
    }

    public function suspendUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $action = $request->input('action');

        if ($action === 'temp') {
            $user->status = 'suspended_temp';
            $user->suspended_until = now()->addDays(7);
        } elseif ($action === 'perm') {
            $user->status = 'suspended_perm';
            $user->suspended_until = null;
        } else {
            $user->status = 'active';
            $user->suspended_until = null;
        }
        
        $user->save();

        return response()->json(['success' => true, 'message' => 'User status updated successfully!']);
    }

    public function changeRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        return response()->json(['success' => true, 'message' => 'User role updated successfully!']);
    }
}