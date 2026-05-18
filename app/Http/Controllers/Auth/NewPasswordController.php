<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // ব্রিজের নিজস্ব পাসওয়ার্ড রিসেট করার মেকানিজম
    $status = Password::broker()->reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    if ($status == Password::PASSWORD_RESET) {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __($status), // "Your password has been reset."
                'redirect' => route('login') // রিসেট সফল হলে লগইন পেজে যাবে
            ]);
        }

        return redirect()->route('login')->with('status', __($status));
    }

    // ফেইল করলে (যেমন টোকেন এক্সপায়ারড বা ইমেইল ম্যাচ না করলে)
    if ($request->ajax()) {
        return response()->json([
            'success' => false,
            'errors' => ['email' => [__($status)]]
        ], 422);
    }

    return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
}

}
