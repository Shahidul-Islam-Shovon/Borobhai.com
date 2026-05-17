<?php

namespace App\Http\Middleware; 

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
   public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== $role) {
            $userRole = Auth::user()->role;
            
            // ডাবল রিডাইরেক্ট এড়াতে সরাসরি যার যার নির্দিষ্ট ড্যাশবোর্ড রাউটে ওয়ার্নিং সহ রিডাইরেক্ট
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard')->with('warning', 'Unauthorized Access! You Are Not Allowed To Access Other Dashboard !!');
            } elseif ($userRole === 'alumni') {
                return redirect()->route('alumni.dashboard')->with('warning', 'Unauthorized Access! You Are Not Allowed To Access Other Dashboard !!');
            } else {
                return redirect()->route('student.dashboard')->with('warning', 'Unauthorized Access! You Are Not Allowed To Access Other Dashboard !!');
            }
        }

        return $next($request);
    }
}