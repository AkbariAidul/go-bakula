<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Reports\UpvoteController;
use App\Http\Controllers\Departments\DepartmentController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Gamification\LeaderboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    return Inertia::render('Welcome');
});

// Auth routes (will be added by Breeze)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::get('/my-reports', [ReportController::class, 'myReports'])->name('my-reports');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
        Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
        
        // Admin only
        Route::middleware('role:super_admin|admin_dinas')->group(function () {
            Route::patch('/{report}/status', [ReportController::class, 'updateStatus'])->name('update-status');
            Route::post('/{report}/completion', [ReportController::class, 'uploadCompletion'])->name('upload-completion');
        });
    });
    
    // Upvotes
    Route::post('/reports/{reportId}/upvote', [UpvoteController::class, 'toggle'])->name('upvotes.toggle');
    
    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin routes
    Route::middleware('role:super_admin')->group(function () {
        // Departments
        Route::resource('departments', DepartmentController::class)->except(['show', 'create', 'edit']);
        
        // Categories
        Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
    });
});

require __DIR__.'/auth.php';
