<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    public function store(LoginRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
{
    // ১. ব্রিজের ডিফল্ট অথেন্টিকেশন লজিক (ইমেইল-পাসওয়ার্ড চেক)
    $request->authenticate();

    $request->session()->regenerate();

    // ২. লগইন করা ইউজারের অবজেক্ট নেওয়া
    $user = Auth::user();

    // ৩. ইউজারের রোল অনুযায়ী ডাইনামিক রিডাইরেক্ট পাথ সেট করা
    $redirectUrl = '/dashboard'; // সেফ ফলব্যাক ইউআরএল

    if ($user->role === 'admin') {
        $redirectUrl = route('admin.dashboard');
    } elseif ($user->role === 'student') {
        $redirectUrl = route('student.dashboard'); // আপনার স্টুডেন্ট ড্যাশবোর্ড রাউট নেম
    } elseif ($user->role === 'alumni') {
        $redirectUrl = route('alumni.dashboard'); // আপনার এলামনাই ড্যাশবোর্ড রাউট নেম
    }

    // 🔥 আমাদের মডার্ন এজাক্স চেক (লগইনের পর সঠিক ড্যাশবোর্ডে পাঠাবে)
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Login successful! Redirecting to your dashboard...',
            'redirect' => $redirectUrl
        ]);
    }

    return redirect()->intended($redirectUrl);
}
     

    /**
     * Destroy an authenticated session (Logout Logic).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}