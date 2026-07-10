<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {

        // ✅ শুধু প্রকৃত suspension status গুলোই ব্লক করবে
        if (in_array($user->status, ['suspended_temp', 'suspended_perm'])) {

            if (
                $user->status === 'suspended_temp' &&
                $user->suspended_until &&
                Carbon::now()->greaterThan(Carbon::parse($user->suspended_until))
            ) {
                // টেম্পোরারি সাসপেনশন শেষ — auto active
                $user->status = 'active';
                $user->suspended_until = null;
                $user->save();

            } else {

                if ($user->status === 'suspended_perm') {
                    $errorMessage = "Access Denied ! You are Permanently Blocked.";
                } else {
                    $unlockTime = $user->suspended_until
                        ? Carbon::parse($user->suspended_until)->format('j F Y \a\t g.i A')
                        : 'N/A';
                    $errorMessage = "Access Blocked ! You are Temporarily Suspended By Admin. Unlocks at " . $unlockTime;
                }

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['errors' => ['email' => [$errorMessage]]], 422);
                }

                return redirect()->back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => $errorMessage]);
            }
        }
        // ✅ deactivated / pending_delete হলে এখানে কিছুই করা হচ্ছে না —
        // স্বাভাবিকভাবে authenticate() এ যাবে, ইউজার লগইন করতে পারবে (Facebook-এর মতো,
        // deactivated অ্যাকাউন্টে লগইন করলেই সেটা reactivate হওয়ার সুযোগ দেয়া উচিত)
    }

    $request->authenticate();
    $request->session()->regenerate();

    // ✅ বোনাস: deactivated/pending_delete ইউজার লগইন করলে status active করে দিন
    $authUser = Auth::user();
    if (in_array($authUser->status, ['deactivated', 'pending_delete'])) {
        $authUser->status = 'active';
        $authUser->save();
    }

    $redirectUrl = $authUser->role === 'admin' ? '/admin/dashboard' : '/dashboard';

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json(['success' => true, 'redirect' => url($redirectUrl)], 200);
    }

    return redirect()->intended($redirectUrl);
}

    /**
     * Destroy an authenticated session.
     */
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        // logout এ last_seen পিছিয়ে দিই না — heartbeat বন্ধ হলেই
        // স্বাভাবিকভাবে 'Active Xm ago' হবে, ২ ঘণ্টা পর Active Now থেকে গায়ব (Facebook-মত)
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}