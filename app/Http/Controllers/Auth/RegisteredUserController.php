<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
   public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
{
    // ১. ব্রিজের ডিফল্ট ভ্যালিডেশন
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'string', 'in:student,alumni'], // এই লাইনটি যোগ করুন
    ]);

    // ২. ডাটাবেজে ইউজার ক্রিয়েট করা
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role, // ফর্ম থেকে আসা রোলটি ডাটাবেজে সেভ হবে
    ]);

    event(new Registered($user));

    Auth::login($user);

    // 🔥 রোল অনুযায়ী ডাইনামিক রিডাইরেক্ট পাথ সেট করা
    $redirectUrl = '/dashboard'; // ডিফল্ট সেফ ফলব্যাক

    if ($user->role === 'admin') {
        $redirectUrl = route('admin.dashboard');
    } elseif ($user->role === 'student') {
        $redirectUrl = route('student.dashboard'); // আপনার প্রজেক্টের স্টুডেন্ট ড্যাশবোর্ড রাউট নাম
    } elseif ($user->role === 'alumni') {
        $redirectUrl = route('alumni.dashboard'); // আপনার প্রজেক্টের এলামনাই ড্যাশবোর্ড রাউট নাম
    } elseif ($user->role === 'alumni') {
        $redirectUrl = route('alumni.dashboard'); 
    }

    // 🔥 আমাদের মডার্ন এজাক্স চেক (এখন এটি ডাইনামিক ইউআরএল পাঠাবে)
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Preparing your dashboard...',
            'redirect' => $redirectUrl
        ]);
    }

    return redirect($redirectUrl);
}
}
