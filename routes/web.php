<?php
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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

    // আপনার অ্যাডমিন বা সুপার অ্যাডমিন রাউট গ্রুপের ভেতরে এই লাইনটি যোগ করুন
    Route::post('/admin/manage-authority', [AdminDashboardController::class, 'manageAuthority'])->name('admin.manage.authority');
});


// Group for Student Protected Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    // সরাসরি ভিউ রিটার্ন না করে কন্ট্রোলারের মাধ্যমে ভিউ লোড হবে
    Route::get('/dashboard', [PostController::class, 'index'])->name('student.dashboard');
});

// Group for Alumni Protected Routes
Route::middleware(['auth', 'role:alumni'])->prefix('alumni')->group(function () {
    // অ্যালামনাইদের জন্যও একই ইউনিফাইড নিউজফিড কন্ট্রোলার কাজ করবে
    Route::get('/dashboard', [PostController::class, 'index'])->name('alumni.dashboard');  
});


// কোনো মিডলওয়্যার বা প্রিফিক্স গ্রুপের বাইরে একদম নিচে স্বাধীনভাবে দিন
Route::post('/admin/manage-authority', [App\Http\Controllers\Admin\AdminDashboardController::class, 'manageAuthority'])->name('admin.manage.authority')->middleware('auth');

require __DIR__.'/auth.php';


// লারাভেল ব্রিজের ডিফল্ট রাউটগুলো এভাবে রাখা উচিত:
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // এই লাইনটিই আপনার মিসিং ছিল!
});


Route::post('/posts/{id}/share', [PostController::class, 'share'])->name('posts.share');

Route::middleware(['auth'])->group(function () {
    // আগের অন্যান্য রাউটগুলোর সাথে নিচে এই দুটি বসিয়ে দিন
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
});