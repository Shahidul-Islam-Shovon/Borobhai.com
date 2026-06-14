<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileDetailController;
use App\Http\Controllers\SavedPostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HOME / FEED (Facebook মডেল)
|--------------------------------------------------------------------------
| লগইন না থাকলে auth middleware লগইন পেজে পাঠাবে → লগইনের পর আবার "/" তে ফিরবে
*/
Route::get('/', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // student বা alumni — দুজনের ফিডই "/" তে; PostController@index role দেখে ঠিক view দেবে
    return app(PostController::class)->index();
})->middleware(['auth', 'verified'])->name('home');

// পুরনো /dashboard → "/" তে redirect (backward compatibility)
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN (role:admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // মূল অ্যাডমিন ড্যাশবোর্ড
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

    // অথরিটি ম্যানেজমেন্ট
    Route::post('/admin/manage-authority', [AdminDashboardController::class, 'manageAuthority'])->name('admin.manage.authority');
});


/*
|--------------------------------------------------------------------------
| STUDENT / ALUMNI DASHBOARD (একই ইউনিফাইড ফিড)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('student.dashboard');
});

Route::middleware(['auth', 'role:alumni'])->prefix('alumni')->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('alumni.dashboard');
});


// Breeze auth routes
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // প্রিমিয়াম প্রোফাইল
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');               // নিজের
    Route::get('/profile/tab/content', [ProfileController::class, 'tabContent'])->name('profile.tab');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update.info');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');

    // Profile Details (Education / Experience / Certification)
    Route::post('/profile/education', [ProfileDetailController::class, 'storeEducation'])->name('profile.education.store');
    Route::delete('/profile/education/{id}', [ProfileDetailController::class, 'deleteEducation'])->name('profile.education.delete');

    Route::post('/profile/experience', [ProfileDetailController::class, 'storeExperience'])->name('profile.experience.store');
    Route::delete('/profile/experience/{id}', [ProfileDetailController::class, 'deleteExperience'])->name('profile.experience.delete');

    Route::post('/profile/certification', [ProfileDetailController::class, 'storeCertification'])->name('profile.certification.store');
    Route::delete('/profile/certification/{id}', [ProfileDetailController::class, 'deleteCertification'])->name('profile.certification.delete');

    // Documents (Thesis / Project / Research)
    Route::post('/profile/document', [DocumentController::class, 'store'])->name('profile.document.store');
    Route::delete('/profile/document/{id}', [DocumentController::class, 'destroy'])->name('profile.document.delete');

    // Breeze account settings (ডিফল্ট)
    Route::get('/profile/edit/account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // অন্যের প্রোফাইল ও তার ট্যাব (⚠️ {id} যুক্ত — তাই উপরের static গুলোর পরে)
    Route::get('/profile/{id}/tab/content', [ProfileController::class, 'tabContent'])->name('profile.tab.user');
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.view');
});


/*
|--------------------------------------------------------------------------
| POSTS (feed, share, save, infinite scroll)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Infinite scroll feed loader
    Route::get('/feed/load', [PostController::class, 'loadMore'])->name('feed.load');

    // পোস্ট আপডেট / ডিলিট / শেয়ার
    Route::post('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{id}/share', [PostController::class, 'share'])->name('posts.share');

    // পোস্ট সেভ/আনসেভ + Saved পেজ
    Route::post('/posts/{post}/save', [SavedPostController::class, 'toggle'])->name('posts.save');
    Route::get('/saved', [SavedPostController::class, 'index'])->name('saved.index');
});


/*
|--------------------------------------------------------------------------
| JOBS + APPLICATIONS
|--------------------------------------------------------------------------
| ⚠️ ক্রম গুরুত্বপূর্ণ:
|   1) static path (/jobs, /my-applications) — আগে
|   2) /jobs/{id}/... (extra segment) — মাঝে
|   3) /jobs/{id} (শুধু id, show) — সবার শেষে
*/
Route::middleware('auth')->group(function () {

    // --- static (কোনো {id} নেই) ---
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs', [JobController::class, 'all'])->name('jobs.all');
    Route::get('/my-applications', [JobApplicationController::class, 'myApplications'])->name('jobs.myApplications');

    // --- /jobs/{id}/... (sub-path আগে, যাতে show এগুলো গিলে না ফেলে) ---
    Route::get('/jobs/{id}/data', [JobController::class, 'getJob'])->name('jobs.data');
    Route::post('/jobs/{id}/save', [JobController::class, 'toggleSave'])->name('jobs.save');
    Route::post('/jobs/{id}/apply', [JobApplicationController::class, 'apply'])->name('jobs.apply');
    Route::post('/jobs/{id}/withdraw', [JobApplicationController::class, 'withdraw'])->name('jobs.withdraw');
    Route::get('/jobs/{id}/applicants', [JobApplicationController::class, 'applicants'])->name('jobs.applicants');

    // --- application status (alumni) ---
    Route::post('/applications/{id}/status', [JobApplicationController::class, 'updateStatus'])->name('jobs.application.status');

    // --- জব ডিলিট ---
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('jobs.delete');

    // --- {id} show — সবার শেষে (catch-all) ---
    Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
});


/*
|--------------------------------------------------------------------------
| LIKES + COMMENTS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Like টগল
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

    // Comment CRUD
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Comment — লোড মোর, লাইক
    Route::get('/posts/{post}/comments/load', [CommentController::class, 'loadMore'])->name('comments.load');
    Route::post('/comments/{comment}/like', [CommentController::class, 'toggleLike'])->name('comments.like');
});


/*
|--------------------------------------------------------------------------
| VIDEO STREAMING (Range request সাপোর্ট সহ)
|--------------------------------------------------------------------------
*/
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