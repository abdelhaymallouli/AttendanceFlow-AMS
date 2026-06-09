<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\LoginController;
use App\Http\Controllers\Mobile\StudentController;
use App\Http\Controllers\Mobile\AttendanceController;

/*
|--------------------------------------------------------------------------
| Mobile Web Routes — Student Only
|--------------------------------------------------------------------------
*/

$auth = function ($request, $next) {
    if (!session()->has('mobile_token')) {
        return redirect()->route('mobile.login');
    }
    return $next($request);
};

Route::prefix('mobile')->group(function () {
    // Auth
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('mobile.login');
    Route::post('/login', [LoginController::class, 'login'])->name('mobile.login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('mobile.logout');

    // Protected student routes
    Route::middleware($auth)->group(function () {
        Route::get('/', [StudentController::class, 'home'])->name('mobile.home');

        // Absences
        Route::get('/absences', [StudentController::class, 'absences'])->name('mobile.absences');

        // Justifications
        Route::get('/justifications', [StudentController::class, 'justifications'])->name('mobile.justifications');
        Route::get('/justifications/create', [StudentController::class, 'justificationsCreate'])->name('mobile.justifications.create');
        Route::post('/justifications', [StudentController::class, 'justificationsStore'])->name('mobile.justifications.store');

        // Sessions
        Route::get('/sessions', [StudentController::class, 'sessions'])->name('mobile.sessions');
        Route::get('/sessions/{id}', [StudentController::class, 'sessionDetail'])->name('mobile.session.detail');

        // QR Scan
        Route::get('/scan', [AttendanceController::class, 'scan'])->name('mobile.scan');
        Route::post('/scan/submit', [AttendanceController::class, 'submitScan'])->name('mobile.scan.submit');
        Route::post('/scan/sync', [AttendanceController::class, 'syncOffline'])->name('mobile.scan.sync');
    });
});

Route::get('/', fn() => redirect()->route('mobile.login'));
