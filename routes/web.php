<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// ১. স্টুডেন্ট বা ডিফল্ট ড্যাশবোর্ড (লারাভেলের ডিফল্টটাই রাখলাম আপাতত)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ২. অ্যাডমিন ড্যাশবোর্ড রাউট (Admin Middleware দিয়ে সুরক্ষিত)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return "<h1>Welcome To Admin Panel</h1><p>This Can Access only Admin</p>";
    })->name('admin.dashboard');
});

// ৩. অ্যালমনাই ড্যাশবোর্ড রাউট (Alumni Middleware দিয়ে সুরক্ষিত)
Route::middleware(['auth', 'alumni'])->group(function () {
    Route::get('/alumni/dashboard', function () {
        return "<h1>Welcome To Alumni Dashboard</h1><p>This Can Access only Alumni</p>";
    })->name('alumni.dashboard');
});


