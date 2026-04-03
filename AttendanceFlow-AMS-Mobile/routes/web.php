<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\SessionController;
use App\Http\Controllers\Mobile\AttendanceController;

/*
|--------------------------------------------------------------------------
| Mobile Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register mobile routes for the application. These
| routes consume the AttendanceFlow-AMS API.
|
*/

Route::prefix('mobile')->group(function () {
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

// Redirect root to mobile hub
Route::get('/', function () {
    return redirect()->route('mobile.hub');
});
