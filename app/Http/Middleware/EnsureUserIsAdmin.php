<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !($user->role === 'admin' || $user->is_super_admin)) {
            abort(403, 'এই পেজ দেখার অনুমতি আপনার নেই।');
        }

        return $next($request);
    }
}