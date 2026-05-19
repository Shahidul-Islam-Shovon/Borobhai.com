<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ১. আপনার আগের ৩টি কাস্টম মিডলওয়্যার ও রোল চেক অ্যালিয়াস (সংরক্ষিত)
        $middleware->alias([
            'admin'   => \App\Http\Middleware\AdminMiddleware::class,
            'alumni'  => \App\Http\Middleware\AlumniMiddleware::class,
            'student' => \App\Http\Middleware\StudentMiddleware::class,
            'role'    => \App\Http\Middleware\CheckRole::class,
        ]);

        // ২. নতুন রিকোয়ারমেন্ট: ইউজার সাসপেনশন গ্লোবাল চেক মিডলওয়্যার (web গ্রুপে অ্যাপেন্ড)
        // এটি প্রতিবার পেজ রিকোয়েস্ট বা রিলোডের সময় ব্যাকএন্ডে ইউজার স্ট্যাটাস চেক করবে
        $middleware->web(append: [
            \App\Http\Middleware\CheckUserStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();