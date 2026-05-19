<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // ১. ইমেইল দিয়ে ইউজার অবজেক্ট খুঁজে বের করা
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // ২. পাসওয়ার্ড চেক করা
            if (Hash::check($request->password, $user->password)) {
                
                // 🛡️ ৩. ইউজার যদি একটিভ না থাকে (সাসপেন্ডেড থাকে)
                if ($user->status !== 'active') {
    
                // টেম্পোরারি সাসপেনশনের ডেট পার হয়ে গেছে কি না চেক
                if ($user->status === 'suspended_temp' && $user->suspended_until && Carbon::now()->greaterThan(Carbon::parse($user->suspended_until))) {
                    $user->status = 'active';
                    $user->suspended_until = null;
                    $user->save();
                } else {
                    // 👑 একদম নিখুঁত কাস্টম ডেট ফরম্যাট: 26 May 2026 at 09:50 AM
                    if ($user->status === 'suspended_perm') {
                        $errorMessage = "Access Denied: Your account has been permanently suspended.";
                    } else {
                        // 'j M Y \a\t g:i A' দিয়ে দিন, মাস, বছর এবং 'at' সহ ১২ ঘণ্টার সময় সেট করা হয়েছে
                        $unlockTime = $user->suspended_until ? Carbon::parse($user->suspended_until)->format('j M Y \a\t g:i A') : 'N/A';
                        $errorMessage = "Access Denied: Temporarily suspended. Unlocks at: " . $unlockTime;
                    }

                    // AJAX বা Fetch রিকোয়েস্টের জন্য রেসপন্স
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'errors' => ['email' => [$errorMessage]]
                        ], 422);
                    }

                    return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
                        'email' => $errorMessage,
                    ]);
                }
            }
                
               
            }
        }

        // ৪. ইউজার একটিভ থাকলে ল্যারাভেল ব্রিজের নরমাল অথেন্টিকেশন প্রসেস চলবে
        $request->authenticate();

        $request->session()->regenerate();

        // সফল লগইনের পর রোল অনুযায়ী সঠিক রিডাইরেক্ট ইউআরএল সেট করা
        $redirectUrl = Auth::user()->role === 'admin' ? '/admin/dashboard' : '/dashboard';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => url($redirectUrl)
            ], 200);
        }

        return redirect()->intended($redirectUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}