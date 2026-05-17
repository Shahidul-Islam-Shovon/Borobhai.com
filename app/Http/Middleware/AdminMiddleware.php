<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // If authenticated but not admin, redirect to their respective dashboard
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return redirect()->route('login')->with('error', 'Please login to access this page.');
    }

    private function redirectBasedOnRole(string $role): Response
    {
        if ($role === 'alumni') {
            return redirect()->route('alumni.dashboard');
        }
        return redirect()->route('dashboard');
    }
}