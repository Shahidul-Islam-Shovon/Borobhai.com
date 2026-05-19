<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                // যদি সময় নির্দিষ্ট থাকে এবং সেই সময় পার হয়ে যায়, তবে একটিভ করে দেওয়া হবে
                if ($user->status === 'suspended_temp' && $user->suspended_until && Carbon::now()->greaterThan(Carbon::parse($user->suspended_until))) {
                    $user->status = 'active';
                    $user->suspended_until = null;
                    $user->save();
                    return $next($request);
                }

                // সময় বাকি থাকলে সেশন আউট করে এরর থ্রো করবে
                $unlockTime = $user->suspended_until ? Carbon::parse($user->suspended_until)->format('Y-m-d H:i:s') : 'N/A';
                
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $errorMessage = "Access Denied: You are Suspended By Admin. Unlocks at: " . $unlockTime;

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'errors' => ['email' => [$errorMessage]]
                    ], 422);
                }

                return redirect()->route('login')->with('suspended_error', $errorMessage);
            }
        }

        return $next($request);
    }
}