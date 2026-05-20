<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;        // 👈 App\Models নিশ্চিত করা হলো
use App\Models\Post;        
use App\Models\Circular;    
use Carbon\Carbon;
use Exception;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $counters = [
            'total_users'     => User::count(),
            'total_students'  => User::where('role', 'student')->count(),
            'total_alumni'    => User::where('role', 'alumni')->count(),
            'total_posts'     => Post::count(),
            'total_circulars' => Circular::count(),
        ];

        $users = User::latest()->get();
        $posts = Post::with('user')->latest()->get();
        $circulars = Circular::with('user')->latest()->get();

        // 📊 ব্লেড ফাইলের চার্টের রিকোয়ারমেন্ট অনুযায়ী গত ৭ দিনের ট্রেন্ড ডেটা এখানেই ক্যালকুলেট করা হলো
        $months = [];
        $students = [];
        $alumni = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $months[] = $date->format('d M'); // যেমন: "20 May"

            // প্রতিদিন কতজন স্টুডেন্ট এবং অ্যাল্যামনাই জয়েন করেছে তা কাউন্ট করা
            $students[] = User::where('role', 'student')->whereDate('created_at', $date->format('Y-m-d'))->count();
            $alumni[] = User::where('role', 'alumni')->whereDate('created_at', $date->format('Y-m-d'))->count();
        }

        $chartData = [
            'months'   => $months,
            'students' => $students,
            'alumni'   => $alumni
        ];

        // 🚀 এখন compact-এর ভেতরে $chartData পাস করে দেওয়া হলো, যা ব্লেডের সাথে ১০০% ম্যাচ করবে
        return view('admin.dashboard', compact('counters', 'users', 'posts', 'circulars', 'chartData'));
    }


    public function manageAuthority(Request $request)
    {
        // 🛡️ সিকিউরিটি লেয়ার ১: রিকোয়েস্টকারী নিজে সুপার অ্যাডমিন কিনা চেক
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access! Only Super Admin can perform this action.'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:admin,super'
        ]);

        $targetUser = User::find($request->user_id);

        // 🟢 কন্ডিশন ১: সাধারণ অ্যাডমিন বানানো (আনলিমিটেড)
        if ($request->type === 'admin') {
            $targetUser->update([
                'role' => 'admin',
                'is_super_admin' => false // যদি সে আগে সুপার অ্যাডমিন থেকে থাকে, তবে ডাউনগ্রেড হবে
            ]);
            return response()->json(['success' => true, 'message' => "{$targetUser->name} কে সফলভাবে সাধারণ Admin বানানো হয়েছে!"]);
        }

        // 👑 কন্ডিশন ২: সুপার অ্যাডমিন বানানো (সর্বোচ্চ ২ জন লিমিট)
        if ($request->type === 'super') {
            // অলরেডি ডেটাবেজে কয়জন সুপার অ্যাডমিন আছে তা কাউন্ট করা
            $superAdminCount = User::where('is_super_admin', true)->count();

            // যদি অলরেডি ২ জন থাকে এবং টার্গেট ইউজার নতুন কেউ হয়, তবেই ওয়ার্নিং দেবে
            if ($superAdminCount >= 2 && !$targetUser->isSuperAdmin()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'সিস্টেমে সর্বোচ্চ ২ জনের বেশি Super Admin থাকা সম্ভব নয়! নতুন কাউকে বানাতে হলে আগের একজনকে সাধারণ অ্যাডমিন বানিয়ে সিট খালি করুন।'
                ], 422); // 422 Unprocessable Entity
            }

            // লজিক ওকে থাকলে কারেন্ট সুপার অ্যাডমিনও সুপার থাকবে এবং নতুনজনও সুপার হবে
            $targetUser->update([
                'role' => 'admin', // ড্যাশবোর্ড পারমিশনের জন্য রোল 'admin' থাকবে
                'is_super_admin' => true
            ]);

            return response()->json(['success' => true, 'message' => "অভিনন্দন! {$targetUser->name} এখন থেকে সিস্টেমে একজন নতুন Super Admin হিসেবে নিযুক্ত হয়েছেন।"]);
        }
    }

    // 📊 চার্টের আলাদা এপিআই ডাটা ইঞ্জিন (ভবিষ্যতে লাগলে ব্যবহার করতে পারবেন)
    public function getAnalyticsData()
    {
        $labels = [];
        $userData = [];
        $postData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');

            $userData[] = User::whereDate('created_at', $date->format('Y-m-d'))->count();
            $postData[] = Post::whereDate('created_at', $date->format('Y-m-d'))->count();
        }

        return response()->json([
            'labels' => $labels,
            'users'  => $userData,
            'posts'  => $postData
        ]);
    }

    //  রোল আপডেট মেথড
    public function changeUserRole(Request $request, $id)
    {
        // মেইন সুপার অ্যাডমিনের আইডি যদি ১ হয়
        if ($id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'The Main System Administrator is fully secured. Role cannot be altered!'
            ], 403);
        }

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully to ' . ucfirst($request->role)
        ]);
    }
        
    //  সাসপেনশন আপডেট মেথড
    public function updateSuspensionStatus(Request $request, $id)
    {
        if ($id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'The Main System Administrator is fully secured. Action denied!'
            ], 403);
        }

        $user = User::findOrFail($id);
        $action = $request->action;

        if ($action === 'temp') {
            $user->status = 'suspended_temp';
            $user->suspended_until = now()->addDays(7);
        } elseif ($action === 'perm') {
            $user->status = 'suspended_perm';
        } elseif ($action === 'active') {
            $user->status = 'active';
            $user->suspended_until = null;
        }
        
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.'
        ]);
    }
        
    // পোস্ট ডিলিট
    public function deletePost($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post has been permanently deleted.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // সার্কুলার ডিলিট
    public function deleteCircular($id)
    {
        try {
            $circular = Circular::findOrFail($id);
            $circular->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job circular removed successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}