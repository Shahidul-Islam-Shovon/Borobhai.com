<?php
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Role-Based Central Dispatcher Route
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'alumni') {
        return redirect()->route('alumni.dashboard');
    } else {
        return redirect()->route('student.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Group for Admin Protected Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // মূল অ্যাডমিন ড্যাশবোর্ড ভিউ
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Chart.js এর জন্য ডাইনামিক ডাটা API
    Route::get('/dashboard/analytics-data', [AdminDashboardController::class, 'getAnalyticsData'])->name('dashboard.analytics');

    // ইউজার রোল পরিবর্তন
    Route::post('/users/{id}/change-role', [AdminDashboardController::class, 'changeUserRole'])->name('users.change-role');

    // অ্যাডভান্সড সাসপেনশন (Temp/Perm)
    Route::post('/users/{id}/suspension', [AdminDashboardController::class, 'updateSuspensionStatus'])->name('users.suspension');

    // পোস্ট মডারেশন (AJAX Delete)
    Route::delete('/posts/{id}/delete', [AdminDashboardController::class, 'deletePost'])->name('posts.delete');

    // জব/সার্কুলার মডারেশন (AJAX Delete)
    Route::delete('/circulars/{id}/delete', [AdminDashboardController::class, 'deleteCircular'])->name('circulars.delete');
});

// Group for Student Protected Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', function () { return view('student.dashboard'); })->name('student.dashboard');
});

// Group for Alumni Protected Routes
Route::middleware(['auth', 'role:alumni'])->prefix('alumni')->group(function () {
    Route::get('/dashboard', function () { return view('alumni.dashboard'); })->name('alumni.dashboard');
});

require __DIR__.'/auth.php';