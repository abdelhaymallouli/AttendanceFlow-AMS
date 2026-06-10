<?php

use App\Http\Controllers\Api\AcademicController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JustificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth Routes
Route::post('login', [AuthController::class, 'login']);

Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::get('me/profile', [AuthController::class, 'meProfile'])->middleware('auth:sanctum');
Route::post('logout', [AuthController::class, 'logout']);

// Academic Routes
Route::prefix('academic')->group(function () {
    Route::get('filieres', [AcademicController::class, 'getFilieres']);
    Route::get('groups', [AcademicController::class, 'getGroups']);
    Route::get('modules', [AcademicController::class, 'getModules']);
    Route::get('session/{id}', [AcademicController::class, 'getSession']);
    Route::get('sessions', [AcademicController::class, 'getSessions']);
    Route::get('sessions/teacher/{id}', [AcademicController::class, 'getTeacherSessions']);
    Route::get('sessions/group/{groupId}', [AcademicController::class, 'getGroupSessions']);
});

// Attendance Routes
Route::prefix('attendance')->group(function () {
    Route::get('student/{id}', [AttendanceController::class, 'getStudentAttendance']);
    Route::get('session/{id}', [AttendanceController::class, 'getSessionAttendance']);
    Route::post('record', [AttendanceController::class, 'recordAttendance']);

    // QR attendance (multi-factor: HMAC + GPS + Wi-Fi + device fingerprint)
    Route::middleware('auth:sanctum')->prefix('qr')->group(function () {
        Route::get('pre-check', [\App\Http\Controllers\Api\QrAttendanceController::class, 'preCheck']);
        Route::get('token/{sessionId}', [\App\Http\Controllers\Api\QrAttendanceController::class, 'issueToken']);
        Route::post('scan', [\App\Http\Controllers\Api\QrAttendanceController::class, 'scan']);
        Route::post('sync-offline', [\App\Http\Controllers\Api\QrAttendanceController::class, 'syncOffline']);
    });
});

// Justification Routes
Route::prefix('justifications')->group(function () {
    Route::get('pending', [JustificationController::class, 'getPending']);
    Route::get('student/{id}', [JustificationController::class, 'getStudentJustifications']);
    Route::post('submit', [JustificationController::class, 'submit']);
});

// Stats Routes
Route::get('stats/admin', [\App\Http\Controllers\Api\StatsController::class, 'getAdminStats']);
Route::get('stats/student/{id}', [\App\Http\Controllers\Api\StatsController::class, 'getStudentStats']);

// Notifications Routes (auth required, scoped to current user)
Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::get('/unread', [\App\Http\Controllers\Api\NotificationController::class, 'unread']);
    Route::get('/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
    Route::post('/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead'])->whereNumber('id');
    Route::post('/mark-all-as-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    Route::post('/clear-all', [\App\Http\Controllers\Api\NotificationController::class, 'clearAllNotifications']);
});
