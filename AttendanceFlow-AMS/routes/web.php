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

        // Calendar
        Route::get('/calendar', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('admin.calendar.index');

        // Attendance Selection
        Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('admin.attendance.index');
    });

    // Student Group
    Route::group(['prefix' => 'student', 'middleware' => ['role:student']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
        
        // My Justifications
        Route::get('/justifications', [\App\Http\Controllers\Student\JustificationController::class, 'index'])->name('student.justifications.index');
        Route::post('/justifications', [\App\Http\Controllers\Student\JustificationController::class, 'store'])->name('student.justifications.store');
    });
});
