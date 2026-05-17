<?php
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

// Group for Admin Protected Routes (শুধু এডমিন ঢুকতে পারবে)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Group for Student Protected Routes (শুধু স্টুডেন্ট ঢুকতে পারবে)
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});


// Group for Alumni Protected Routes (শুধু এলামনাই ঢুকতে পারবে)
Route::middleware(['auth', 'role:alumni'])->prefix('alumni')->group(function () {
    Route::get('/dashboard', function () {
        return view('alumni.dashboard');
    })->name('alumni.dashboard');
});

require __DIR__.'/auth.php';