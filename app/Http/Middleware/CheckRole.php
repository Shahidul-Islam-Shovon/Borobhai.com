<?php

namespace App\Http\Middleware; 

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 🔄 ইউজার যদি লগইন থাকে কিন্তু তার কারেন্ট রোল যদি রাউটের রোলের সাথে না মেলে
        if ($user->role !== $role) {
            
            // ইউজার যদি আসলে এডমিন হয়, কিন্তু ভুল করে স্টুডেন্ট বা অন্য রাউটে ঢুকে পড়ে
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } 
            // ইউজার যদি এলামনাই হয়
            elseif ($user->role === 'alumni') {
                return redirect()->route('alumni.dashboard');
            } 
            // ইউজার যদি স্টুডেন্ট হয়
            else {
                return redirect()->route('student.dashboard');
            }
        }

        return $next($request);
    }
}
   
