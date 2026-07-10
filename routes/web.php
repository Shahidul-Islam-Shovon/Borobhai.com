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
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HeartbeatController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/student/dashboard', [PostController::class, 'index'])->name('student.dashboard');
    Route::get('/alumni/dashboard', [PostController::class, 'index'])->name('alumni.dashboard');
});

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                 [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics-data',  [AdminDashboardController::class, 'getAnalyticsData'])->name('dashboard.analytics');
    Route::get('/reports/history',           [AdminDashboardController::class, 'reportHistory'])->name('reports.history');
    Route::get('/reports/poll', [AdminDashboardController::class, 'pollReports'])->name('reports.poll');

    Route::get('/dashboard/report/download', [AdminDashboardController::class, 'downloadReport'])->name('dashboard.report.download');

    Route::post('/users/{id}/change-role',   [AdminDashboardController::class, 'changeUserRole'])->name('users.change-role');
    Route::post('/users/{id}/suspension',    [AdminDashboardController::class, 'updateSuspensionStatus'])->name('users.suspension');
    Route::post('/reports/suspend/{userId}', [AdminDashboardController::class, 'suspendFromReport'])->name('reports.suspend');

    Route::delete('/posts/{id}/delete',      [AdminDashboardController::class, 'deletePost'])->name('posts.delete');
    Route::delete('/circulars/{id}/delete',  [AdminDashboardController::class, 'deleteCircular'])->name('circulars.delete');
    Route::post('/manage-authority',         [AdminDashboardController::class, 'manageAuthority'])->name('manage.authority');

    Route::post('/reports/{id}/warn',             [AdminDashboardController::class, 'warnUser'])->name('reports.warn');
    Route::post('/reports/{id}/dismiss',          [AdminDashboardController::class, 'dismissReport'])->name('reports.dismiss');
    Route::post('/reports/{id}/mark-seen',        [AdminDashboardController::class, 'markSeen'])->name('reports.markSeen');
    Route::delete('/reports/{id}/delete-content', [AdminDashboardController::class, 'deleteReportedContent'])->name('reports.delete-content');
    Route::post('/reports/{id}/complete',         [AdminDashboardController::class, 'completeReport'])->name('reports.complete');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/posts/review/{hashid}',            [PostController::class, 'adminReview'])->name('admin.post.review');
    Route::post('/admin/reports/{report}/review',         [\App\Http\Controllers\Admin\AdminDashboardController::class, 'submitReview'])->name('admin.reports.review');
    Route::post('/admin/reports/{report}/mark-reviewed',  [\App\Http\Controllers\Admin\AdminDashboardController::class, 'markReviewed'])->name('admin.reports.markReviewed');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/reports/{hashid}/decision',  [\App\Http\Controllers\ReportDecisionController::class, 'show'])->name('reports.decision');
    Route::post('/reports/{hashid}/appeal',   [\App\Http\Controllers\ReportDecisionController::class, 'appeal'])->name('reports.appeal');
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
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
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

    Route::get('/profile/{user}/tab/content',   [ProfileController::class, 'tabContent'])->name('profile.tab.user');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.view');


    Route::get('/muted-accounts',              [\App\Http\Controllers\MutedUserController::class, 'index'])->name('muted.index');
    
    Route::post('/muted-accounts/{userId}/unmute', [\App\Http\Controllers\MutedUserController::class, 'unmute'])->name('muted.unmute');

    // auth group এর ভেতরে
    Route::post('/account/deactivate',  [ProfileController::class, 'deactivate'])->name('account.deactivate');
    Route::post('/account/delete',      [ProfileController::class, 'requestDelete'])->name('account.delete');

    // ✅ deactivated/pending_delete user এর জন্য — auth middleware শুধু
    Route::middleware(['auth'])->group(function () {
        Route::get('/account/reactivate',       [ProfileController::class, 'reactivate'])->name('account.reactivate');
        Route::post('/account/reactivate',      [ProfileController::class, 'reactivatePost'])->name('account.reactivate.post');
        Route::get('/account/recovery',         [ProfileController::class, 'recovery'])->name('account.recovery');
        Route::post('/account/recover',         [ProfileController::class, 'recover'])->name('account.recover');
    });

    // ---------- FEED ----------
    Route::post('/presence/offline', [PostController::class, 'goOffline'])->name('presence.offline');

    Route::get('/feed/load',                    [PostController::class, 'loadMore'])->name('feed.load');
    Route::post('/posts',                       [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{id}',                  [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}',                [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{id}/share',            [PostController::class, 'share'])->name('posts.share');
    Route::post('/posts/{post}/save',           [SavedPostController::class, 'toggle'])->name('posts.save');

    Route::get('/saved',                        [SavedPostController::class, 'index'])->name('saved.index');
    Route::get('/feed/live-counts', [PostController::class, 'liveCounts'])->name('feed.liveCounts');

    // ---------- JOBS ----------
    Route::post('/jobs',                        [JobController::class, 'store'])->name('jobs.store')->middleware('role:alumni,teacher');
    Route::get('/jobs',                         [JobController::class, 'all'])->name('jobs.all');
    Route::get('/my-applications',              [JobApplicationController::class, 'myApplications'])->name('jobs.myApplications');
    Route::get('/my-applications/live-status',  [JobApplicationController::class, 'liveStatus'])->name('jobs.myApplications.live');
    Route::get('/jobs/{id}/applicants/live',    [JobApplicationController::class, 'applicantsLive'])->name('jobs.applicants.live');
    Route::get('/jobs/{id}/data',                [JobController::class, 'getJob'])->name('jobs.data');
    Route::get('/jobs/{id}/report',              [JobController::class, 'downloadReport'])->name('jobs.report');
    Route::post('/jobs/{id}/save',               [JobController::class, 'toggleSave'])->name('jobs.save');
    Route::post('/jobs/{id}/apply',              [JobApplicationController::class, 'apply'])->name('jobs.apply');
    Route::post('/jobs/{id}/withdraw',           [JobApplicationController::class, 'withdraw'])->name('jobs.withdraw');
    Route::get('/jobs/{id}/applicants',          [JobApplicationController::class, 'applicants'])->name('jobs.applicants');

    Route::post('/applications/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('jobs.application.status');

    Route::delete('/jobs/{id}',                 [JobController::class, 'destroy'])->name('jobs.delete')->middleware('role:alumni,teacher');
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
    Route::post('/heartbeat', [PostController::class, 'heartbeat'])->name('heartbeat');
    Route::get('/friends/messenger-contacts', [\App\Http\Controllers\PostController::class, 'messengerContacts'])
        ->name('friends.messengerContacts');

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
        Route::get('/blocked', [FriendController::class, 'blockedList'])->name('blocked');
        Route::get('/{userId}/mutual',          [FriendController::class, 'mutualFriends'])->name('mutual');
        Route::get('/status/{userId}',          [FriendController::class, 'statusWith'])->name('status');

        Route::post('/send',                    [FriendController::class, 'sendRequest'])->name('send');
        Route::post('/accept',                  [FriendController::class, 'acceptRequest'])->name('accept');
        Route::post('/decline',                 [FriendController::class, 'declineRequest'])->name('decline');
        Route::post('/cancel',                  [FriendController::class, 'cancelRequest'])->name('cancel');
        Route::post('/unfriend',                [FriendController::class, 'unfriend'])->name('unfriend');
        Route::post('/block',                   [FriendController::class, 'block'])->name('block');
        Route::post('/unblock',                 [FriendController::class, 'unblock'])->name('unblock');
        Route::post('/not-interested',          [FriendController::class, 'notInterested']);
    });

    // ---------- MESSAGING ----------
    Route::post('/message/send', [MessageController::class, 'send'])->name('message.send');
    Route::get('/message/thread/{userId}', [MessageController::class, 'thread'])->name('message.thread');
    Route::get('/message/unread-count', [MessageController::class, 'unreadCount'])->name('message.unreadCount');
    Route::get('/message/conversations', [MessageController::class, 'conversations'])->name('message.conversations');
    Route::post('/message/{id}/edit', [MessageController::class, 'editMessage'])->name('message.edit');
    Route::post('/message/{id}/delete', [MessageController::class, 'deleteMessage'])->name('message.delete');
    Route::post('/message/{id}/react', [MessageController::class, 'reactMessage'])->name('message.react');
    Route::post('/message/share', [MessageController::class, 'shareToMessenger'])->name('message.share');

    Route::post('/message/{id}/forward', [MessageController::class, 'forwardMessage'])->name('message.forward');
    Route::get('/message/thread/{userId}/older', [MessageController::class, 'olderMessages'])->name('message.thread.older');
    Route::get('/message/thread/{userId}/search', [MessageController::class, 'searchThread'])->name('message.thread.search');
    Route::get('/message/thread/{userId}/media', [MessageController::class, 'threadMedia'])->name('message.thread.media');

    Route::get('/message/user-status/{id}', [\App\Http\Controllers\MessageController::class, 'userStatus']);

    Route::post('/message/conversations/{userId}/mute', [MessageController::class, 'toggleMute'])->name('message.conversations.mute');
    Route::post('/message/conversations/{userId}/delete-chat', [MessageController::class, 'deleteChatForMe'])->name('message.conversations.deleteChat');
    Route::get('/message/conversations/{userId}/export', [MessageController::class, 'exportChatHtml'])->name('message.conversations.export');

    // ---------- REPORT ----------
    Route::post('/report',                      [ReportController::class, 'store'])->name('report.store');

    // ---------- NOTIFICATIONS ----------
    Route::get('/notifications',            [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/all',        [NotificationController::class, 'all'])->name('notifications.all');
    Route::get('/notifications/poll',       [NotificationController::class, 'poll'])->name('notifications.poll');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markOneRead'])->name('notifications.read');
    Route::post('/notifications/read-all',  [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
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