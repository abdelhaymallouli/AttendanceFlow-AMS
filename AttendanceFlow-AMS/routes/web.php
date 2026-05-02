<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Routes (Role Protected)
Route::group(['middleware' => ['auth']], function () {
    // Teacher Group
    Route::group(['prefix' => 'teacher', 'middleware' => ['role:teacher']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('teacher.dashboard');
        
        // Attendance Routes
        Route::get('/sessions/{session}/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'show'])->name('teacher.sessions.attendance.show');
        Route::post('/sessions/{session}/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('teacher.sessions.attendance.store');
    });

    // Admin Group
    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Justification Management
        Route::get('/justifications', [\App\Http\Controllers\Admin\JustificationController::class, 'index'])->name('admin.justifications.index');
        Route::patch('/justifications/{justification}', [\App\Http\Controllers\Admin\JustificationController::class, 'update'])->name('admin.justifications.update');

        // Reporting & Analytics
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');

        // Student Management
        Route::get('/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('admin.students.index');

        // Session Management (Replaces Calendar)
        Route::get('/sessions', [\App\Http\Controllers\Admin\SessionController::class, 'index'])->name('admin.sessions.index');
        Route::get('/sessions/create', [\App\Http\Controllers\Admin\SessionController::class, 'create'])->name('admin.sessions.create');
        Route::post('/sessions', [\App\Http\Controllers\Admin\SessionController::class, 'store'])->name('admin.sessions.store');
        Route::get('/sessions/{session}/edit', [\App\Http\Controllers\Admin\SessionController::class, 'edit'])->name('admin.sessions.edit');
        Route::put('/sessions/{session}', [\App\Http\Controllers\Admin\SessionController::class, 'update'])->name('admin.sessions.update');
        Route::delete('/sessions/{session}', [\App\Http\Controllers\Admin\SessionController::class, 'destroy'])->name('admin.sessions.destroy');

        // Attendance Selection
        Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('admin.attendance.index');
        Route::get('/attendance/{session}', [\App\Http\Controllers\Admin\AttendanceController::class, 'show'])->name('admin.attendance.show');
        Route::post('/attendance/{session}', [\App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('admin.attendance.store');
    });

    // Student Group
    Route::group(['prefix' => 'student', 'middleware' => ['role:student']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
        
        // My Justifications
        Route::get('/justifications', [\App\Http\Controllers\Student\JustificationController::class, 'index'])->name('student.justifications.index');
        Route::post('/justifications', [\App\Http\Controllers\Student\JustificationController::class, 'store'])->name('student.justifications.store');
    });
});
