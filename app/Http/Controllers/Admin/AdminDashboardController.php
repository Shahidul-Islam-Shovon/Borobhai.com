<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;        
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

        // 📊 গত ৭ দিনের ট্রেন্ড ডেটা ক্যালকুলেট
        $months = [];
        $students = [];
        $alumni = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $months[] = $date->format('d M'); 

            $students[] = User::where('role', 'student')->whereDate('created_at', $date->format('Y-m-d'))->count();
            $alumni[] = User::where('role', 'alumni')->whereDate('created_at', $date->format('Y-m-d'))->count();
        }

        $chartData = [
            'months'   => $months,
            'students' => $students,
            'alumni'   => $alumni
        ];

        return view('admin.dashboard', compact('counters', 'users', 'posts', 'circulars', 'chartData'));
    }


    // 👑 +Admin, +Super এবং সাধারণ ইউজারে ব্যাক করার (Demote) মেথড
    public function manageAuthority(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access!'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type'    => 'required|in:student,alumni,admin,super' 
        ]);

        $targetUser = User::find($request->user_id);

        // 🛡️ .env ফাইল থেকে ডাইনামিক চিফ সুপার অ্যাডমিন ইমেইল প্রটেকশন
        $chiefEmail = env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com');

        if ($targetUser->email === $chiefEmail) {
            return response()->json([
                'success' => false,
                'message' => 'The Main System Administrator is fully secured. Role cannot be altered!'
            ], 422);
        }

        // 🟢 কন্ডিশন ১: অ্যাডমিন বা সুপার থেকে নরমাল স্টুডেন্ট/অ্যাল্যামনাই বানানো (Make Normal)
        if (in_array($request->type, ['student', 'alumni'])) {
            $targetUser->update([
                'role'           => $request->type, 
                'is_super_admin' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => "{$targetUser->name} কে সফলভাবে সাধারণ সদস্য গ্রুপে নামানো হয়েছে!"
            ]);
        }

        // 🔵 কন্ডিশন ২: সাধারণ অ্যাডমিন বানানো
        if ($request->type === 'admin') {
            $targetUser->update([
                'role'           => 'admin',
                'is_super_admin' => false
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "{$targetUser->name} কে সফলভাবে সাধারণ Admin করা হয়েছে!"
            ]);
        }

        // 👑 কন্ডিশন ৩: সুপার অ্যাডমিন বানানো (সর্বোচ্চ ২ জন লিমিট)
        if ($request->type === 'super') {
            $superAdminCount = User::where('is_super_admin', true)->count();

            if ($superAdminCount >= 2 && !$targetUser->isSuperAdmin()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'সিস্টেমে সর্বোচ্চ ২ জনের বেশি Super Admin থাকা সম্ভব নয়!'
                ], 422);
            }

            $targetUser->update([
                'role'           => 'admin', 
                'is_super_admin' => true
            ]);

            return response()->json([
                'success' => true, 
                'message' => "{$targetUser->name} এখন থেকে একজন Super Admin!"
            ]);
        }
    }


    
    // 🔄 ড্রপডাউন থেকে রোল চেঞ্জ করার মেথড
// 🔄 ড্রপডাউন থেকে রোল চেঞ্জ করার মেথড
public function changeRole(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'role'    => 'required|in:student,alumni,admin'
    ]);

    $user = User::find($request->user_id);

    // 🛡️ ১. ডাইনামিক চিফ সুপার অ্যাডমিন প্রটেকশন
    $chiefEmail = env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com');
    if ($user->email === $chiefEmail) {
        return response()->json([
            'success' => false,
            'message' => 'The Main System Administrator is fully secured. Role cannot be altered!'
        ], 422);
    }

    // 🛡️ ২. আপনার স্পেশাল লজিক: 
    // টার্গেট ইউজার যদি অলরেডি একজন Admin (বা সুপার) হয়, এবং লগইন করা ইউজার যদি নিজে Super Admin না হয়,
    // তবে সে কোনো কারেন্ট/আগের অ্যাডমিনের রোল পরিবর্তন করতে পারবে না!
    if ($user->role === 'admin' && !auth()->user()->isSuperAdmin()) {
        return response()->json([
            'success' => false,
            'message' => 'অ্যাকশন রিজেক্টেড! একজন সাধারণ Admin হয়ে আপনি অন্য কোনো বর্তমান বা পূর্বতন Admin-এর রোল পরিবর্তন করতে পারবেন না।'
        ], 403);
    }

    // রোল চেঞ্জ হয়ে স্টুডেন্ট বা অ্যাল্যামনাই হলে সে আর সুপার অ্যাডমিন থাকবে না
    $isSuper = ($request->role === 'admin') ? $user->is_super_admin : false;

    $user->update([
        'role'           => $request->role,
        'is_super_admin' => $isSuper
    ]);

    return response()->json([
        'success' => true,
        'message' => 'User role updated successfully!'
    ]);
}
       
    // 📊 চার্টের আলাদা এপিআই ডাটা ইঞ্জিন
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

    //  ম্যানুয়াল রোল আপডেট মেথড (যদি অন্য কোথাও কল করা থাকে)
    public function changeUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $chiefEmail = env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com');

        if ($user->email === $chiefEmail) {
            return response()->json([
                'success' => false,
                'message' => 'The Main System Administrator is fully secured. Role cannot be altered!'
            ], 403);
        }

        $user->role = $request->role;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully to ' . ucfirst($request->role)
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

public function updateSuspensionStatus(Request $request, $id)
    {
        // ১. আইডি দিয়ে ইউজার খোঁজা
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in database.'
            ], 404);
        }

        $action = $request->input('action');

        // ২. টেম্পোরারি ব্লকের জন্য কার্বন ফরম্যাট একদম স্ট্রিক্ট করে দেওয়া হলো
        if ($action === 'temp') {
            $user->status = 'suspended_temp';
            
            // 🎯 অনেক সময় সরাসরি অবজেক্ট ডাটাবেজ নিতে পারে না, তাই string ফরম্যাটে কাস্ট করা হলো
            $user->suspended_until = Carbon::now()->addDays(7)->toDateTimeString(); 
            
        } elseif ($action === 'perm') {
            $user->status = 'suspended_perm';
            $user->suspended_until = null;
             
        } elseif ($action === 'active') {
            $user->status = 'active';
            $user->suspended_until = null;
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid action.'], 400);
        }

        // ৩. ডিরেক্ট ডাটাবেজ আপডেট (যা কোনো মডেল কনস্ট্রেইন্ট বা মিউটেটরে আটকাবে না)
        // এটি ব্যবহার করলে মডেলের ভেতর কোনো হিডেন ঝামেলা থাকলেও সেটা বাইপাস হয়ে যাবে
        $updated = \DB::table('users')->where('id', $id)->update([
            'status' => $user->status,
            'suspended_until' => $user->suspended_until,
            'updated_at' => Carbon::now()
        ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully to ' . $action
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to write data to database.'
        ], 500);
    }


    // post and circular restore methods
    




































































}