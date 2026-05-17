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
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    // ইউজার যদি লগইন করা থাকে এবং তার রোল যদি 'admin' হয়, তবেই সামনে যেতে দাও
    if (Auth::check() && Auth::user()->role === 'admin') {
        return $next($request);
    }

    // অ্যাডমিন না হলে তাকে হোমপেজে রিডাইরেক্ট করো এবং একটি মেসেজ দাও
    return redirect('/')->with('error', 'Alert !! You Do Not Have Admin Access!');
}
}
