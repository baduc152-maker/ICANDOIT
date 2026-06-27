<?php

use App\Http\Controllers\Admin\AttendanceAdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Trang chủ điều hướng theo trạng thái đăng nhập.
Route::get('/', fn () => redirect()->route(auth()->check() ? 'dashboard' : 'login'));

// Đăng nhập / đăng xuất.
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Khu vực nhân viên (yêu cầu đăng nhập).
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
});

// Khu vực quản trị (yêu cầu quyền admin).
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/attendance', [AttendanceAdminController::class, 'index'])->name('attendance');
    Route::get('/attendance/export', [AttendanceAdminController::class, 'export'])->name('attendance.export');
    Route::post('/attendance/{attendance}/resync', [AttendanceAdminController::class, 'resync'])->name('attendance.resync');
});
