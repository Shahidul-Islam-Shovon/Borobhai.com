<?php
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==========================================
// হোম রাউট = ফিড (Facebook মডেল)
// লগইন না থাকলে auth middleware লগইন পেজে পাঠাবে → লগইনের পর আবার "/" তে ফিরবে
// ==========================================
Route::get('/', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    // student বা alumni — দুজনের ফিডই "/" তে; PostController@index role দেখে ঠিক view দেবে
    return app(PostController::class)->index();
})->middleware(['auth', 'verified'])->name('home');

// পুরনো /dashboard রাউট — "/" তে redirect (backward compatibility)
Route::get('/dashboard', function () {
    return redirect()->route('home');
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


// কোনো মিডলওয়্যার বা প্রিফিক্স গ্রুপের বাইরে একদম নিচে স্বাধীনভাবে দিন
Route::post('/admin/manage-authority', [App\Http\Controllers\Admin\AdminDashboardController::class, 'manageAuthority'])->name('admin.manage.authority')->middleware('auth');

require __DIR__.'/auth.php';


Route::middleware('auth')->group(function () {
    // 🆕 প্রিমিয়াম প্রোফাইল
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');           // নিজের প্রোফাইল
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.view');       // অন্যের প্রোফাইল
    Route::get('/profile/tab/content', [ProfileController::class, 'tabContent'])->name('profile.tab');
    Route::get('/profile/{id}/tab/content', [ProfileController::class, 'tabContent'])->name('profile.tab.user');
    Route::get('/profile/{id}/tab/content', [ProfileController::class, 'tabContent'])->name('profile.tab.user'); // 🆕 অন্যের ট্যাব
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update.info');  // AJAX তথ্য আপডেট
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');    // AJAX ছবি আপডেট

    // Breeze ডিফল্ট (অক্ষত)
    Route::get('/profile/edit/account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // আগের অন্যান্য রাউটগুলোর সাথে নিচে এই দুটি বসিয়ে দিন
    Route::post('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{id}/share', [PostController::class, 'share'])
    ->name('posts.share');
    Route::middleware(['auth'])->group(function () {
    // Infinite scroll feed loader
    Route::get('/feed/load', [PostController::class, 'loadMore'])->name('feed.load');
    });
     // পোস্ট সেভ/আনসেভ টগল
    Route::post('/posts/{post}/save', [App\Http\Controllers\SavedPostController::class, 'toggle'])->name('posts.save');

    // Saved পেজ
    Route::get('/saved', [App\Http\Controllers\SavedPostController::class, 'index'])->name('saved.index');

});

Route::middleware(['auth'])->group(function () {
    // Like Route
    Route::post('/posts/{post}/like', [App\Http\Controllers\LikeController::class, 'toggle'])->name('posts.like');

    // Comment Routes
    Route::post('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::middleware(['auth'])->group(function () {
    // একটা পোস্টের আরও কমেন্ট লোড করা (View more)
    Route::get('/posts/{post}/comments/load', [App\Http\Controllers\CommentController::class, 'loadMore'])->name('comments.load');
});

});


// ভিডিও streaming (Range request সাপোর্ট সহ)
Route::get('/stream/video/{path}', function ($path) {
    $path = str_replace('..', '', $path); // নিরাপত্তা
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath, [
        'Content-Type'  => 'video/mp4',
        'Accept-Ranges' => 'bytes',
    ]);
})->where('path', '.*')->name('stream.video');