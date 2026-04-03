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

Route::get('me', [AuthController::class, 'me']);
Route::post('logout', [AuthController::class, 'logout']);

// Academic Routes
Route::prefix('academic')->group(function () {
    Route::get('filieres', [AcademicController::class, 'getFilieres']);
    Route::get('groups', [AcademicController::class, 'getGroups']);
    Route::get('modules', [AcademicController::class, 'getModules']);
    Route::get('session/{id}', [AcademicController::class, 'getSession']);
    Route::get('sessions', [AcademicController::class, 'getSessions']);
    Route::get('sessions/teacher/{id}', [AcademicController::class, 'getTeacherSessions']);
});

// Attendance Routes
Route::prefix('attendance')->group(function () {
    Route::get('student/{id}', [AttendanceController::class, 'getStudentAttendance']);
    Route::get('session/{id}', [AttendanceController::class, 'getSessionAttendance']);
    Route::post('record', [AttendanceController::class, 'recordAttendance']);
});

// Justification Routes
Route::prefix('justifications')->group(function () {
    Route::get('pending', [JustificationController::class, 'getPending']);
    Route::post('submit', [JustificationController::class, 'submit']);
});

// Stats Routes
Route::get('stats/admin', [\App\Http\Controllers\Api\StatsController::class, 'getAdminStats']);
Route::get('stats/student/{id}', [\App\Http\Controllers\Api\StatsController::class, 'getStudentStats']);
