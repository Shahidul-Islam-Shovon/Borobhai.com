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
            // ১. ভ্যালিডেশন — teacher সহ ৩ রোল
        $request->validate([
            'role'     => 'required|in:student,alumni,teacher',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'terms'    => 'accepted',   // ✅ এই লাইন যোগ করুন
        ], [
            'terms.accepted' => 'You must agree to the Terms & Privacy Policy.',
        ]);

        // ২. ইউজার ক্রিয়েট
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // ৩. রোল অনুযায়ী রিডাইরেক্ট
        $redirectUrl = $this->redirectPathForRole($user->role);

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Registration successful! Preparing your dashboard...',
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl);
    }

    /**
     * রোল অনুযায়ী সঠিক dashboard path।
     * Teacher → home feed (alumni এর মতই feed, route('home'))
     */
    private function redirectPathForRole(string $role): string
    {
        return match ($role) {
            'admin'   => route('admin.dashboard'),
            'student' => route('student.dashboard'),
            'alumni'  => route('alumni.dashboard'),
            'teacher' => route('home'),  // teacher → home feed (role:alumni guard এড়াতে)
            default   => route('home'),
        };
    }
}