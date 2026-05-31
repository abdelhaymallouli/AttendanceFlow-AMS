<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\SessionController;
use App\Http\Controllers\Mobile\AttendanceController;
use App\Http\Controllers\Mobile\LoginController;

/*
|--------------------------------------------------------------------------
| Mobile Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('mobile')->group(function () {
    // Auth Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('mobile.login');
    Route::post('/login', [LoginController::class, 'login'])->name('mobile.login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('mobile.logout');

    // Secure Portal Routes
    Route::middleware([function ($request, $next) {
        if (!session()->has('mobile_token')) {
            return redirect()->route('mobile.login');
        }
        return $next($request);
    }])->group(function () {
        // Hub route (Portal Selection)
        Route::get('/', function () {
            return view('mobile.hub');
        })->name('mobile.hub');

        // Teacher routes
        Route::get('/teacher', [SessionController::class, 'index'])->name('mobile.sessions');
        Route::get('/session/{id}', [SessionController::class, 'show'])->name('mobile.session.show');
        Route::get('/flash/{id}', [SessionController::class, 'flash'])->name('mobile.attendance.flash');
        Route::post('/attendance/record', [AttendanceController::class, 'record'])->name('mobile.attendance.record');
        
        // Admin routes
        Route::get('/admin', [\App\Http\Controllers\Mobile\AdminController::class, 'dashboard'])->name('mobile.admin.dashboard');
        
        // Student routes
        Route::get('/student/{id?}', [\App\Http\Controllers\Mobile\StudentController::class, 'dashboard'])->name('mobile.student.dashboard');
    });
});

// Redirect root to mobile hub
Route::get('/', function () {
    return redirect()->route('mobile.hub');
});
