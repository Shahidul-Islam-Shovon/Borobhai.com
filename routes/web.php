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
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ==========================================
// HOME
// ==========================================
Route::get('/', function () {
    $user = Auth::user();
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    return app(PostController::class)->index();
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                          [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics-data',           [AdminDashboardController::class, 'getAnalyticsData'])->name('dashboard.analytics');
    Route::post('/users/{id}/change-role',            [AdminDashboardController::class, 'changeUserRole'])->name('users.change-role');
    Route::post('/users/{id}/suspension',             [AdminDashboardController::class, 'updateSuspensionStatus'])->name('users.suspension');
    Route::delete('/posts/{id}/delete',               [AdminDashboardController::class, 'deletePost'])->name('posts.delete');
    Route::delete('/circulars/{id}/delete',           [AdminDashboardController::class, 'deleteCircular'])->name('circulars.delete');
    Route::post('/admin/manage-authority',            [AdminDashboardController::class, 'manageAuthority'])->name('manage.authority');
});

// ==========================================
// LEGACY DASHBOARD ROUTES
// ==========================================
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('student.dashboard');
});
Route::middleware(['auth', 'role:alumni'])->prefix('alumni')->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('alumni.dashboard');
});

// Breeze auth routes
require __DIR__.'/auth.php';

// Terms & Privacy — সবাই দেখতে পারবে
Route::view('/terms',   'auth.terms')->name('terms');
Route::view('/privacy', 'auth.privacy')->name('privacy');

// Social login — guest only
Route::middleware('guest')->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('social.callback');
    Route::get('/auth/choose-role',         [SocialiteController::class, 'showChooseRole'])->name('social.chooseRole');
    Route::post('/auth/choose-role',        [SocialiteController::class, 'storeChooseRole'])->name('social.storeRole');
});

