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

    // Notification endpoints (session-auth, used by the bell + popup in the layout)
    Route::prefix('web/notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\Web\NotificationController::class, 'index']);
        Route::get('/unread', [\App\Http\Controllers\Web\NotificationController::class, 'unread']);
        Route::get('/unread-count', [\App\Http\Controllers\Web\NotificationController::class, 'unreadCount']);
        Route::post('/{id}/read', [\App\Http\Controllers\Web\NotificationController::class, 'markAsRead'])->whereNumber('id');
        Route::post('/mark-all-as-read', [\App\Http\Controllers\Web\NotificationController::class, 'markAllAsRead']);
        Route::post('/clear-all', [\App\Http\Controllers\Web\NotificationController::class, 'clearAll']);
    });

    // Teacher Group
    Route::group(['prefix' => 'teacher', 'middleware' => ['role:teacher']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('teacher.dashboard');

        // Timetable (Emploi du temps)
        Route::get('/timetable', [\App\Http\Controllers\Teacher\TimetableController::class, 'index'])->name('teacher.timetable.index');
        Route::get('/timetable/request', [\App\Http\Controllers\Teacher\TimetableController::class, 'createRequest'])->name('teacher.timetable.create-request');
        Route::post('/timetable/request', [\App\Http\Controllers\Teacher\TimetableController::class, 'storeRequest'])->name('teacher.timetable.store-request');

        // Session Management (Create own sessions) — kept for legacy direct create flow
        Route::get('/sessions/create', [\App\Http\Controllers\Teacher\SessionController::class, 'create'])->name('teacher.sessions.create');
        Route::post('/sessions', [\App\Http\Controllers\Teacher\SessionController::class, 'store'])->name('teacher.sessions.store');

        // Attendance Routes
        Route::get('/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('teacher.attendance.index');
        Route::get('/sessions/{session}/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'show'])->name('teacher.sessions.attendance.show');
        Route::post('/sessions/{session}/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('teacher.sessions.attendance.store');

        // QR Display
        Route::get('/sessions/{session}/qr', [\App\Http\Controllers\Teacher\QrSessionController::class, 'show'])->name('teacher.sessions.qr.show');
        Route::post('/sessions/{session}/qr/reinitialize', [\App\Http\Controllers\Teacher\QrSessionController::class, 'reinitialize'])->name('teacher.sessions.qr.reinitialize');

        // Live scan telemetry (JSON, polled)
        Route::get('/sessions/{session}/live-scans', [\App\Http\Controllers\Teacher\QrSessionController::class, 'liveScans'])
            ->name('teacher.sessions.live-scans');

        // My Students (read-only with phone numbers)
        Route::get('/students', [\App\Http\Controllers\Teacher\StudentController::class, 'index'])->name('teacher.students.index');
    });

    // Admin Group
    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        // Export Routes
        Route::get('/export/students', [\App\Http\Controllers\Admin\ExportController::class, 'exportStudents'])->name('admin.export.students');
        Route::get('/export/attendance', [\App\Http\Controllers\Admin\ExportController::class, 'exportAttendance'])->name('admin.export.attendance');
        Route::get('/export/sessions', [\App\Http\Controllers\Admin\ExportController::class, 'exportSessions'])->name('admin.export.sessions');

        // Justification Management
        Route::get('/justifications', [\App\Http\Controllers\Admin\JustificationController::class, 'index'])->name('admin.justifications.index');
        Route::patch('/justifications/{justification}', [\App\Http\Controllers\Admin\JustificationController::class, 'update'])->name('admin.justifications.update');

        // Student Management (legacy read-only)
        Route::get('/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('admin.students.index');

        // User Management (unified)
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'createStudent'])->name('admin.users.create-student');
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'storeStudent'])->name('admin.users.store-student');
        Route::get('/users/teachers/create', [\App\Http\Controllers\Admin\UserController::class, 'createTeacher'])->name('admin.users.create-teacher');
        Route::post('/users/teachers', [\App\Http\Controllers\Admin\UserController::class, 'storeTeacher'])->name('admin.users.store-teacher');
        Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

        // Timetable (Emploi du temps) — admin has full access
        Route::get('/timetable', [\App\Http\Controllers\Admin\TimetableController::class, 'index'])->name('admin.timetable.index');
        Route::get('/timetable/requests', [\App\Http\Controllers\Admin\TimetableController::class, 'requests'])->name('admin.timetable.requests');
        Route::post('/timetable/requests/{changeRequest}/approve', [\App\Http\Controllers\Admin\TimetableController::class, 'approveRequest'])->name('admin.timetable.requests.approve');
        Route::post('/timetable/requests/{changeRequest}/reject', [\App\Http\Controllers\Admin\TimetableController::class, 'rejectRequest'])->name('admin.timetable.requests.reject');
        Route::get('/timetable/create', [\App\Http\Controllers\Admin\TimetableController::class, 'create'])->name('admin.timetable.create');
        Route::post('/timetable', [\App\Http\Controllers\Admin\TimetableController::class, 'store'])->name('admin.timetable.store');
        Route::get('/timetable/{session}/edit', [\App\Http\Controllers\Admin\TimetableController::class, 'edit'])->name('admin.timetable.edit');
        Route::put('/timetable/{session}', [\App\Http\Controllers\Admin\TimetableController::class, 'update'])->name('admin.timetable.update');
        Route::delete('/timetable/{session}', [\App\Http\Controllers\Admin\TimetableController::class, 'destroy'])->name('admin.timetable.destroy');
        Route::get('/timetable/validate', [\App\Http\Controllers\Admin\TimetableController::class, 'validateSession'])->name('admin.timetable.validate');

        // Legacy sessions resource (kept for backward compatibility)
        Route::resource('sessions', \App\Http\Controllers\Admin\SessionController::class)->names('admin.sessions');

        // Attendance Marking
        Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('admin.attendance.index');
        Route::get('/attendance/{session}', [\App\Http\Controllers\Admin\AttendanceController::class, 'show'])->name('admin.attendance.show');
        Route::post('/attendance/{session}', [\App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('admin.attendance.store');
        Route::patch('/attendance/{session}/row', [\App\Http\Controllers\Admin\AttendanceController::class, 'updateRow'])->name('admin.attendance.update-row');

        // QR workspace — admin can drive QR for ANY group of the school
        Route::get('/qr', [\App\Http\Controllers\Admin\QrController::class, 'index'])->name('admin.qr.index');
        Route::get('/sessions/{session}/qr', [\App\Http\Controllers\Teacher\QrSessionController::class, 'show'])->name('admin.sessions.qr.show');
        Route::post('/sessions/{session}/qr/reinitialize', [\App\Http\Controllers\Teacher\QrSessionController::class, 'reinitialize'])->name('admin.sessions.qr.reinitialize');
        Route::get('/sessions/{session}/live-scans', [\App\Http\Controllers\Teacher\QrSessionController::class, 'liveScans'])->name('admin.sessions.live-scans');

        // QR Settings & Attendance Logs
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
        Route::get('/logs', [\App\Http\Controllers\Admin\AttendanceLogController::class, 'index'])->name('admin.logs.index');
    });

    // Student Group
    Route::group(['prefix' => 'student', 'middleware' => ['role:student']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');

        // My Timetable (Emploi du temps)
        Route::get('/timetable', [\App\Http\Controllers\Student\TimetableController::class, 'index'])->name('student.timetable.index');

        // My Justifications
        Route::get('/justifications', [\App\Http\Controllers\Student\JustificationController::class, 'index'])->name('student.justifications.index');
        Route::get('/justifications/create', [\App\Http\Controllers\Student\JustificationController::class, 'create'])->name('student.justifications.create');
        Route::post('/justifications', [\App\Http\Controllers\Student\JustificationController::class, 'store'])->name('student.justifications.store');

        // QR Scan (web fallback; primary path is the mobile app)
        Route::get('/scan', [\App\Http\Controllers\Student\QrScanController::class, 'show'])->name('student.qr.scan');
    });
});
