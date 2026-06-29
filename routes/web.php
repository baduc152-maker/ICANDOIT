<?php

use App\Http\Controllers\Admin\AccountingController;
use App\Http\Controllers\Admin\AttendanceAdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TransactionCategoryController;
use App\Http\Controllers\Admin\TransactionController;
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

    // ---- Kế toán ----
    Route::get('/ke-toan', [AccountingController::class, 'dashboard'])->name('accounting');
    Route::get('/ke-toan/bao-cao', [AccountingController::class, 'report'])->name('accounting.report');
    Route::get('/ke-toan/xuat-csv', [AccountingController::class, 'export'])->name('accounting.export');

    // Sổ quỹ thu / chi.
    Route::get('/thu-chi/tao', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/thu-chi', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/thu-chi/{transaction}/sua', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/thu-chi/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/thu-chi/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/thu-chi', [TransactionController::class, 'index'])->name('transactions.index');

    // Học viên.
    Route::resource('students', StudentController::class)->except(['show'])->names('students');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

    // Khóa học.
    Route::resource('courses', CourseController::class)->except(['show'])->names('courses');

    // Ghi danh + thu học phí.
    Route::post('/enrollments/{enrollment}/pay', [EnrollmentController::class, 'pay'])->name('enrollments.pay');
    Route::resource('enrollments', EnrollmentController::class)->names('enrollments');

    // Danh mục thu / chi.
    Route::get('/categories', [TransactionCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [TransactionCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [TransactionCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [TransactionCategoryController::class, 'destroy'])->name('categories.destroy');
});
