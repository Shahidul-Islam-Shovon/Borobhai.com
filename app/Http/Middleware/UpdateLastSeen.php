<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            // প্রতি ৩০ সেকেন্ডে একবার DB update (বেশি load এড়াতে cache দিয়ে throttle)
            $cacheKey = 'last_seen_updated_' . $userId;
            if (!Cache::has($cacheKey)) {
                Auth::user()->updateQuietly(['last_seen' => now()]);
                Cache::put($cacheKey, true, 30); // ৩০ সেকেন্ড
            }
        }

        return $next($request);
    }
}