<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;        // 👈 এখানে ১০০% সঠিক App\Models নিশ্চিত করা হলো
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

        return view('admin.dashboard', compact('counters', 'users', 'posts', 'circulars'));
    }

    // 📊 চার্টের ডাটা ইঞ্জিন
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

    // 👑 রোল আপডেট মেথড
    public function changeUserRole(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->role = $request->role;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User role updated successfully to ' . ucfirst($request->role)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // 👑 সাসপেনশন আপডেট মেথড
    public function updateSuspensionStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $action = $request->action; 

            if ($action === 'temp') {
                $user->status = 'suspended_temp';
                $user->suspended_until = Carbon::now()->addDays(7);
            } elseif ($action === 'perm') {
                $user->status = 'suspended_perm';
                $user->suspended_until = null;
            } else {
                $user->status = 'active';
                $user->suspended_until = null;
            }
            
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User status updated to ' . strtoupper($action) . ' successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage()
            ], 500);
        }
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