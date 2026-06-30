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
        // ইউজার খুঁজে বের করা
        $user = User::where('email', $request->email)->first();

        if ($user) {

            // পাসওয়ার্ড মিলানো
            if (Hash::check($request->password, $user->password)) {

                // যদি ইউজার active না থাকে
                if ($user->status !== 'active') {

                    // টেম্পোরারি সাসপেনশন শেষ হয়ে গেলে auto active
                    if (
                        $user->status === 'suspended_temp' &&
                        $user->suspended_until &&
                        Carbon::now()->greaterThan(
                            Carbon::parse($user->suspended_until)
                        )
                    ) {

                        $user->status = 'active';
                        $user->suspended_until = null;
                        $user->save();

                    } else {

                        // Permanent Suspend
                        if ($user->status === 'suspended_perm') {

                            $errorMessage = "Access Denied ! You are Permanently Blocked.";

                        } else {

                            // Temporary Suspend Time Format
                            $unlockTime = $user->suspended_until
                                ? Carbon::parse($user->suspended_until)
                                    ->format('j F Y \a\t g.i A')
                                : 'N/A';

                            $errorMessage =
                                "Access Blocked ! You are Temporarily Suspended By Admin. Unlocks at " .
                                $unlockTime;
                        }

                        // AJAX Request
                        if ($request->wantsJson() || $request->ajax()) {

                            return response()->json([
                                'errors' => [
                                    'email' => [$errorMessage]
                                ]
                            ], 422);
                        }

                        // Normal Request
                        return redirect()
                            ->back()
                            ->withInput($request->only('email', 'remember'))
                            ->withErrors([
                                'email' => $errorMessage,
                            ]);
                    }
                }
            }
        }

        // Normal Laravel Breeze Authentication
        $request->authenticate();

        $request->session()->regenerate();

        // Role Based Redirect
        $redirectUrl = Auth::user()->role === 'admin'
            ? '/admin/dashboard'
            : '/dashboard';

        // AJAX Login Response
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
    public function destroy(Request $request)
    {

    // logout করলেই সাথে সাথে offline (Active Now থেকে সরে যাবে)
        if (Auth::id()) {
            \DB::table('users')->where('id', Auth::id())
            ->update(['last_seen' => now()->subHours(5)]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}