<?php

use App\Http\Controllers\Admin\AttendanceAdminController;
use App\Http\Controllers\Admin\ConsultationAdminController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\WorkTaskController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\Site\ConsultationController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\SitemapController;
use App\Http\Controllers\WorkScheduleController;
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
| Trang cài đặt một lần (tự khoá sau khi cài xong)
|--------------------------------------------------------------------------
*/
Route::get('/cai-dat', [InstallController::class, 'show'])->name('install');
Route::post('/cai-dat', [InstallController::class, 'store'])->name('install.store');

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

    // Lịch công việc: buổi dạy theo thời khoá biểu + công việc được giao.
    Route::get('/lich-cong-viec', [WorkScheduleController::class, 'index'])->name('schedule');
    Route::post('/lich-cong-viec/{task}/trang-thai', [WorkScheduleController::class, 'toggleTask'])->name('schedule.task.toggle');
});

// Khu vực quản trị (yêu cầu quyền admin).
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/attendance', [AttendanceAdminController::class, 'index'])->name('attendance');
    Route::get('/attendance/export', [AttendanceAdminController::class, 'export'])->name('attendance.export');
    Route::post('/attendance/{attendance}/resync', [AttendanceAdminController::class, 'resync'])->name('attendance.resync');

    // Trang nhập và chỉnh sửa nội dung website.
    Route::get('/noi-dung', [ContentController::class, 'index'])->name('content');
    Route::get('/noi-dung/{section}', [ContentController::class, 'edit'])->name('content.edit');
    Route::put('/noi-dung/{section}', [ContentController::class, 'update'])->name('content.update');
    Route::post('/noi-dung/{section}/khoi-phuc', [ContentController::class, 'reset'])->name('content.reset');

    // Thời khoá biểu.
    Route::get('/thoi-khoa-bieu', [TimetableController::class, 'index'])->name('timetable');
    Route::get('/thoi-khoa-bieu/them', [TimetableController::class, 'create'])->name('timetable.create');
    Route::post('/thoi-khoa-bieu', [TimetableController::class, 'store'])->name('timetable.store');
    Route::get('/thoi-khoa-bieu/{timetable}', [TimetableController::class, 'edit'])->name('timetable.edit');
    Route::put('/thoi-khoa-bieu/{timetable}', [TimetableController::class, 'update'])->name('timetable.update');
    Route::delete('/thoi-khoa-bieu/{timetable}', [TimetableController::class, 'destroy'])->name('timetable.destroy');

    // Giao việc.
    Route::get('/cong-viec', [WorkTaskController::class, 'index'])->name('tasks');
    Route::get('/cong-viec/them', [WorkTaskController::class, 'create'])->name('tasks.create');
    Route::post('/cong-viec', [WorkTaskController::class, 'store'])->name('tasks.store');
    Route::get('/cong-viec/{task}', [WorkTaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/cong-viec/{task}', [WorkTaskController::class, 'update'])->name('tasks.update');
    Route::delete('/cong-viec/{task}', [WorkTaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/consultations', [ConsultationAdminController::class, 'index'])->name('consultations');
    Route::get('/consultations/export', [ConsultationAdminController::class, 'export'])->name('consultations.export');
    Route::post('/consultations/{consultation}', [ConsultationAdminController::class, 'update'])->name('consultations.update');
});