// ==========================================
// AUTH REQUIRED — সব এক group এ
// ==========================================
Route::middleware('auth')->group(function () {

    // ---------- PROFILE ----------
    Route::get('/profile',                      [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/tab/content',          [ProfileController::class, 'tabContent'])->name('profile.tab');
    Route::post('/profile/update',              [ProfileController::class, 'updateProfile'])->name('profile.update.info');
    Route::post('/profile/photo',               [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');
    Route::get('/profile/edit/account',         [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',                    [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',                   [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/education',           [ProfileDetailController::class, 'storeEducation'])->name('profile.education.store');
    Route::delete('/profile/education/{id}',    [ProfileDetailController::class, 'deleteEducation'])->name('profile.education.delete');
    Route::post('/profile/experience',          [ProfileDetailController::class, 'storeExperience'])->name('profile.experience.store');
    Route::delete('/profile/experience/{id}',   [ProfileDetailController::class, 'deleteExperience'])->name('profile.experience.delete');
    Route::post('/profile/certification',       [ProfileDetailController::class, 'storeCertification'])->name('profile.certification.store');
    Route::delete('/profile/certification/{id}',[ProfileDetailController::class, 'deleteCertification'])->name('profile.certification.delete');
    Route::post('/profile/document',            [DocumentController::class, 'store'])->name('profile.document.store');
    Route::delete('/profile/document/{id}',     [DocumentController::class, 'destroy'])->name('profile.document.delete');

    // ⚠️ {id} routes — static এর পরে
    Route::get('/profile/{id}/tab/content',     [ProfileController::class, 'tabContent'])->name('profile.tab.user');
    Route::get('/profile/{id}',                 [ProfileController::class, 'show'])->name('profile.view');

    // ---------- FEED ----------
    Route::get('/feed/load',                    [PostController::class, 'loadMore'])->name('feed.load');
    Route::post('/posts',                       [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{id}',                  [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}',                [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{id}/share',            [PostController::class, 'share'])->name('posts.share');
    Route::post('/posts/{post}/save',           [SavedPostController::class, 'toggle'])->name('posts.save');
    Route::get('/saved',                        [SavedPostController::class, 'index'])->name('saved.index');

    // ---------- JOBS ----------
    Route::post('/jobs',                        [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs',                         [JobController::class, 'all'])->name('jobs.all');
    Route::get('/my-applications',              [JobApplicationController::class, 'myApplications'])->name('jobs.myApplications');
    Route::get('/jobs/{id}/data',               [JobController::class, 'getJob'])->name('jobs.data');
    Route::post('/jobs/{id}/save',              [JobController::class, 'toggleSave'])->name('jobs.save');
    Route::post('/jobs/{id}/apply',             [JobApplicationController::class, 'apply'])->name('jobs.apply');
    Route::post('/jobs/{id}/withdraw',          [JobApplicationController::class, 'withdraw'])->name('jobs.withdraw');
    Route::get('/jobs/{id}/applicants',         [JobApplicationController::class, 'applicants'])->name('jobs.applicants');
    Route::post('/applications/{id}/status',    [JobApplicationController::class, 'updateStatus'])->name('jobs.application.status');
    Route::delete('/jobs/{id}',                 [JobController::class, 'destroy'])->name('jobs.delete');
    Route::get('/jobs/{id}',                    [JobController::class, 'show'])->name('jobs.show');

    // ---------- LIKES + COMMENTS ----------
    Route::post('/posts/{post}/like',           [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/comments',       [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}',           [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}',        [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/posts/{post}/comments/load',   [CommentController::class, 'loadMore'])->name('comments.load');
    Route::post('/comments/{comment}/like',     [CommentController::class, 'toggleLike'])->name('comments.like');

    // ---------- ACTIVE NOW ----------
    Route::get('/active-now',                   [PostController::class, 'activeNow'])->name('active.now');

    // ---------- SEARCH ----------
    Route::get('/search',                       [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/live',                  [SearchController::class, 'live'])->name('search.live');
    Route::get('/search/recent',                [SearchController::class, 'recentSearches'])->name('search.recent');
    Route::delete('/search/recent/{id}',        [SearchController::class, 'deleteSearch'])->name('search.deleteRecent');
    Route::delete('/search/recent',             [SearchController::class, 'clearSearches'])->name('search.clearRecent');

    // ---------- FRIENDS ----------
    Route::prefix('friends')->name('friends.')->group(function () {
        Route::get('/',                         [FriendController::class, 'friendsList'])->name('index');
        Route::get('/suggested',                [FriendController::class, 'suggestedAll'])->name('suggested');
        Route::get('/{userId}/mutual',          [FriendController::class, 'mutualFriends'])->name('mutual');
        Route::get('/status/{userId}',          [FriendController::class, 'statusWith'])->name('status');
        Route::post('/send',                    [FriendController::class, 'sendRequest'])->name('send');
        Route::post('/accept',                  [FriendController::class, 'acceptRequest'])->name('accept');
        Route::post('/decline',                 [FriendController::class, 'declineRequest'])->name('decline');
        Route::post('/cancel',                  [FriendController::class, 'cancelRequest'])->name('cancel');
        Route::post('/unfriend',                [FriendController::class, 'unfriend'])->name('unfriend');
        Route::post('/block',                   [FriendController::class, 'block'])->name('block');
        Route::post('/unblock',                 [FriendController::class, 'unblock'])->name('unblock');
        Route::post('/not-interested',          [FriendController::class, 'notInterested'])->name('notInterested');
    });

    // ---------- REPORT ----------
    Route::post('/report',                      [ReportController::class, 'store'])->name('report.store');

    // ---------- NOTIFICATIONS ----------
    Route::get('/notifications',                [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/poll',           [NotificationController::class, 'poll'])->name('notifications.poll');
    Route::post('/notifications/read-all',      [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});

// ---------- VIDEO STREAMING ----------
Route::get('/stream/video/{path}', function ($path) {
    $path     = str_replace('..', '', $path);
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) abort(404);
    return response()->file($fullPath, [
        'Content-Type'  => 'video/mp4',
        'Accept-Ranges' => 'bytes',
    ]);
})->where('path', '.*')->name('stream.video');