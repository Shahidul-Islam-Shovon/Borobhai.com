<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Authenticated Session Multi-Routing Fallback
// If someone visits /dashboard directly, this middleware tree handles them safely
Route::middleware(['auth'])->group(function () {
    
    // 1. Admin Space
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return "<h1>Welcome To Admin Panel</h1><p>This page is restricted to Administrators only.</p>";
        })->name('admin.dashboard');
    });

    // 2. Alumni Space
    Route::middleware(['alumni'])->group(function () {
        Route::get('/alumni/dashboard', function () {
            return "<h1>Welcome To Alumni Dashboard</h1><p>This page is restricted to Alumni members only.</p>";
        })->name('alumni.dashboard');
    });

    // 3. Student Space (Standard Default Dashboard)
    Route::middleware(['student'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    // Profile Management (Shared by all roles)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes (Breeze)
require __DIR__.'/auth.php';