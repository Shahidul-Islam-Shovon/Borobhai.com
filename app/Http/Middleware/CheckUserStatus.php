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
        if (!Auth::check()) return $next($request);

        $user = Auth::user();
        $fresh = \DB::table('users')->where('id', $user->id)->first();
        if (!$fresh) return $next($request);

        // ✅ Temp suspension
        if ($fresh->status === 'suspended_temp') {
            $dateText = $fresh->suspended_until
                ? Carbon::parse($fresh->suspended_until)->format('d M Y \a\t g:i a')
                : Carbon::now()->addDays(7)->format('d M Y \a\t g:i a');

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', "Access Denied! You are temporarily suspended until: {$dateText}");
        }

        // ✅ Permanent suspension
        if ($fresh->status === 'suspended_perm') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', 'Access Denied! Your account has been permanently suspended.');
        }

        // ✅ Pending delete — ৩০ দিনের মধ্যে login করলে recovery page
        if ($fresh->status === 'pending_delete') {
            $deletionDate = Carbon::parse($fresh->deletion_requested_at)->addDays(30);

            if (Carbon::now()->gt($deletionDate)) {
                // ৩০ দিন পেরিয়ে গেছে — permanently delete
                \DB::table('users')->where('id', $fresh->id)->delete();
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->with('error', 'Your account has been permanently deleted.');
            }

            // Recovery page ছাড়া অন্য সব route block করো
            if (!$request->routeIs('account.recovery') && !$request->routeIs('account.recover')) {
                return redirect()->route('account.recovery');
            }
        }

        // ✅ Deactivated — login করলে reactivate page
        if ($fresh->status === 'deactivated') {
            if (!$request->routeIs('account.reactivate') && !$request->routeIs('account.reactivate.post')) {
                return redirect()->route('account.reactivate');
            }
        }

        return $next($request);
    }
}