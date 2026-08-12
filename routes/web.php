<?php

use App\Http\Controllers\Admin\AttendanceAdminController;
use App\Http\Controllers\Admin\ConsultationAdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Site\ConsultationController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website trung tâm (công khai)
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('site.home');
Route::get('/gioi-thieu', [PageController::class, 'about'])->name('site.about');
Route::get('/khoa-hoc', [PageController::class, 'courses'])->name('site.courses');
Route::get('/khoa-hoc/{slug}', [PageController::class, 'course'])->name('site.course');
Route::get('/giang-vien', [PageController::class, 'teachers'])->name('site.teachers');
Route::get('/lich-khai-giang', [PageController::class, 'schedule'])->name('site.schedule');
Route::get('/cam-nang', [PageController::class, 'posts'])->name('site.posts');
Route::get('/cam-nang/{slug}', [PageController::class, 'post'])->name('site.post');
Route::get('/lien-he', [PageController::class, 'contact'])->name('site.contact');

Route::get('/sitemap.xml', SitemapController::class)->name('site.sitemap');

Route::post('/dang-ky-tu-van', [ConsultationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('site.consult');

/*
|--------------------------------------------------------------------------
| Khu vực nội bộ: đăng nhập, điểm danh, quản trị
|--------------------------------------------------------------------------
*/
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

    Route::get('/consultations', [ConsultationAdminController::class, 'index'])->name('consultations');
    Route::get('/consultations/export', [ConsultationAdminController::class, 'export'])->name('consultations.export');
    Route::post('/consultations/{consultation}', [ConsultationAdminController::class, 'update'])->name('consultations.update');
});
