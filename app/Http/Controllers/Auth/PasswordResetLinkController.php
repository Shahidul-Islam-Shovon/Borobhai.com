<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */

    public function store(Request $request): RedirectResponse|JsonResponse
{
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    // ব্রিজের ডিফল্ট মেকানিজমে মেইল পাঠানো
    $status = Password::broker()->sendResetLink(
        $request->only('email')
    );

    if ($status === Password::RESET_LINK_SENT) {
        // 🔥 এজাক্স রিকোয়েস্ট হলে সুন্দর JSON রেসপন্স ব্যাক করা
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __($status) // "We have emailed your password reset link."
            ]);
        }

        return back()->with('status', __($status));
    }

    // যদি ইমেইল ভুল হয় বা কোনো প্রবলেম হয়
    if ($request->ajax()) {
        return response()->json([
            'success' => false,
            'errors' => ['email' => [__($status)]]
        ], 422);
    }

    return back()->withErrors(['email' => __($status)]);
}
   
}
