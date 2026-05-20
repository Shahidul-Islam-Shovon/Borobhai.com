<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // 🎯 প্রথম রিফ্রেশে ওল্ড সেশন অবজেক্ট এড়াতে সরাসরি ডাটাবেজ থেকে ফ্রেশ ডেটা রিড করা হলো
            $freshUser = \DB::table('users')->where('id', $user->id)->first();

            if ($freshUser) {
                // ১. টেম্পোরারি সাসপেনশন চেক
                if ($freshUser->status === 'suspended_temp') {
                    
                    // প্রথম রিফ্রেশেই যাতে ডেট নাল বা N/A না হয়, তার জন্য কন্ডিশনাল ব্যাকআপ সহ ফরম্যাট
                    if ($freshUser->suspended_until) {
                        $dateText = Carbon::parse($freshUser->suspended_until)->format('d M Y \a\t  g:i a');
                    } else {
                        // যদি কোনো কারণে ইমিডিয়েটলি ডেট না পায়, তবে কারেন্ট টাইম থেকে ৭ দিন যোগ করে ইনস্ট্যান্ট দেখাবে
                        $dateText = Carbon::now()->addDays(7)->format('d M Y \a\t  g:i a');
                    }

                    // সেশন আউট করে দেওয়া যাতে ব্যাক বোতাম চাপলেও আর ঢুকতে না পারে
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // 🎯 আপনার চাহিদামতো একদম নিখুঁত মেসেজ ফরম্যাট (লগইন ব্লেডের গ্লোবাল অ্যালার্ট এটি রিড করবে)
                    return redirect()->route('login')->with('error', "Access Denied ! You are tempurary Suspended Untill : {$dateText}");
                }

                // ২. পারমানেন্ট সাসপেনশন চেক
                if ($freshUser->status === 'suspended_perm') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // return redirect()->route('login')->with('error', "Access Denied ! ..."); 
                    // উপরের লাইনের বদলে সেশনে পুশ করে রিডাইরেক্ট করুন:

                    $request->session()->put('suspended_permanent_msg', "Access Denied ! You are tempurary Suspended Untill : {$dateText}");
                    return redirect()->route('login');
                                    }
                                }
                            }

        return $next($request);
    }
}