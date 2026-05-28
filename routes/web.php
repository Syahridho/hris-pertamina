<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Manpower\AttendanceController as ManpowerAttendanceController;
use App\Http\Controllers\Manpower\DashboardController as ManpowerDashboardController;
use App\Http\Controllers\Manpower\SubmissionController as ManpowerSubmissionController;
use App\Http\Controllers\Supervisor\AttendanceController as SupervisorAttendanceController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use App\Http\Controllers\Supervisor\SubmissionController as SupervisorSubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Root — Redirect ke panel yang sesuai
|--------------------------------------------------------------------------
*/
Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('landing');

/*
|--------------------------------------------------------------------------
| Authentication — Single login page untuk semua role
|--------------------------------------------------------------------------
*/
Route::middleware('web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Supervisor Panel — /supervisor/*
|--------------------------------------------------------------------------
*/
Route::prefix('supervisor')
    ->middleware(['web', 'auth.supervisor'])
    ->name('supervisor.')
    ->group(function () {
        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');

        Route::get('/attendance', [SupervisorAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/history', [SupervisorAttendanceController::class, 'history'])->name('attendance.history');
        Route::get('/clock', [SupervisorAttendanceController::class, 'clock'])->name('clock');
        Route::post('/clock/in', [SupervisorAttendanceController::class, 'clockIn'])->name('clock.in');
        Route::post('/clock/out', [SupervisorAttendanceController::class, 'clockOut'])->name('clock.out');

        Route::get('/submissions', [SupervisorSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [SupervisorSubmissionController::class, 'show'])->name('submissions.show');
        Route::post('/submissions/{submission}/approve', [SupervisorSubmissionController::class, 'approve'])->name('submissions.approve');
        Route::post('/submissions/{submission}/reject', [SupervisorSubmissionController::class, 'reject'])->name('submissions.reject');
    });

/*
|--------------------------------------------------------------------------
| Manpower Panel — /manpower/*
|--------------------------------------------------------------------------
*/
Route::prefix('manpower')
    ->middleware(['web', 'auth.manpower'])
    ->name('manpower.')
    ->group(function () {
        Route::get('/dashboard', [ManpowerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/clock', [ManpowerAttendanceController::class, 'clock'])->name('clock');
        Route::post('/clock/in', [ManpowerAttendanceController::class, 'clockIn'])->name('clock.in');
        Route::post('/clock/out', [ManpowerAttendanceController::class, 'clockOut'])->name('clock.out');

        Route::get('/attendance/history', [ManpowerAttendanceController::class, 'history'])->name('attendance.history');

        // Submissions — 'create' harus didefinisikan SEBELUM '{submission}' agar tidak tertangkap
        Route::get('/submissions', [ManpowerSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/create', [ManpowerSubmissionController::class, 'create'])->name('submissions.create');
        Route::post('/submissions', [ManpowerSubmissionController::class, 'store'])->name('submissions.store');
        Route::get('/submissions/{submission}', [ManpowerSubmissionController::class, 'show'])->name('submissions.show');
        Route::delete('/submissions/{submission}', [ManpowerSubmissionController::class, 'destroy'])->name('submissions.destroy');
    });

/*
|--------------------------------------------------------------------------
| Superadmin redirect — Filament menangani /admin secara otomatis
|--------------------------------------------------------------------------
*/
Route::get('/admin/redirect', function () {
    return redirect('/admin');
})->name('superadmin.dashboard')->middleware(['web']);
