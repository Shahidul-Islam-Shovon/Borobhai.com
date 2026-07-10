<?php

namespace App\Http\Middleware; 

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    // ✅ এখন একাধিক role চেক করা যাবে (role:alumni,teacher)
    if (!in_array($user->role, $roles)) {

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'alumni') {
            return redirect()->route('alumni.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    }

    return $next($request);
}
}
   
