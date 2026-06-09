<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    /**
 * Attempt to authenticate the request's credentials.
 * ল্যারাভেল ১৩ + ব্রিজ: লগইন সাকসেস হওয়ার আগেই সাসপেনশন ফায়ারওয়াল (BUG FIXED)
 *
 * @throws \Illuminate\Validation\ValidationException
 */
public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    // ১. ডেটাবেজ থেকে লগইন করতে চাওয়া ইউজারকে খুঁজে বের করা
    $user = User::query()->where('email', $this->boolean('email') ? null : $this->string('email'))->first();

    // ২. ইউজার যদি এক্সিস্ট করে এবং তার দেওয়া পাসওয়ার্ড যদি একদম সঠিক হয়
    if ($user && Hash::check($this->string('password'), $user->password)) {
        
        // চেক ১: পারমানেন্ট সাসপেনশন
        if ($user->status === 'suspended_perm') {
            redirect()->route('login')->with('suspended_error', 'Security Alert: You are Suspended Permanently from this site.')->send();
            exit;
        }

        // 🚫 চেক ২: সাময়িক (৭ দিন) সাসপেনশন
            if ($user->status === 'suspended_temp') {
                if ($user->suspended_until && now()->greaterThan($user->suspended_until)) {
                    $user->status = 'active';
                    $user->suspended_until = null;
                    $user->save();
                } else {
                    $unlockTime = $user->suspended_until ? $user->suspended_until->format('j M Y, \a\t h:i A') : 'N/A';
                    
                    redirect()->route('login')->with('suspended_error', "Access Denied: You are Suspended By Admin. Unlocks at: {$unlockTime}.")->send();
                    exit;
                }
            }
    }

    // ৩. ইউজার যদি একটিভ থাকে, তবেই কেবল ব্রিজের ডিফল্ট লগইন সাকসেস এটেম্পট রান হবে
    if (! Auth::attempt($this->only('email', 'password'), true)) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
