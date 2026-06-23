<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialiteController extends Controller
{
    /**
     * কোন কোন provider অনুমোদিত
     */
    private array $allowedProviders = ['google', 'facebook'];

    /**
     * কোন কোন role নতুন user বেছে নিতে পারবে (admin বাদ)
     */
    private array $allowedRoles = ['student', 'alumni', 'teacher'];

    /*
    |--------------------------------------------------------------------------
    | STEP 1: provider এ redirect
    |--------------------------------------------------------------------------
    | register page থেকে এলে ?role=... query থাকে → session এ রাখি
    */
    public function redirect(string $provider, Request $request)
    {
        if (! in_array($provider, $this->allowedProviders)) {
            return redirect()->route('login')->with('error', 'Invalid login provider.');
        }

        // register page থেকে role পাঠানো হলে session এ রাখি (নতুন user এর জন্য)
        $role = $request->query('role');
        if ($role && in_array($role, $this->allowedRoles)) {
            session(['social_intended_role' => $role]);
        } else {
            session()->forget('social_intended_role');
        }

        try {
            // redirect এ stateless লাগে না — শুধু callback এ লাগে
            return Socialite::driver($provider)->redirect();
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'Could not connect to '.ucfirst($provider).'. Please try again.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2: provider থেকে callback
    |--------------------------------------------------------------------------
    | ৩টা ক্ষেত্র:
    |   (ক) পুরনো user (provider_id বা email মিলে) → সরাসরি login
    |   (খ) নতুন + role আছে (register থেকে) → account তৈরি + login
    |   (গ) নতুন + role নেই (login page থেকে) → Choose Role পেজে পাঠাই
    */
    public function callback(string $provider, Request $request)
    {
        if (! in_array($provider, $this->allowedProviders)) {
            return redirect()->route('login')->with('error', 'Invalid login provider.');
        }

        // provider থেকে user তথ্য আনি (callback এ stateless — localhost এ state সমস্যা এড়াতে)
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'Authentication failed or was cancelled. Please try again.');
        }

        $email = $socialUser->getEmail();

        // Facebook মাঝে মাঝে email নাও দিতে পারে — তখন আগাতে পারব না
        if (empty($email)) {
            return redirect()->route('login')
                ->with('error', 'We could not get your email from '.ucfirst($provider).'. Please use email signup instead.');
        }

        // ---------- ক্ষেত্র (ক): provider_id দিয়ে আগে থেকেই আছে কিনা ----------
        $existing = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($existing) {
            return $this->loginAndRedirect($existing);
        }

        // ---------- একই email এ অন্য account আছে কিনা (link করব) ----------
        $byEmail = User::where('email', $email)->first();

        if ($byEmail) {
            // recommended: একই email = একই ব্যক্তি → এই account এ provider link করি
            // (role অপরিবর্তিত থাকবে — পুরনো role ই থাকবে)
            $byEmail->forceFill([
                'provider'          => $provider,
                'provider_id'       => $socialUser->getId(),
                'email_verified_at' => $byEmail->email_verified_at ?? now(),
            ])->save();

            // profile ছবি না থাকলে provider এর ছবি বসিয়ে দিই (optional, ক্ষতি নেই)
            if (empty($byEmail->profile_picture) && $socialUser->getAvatar()) {
                // আমরা শুধু URL রাখছি না — স্টোরেজে নেই, তাই skip করছি যাতে ভাঙা লিংক না হয়
            }

            return $this->loginAndRedirect($byEmail);
        }

        // ---------- এ পর্যন্ত এলে: একদম নতুন user ----------
        $intendedRole = session('social_intended_role');

        // ক্ষেত্র (খ): register থেকে এসেছে — role আছে → এখনই বানাই
        if ($intendedRole && in_array($intendedRole, $this->allowedRoles)) {
            $user = $this->createSocialUser([
                'name'        => $socialUser->getName() ?: 'New User',
                'email'       => $email,
                'provider'    => $provider,
                'provider_id' => $socialUser->getId(),
                'role'        => $intendedRole,
            ]);

            session()->forget('social_intended_role');

            return $this->loginAndRedirect($user, true);
        }

        // ক্ষেত্র (গ): login page থেকে নতুন user — role নেই
        // → Google/FB তথ্য session এ অস্থায়ীভাবে রেখে Choose Role পেজে পাঠাই
        session([
            'social_pending' => [
                'provider'    => $provider,
                'provider_id' => $socialUser->getId(),
                'name'        => $socialUser->getName() ?: 'New User',
                'email'       => $email,
            ],
        ]);

        return redirect()->route('social.chooseRole');
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3: Choose Role পেজ দেখাই (নতুন social user)
    |--------------------------------------------------------------------------
    */
    public function showChooseRole(Request $request)
    {
        $pending = session('social_pending');

        // সরাসরি কেউ URL এ এলে (session নেই) → login এ ফেরত
        if (! $pending) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please sign in again.');
        }

        return view('auth.choose-role', [
            'name'     => $pending['name'],
            'email'    => $pending['email'],
            'provider' => $pending['provider'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 4: role confirm → account তৈরি + login
    |--------------------------------------------------------------------------
    */
    public function storeChooseRole(Request $request)
    {
        $pending = session('social_pending');

        if (! $pending) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please sign in again.');
        }

        $request->validate([
            'role' => 'required|in:'.implode(',', $this->allowedRoles),
        ], [
            'role.required' => 'Please choose your account type.',
            'role.in'       => 'Invalid account type selected.',
        ]);

        // নিরাপত্তা: এই email/provider_id এর মধ্যে আবার কেউ বানিয়ে ফেলেনি তো?
        $already = User::where('email', $pending['email'])->first();
        if ($already) {
            session()->forget('social_pending');
            return $this->loginAndRedirect($already);
        }

        $user = $this->createSocialUser([
            'name'        => $pending['name'],
            'email'       => $pending['email'],
            'provider'    => $pending['provider'],
            'provider_id' => $pending['provider_id'],
            'role'        => $request->input('role'),
        ]);

        session()->forget('social_pending');

        return $this->loginAndRedirect($user, true);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: নতুন social user তৈরি
    |--------------------------------------------------------------------------
    | - password র‍্যান্ডম (login এ লাগবে না, কিন্তু কলাম nullable হলেও সেট করা নিরাপদ)
    | - email_verified_at = now() (Google/FB ইমেইল আগেই verified — auto verify)
    | - status active
    */
    private function createSocialUser(array $data): User
    {
        return User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'provider'          => $data['provider'],
            'provider_id'       => $data['provider_id'],
            'role'              => $data['role'],
            'status'            => 'active',
            'password'          => bcrypt(Str::random(40)),
            'email_verified_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: login করিয়ে সঠিক জায়গায় পাঠাই
    |--------------------------------------------------------------------------
    | role অনুযায়ী redirect (RegisteredUserController এর মতোই):
    |   admin → admin.dashboard, teacher → home, alumni → alumni.dashboard,
    |   student → student.dashboard, default → home
    */
    private function loginAndRedirect(User $user, bool $isNew = false)
    {
        // suspended/blocked user চেক (থাকলে)
        if (($user->status ?? 'active') === 'suspended') {
            return redirect()->route('login')
                ->with('error', 'Your account is suspended. Please contact support.');
        }

        Auth::login($user, true); // remember = true

        request()->session()->regenerate();

        $target = $this->redirectPathForRole($user->role);

        $msg = $isNew ? 'Welcome to Borobhai.online! Your account is ready.' : 'Logged in successfully!';

        return redirect()->intended($target)->with('success', $msg);
    }

    /**
     * role অনুযায়ী target route
     */
    private function redirectPathForRole(?string $role): string
    {
        return match ($role) {
            'admin'   => route('admin.dashboard'),
            'student' => route('student.dashboard'),
            'alumni'  => route('alumni.dashboard'),
            // teacher এর আলাদা role:alumni route নেই — তাই home এ (PostController teacher কে alumni feed দেখাবে)
            'teacher' => route('home'),
            default   => route('home'),
        };
    }
}