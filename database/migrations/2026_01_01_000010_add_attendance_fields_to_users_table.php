<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bổ sung các trường phục vụ điểm danh & đồng bộ ICANDOIT cho bảng users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Phân quyền: 'admin' (quản lý) hoặc 'employee' (nhân viên).
            $table->string('role')->default('employee')->after('email');
            // Mã nhân viên dùng để khớp với hệ thống ICANDOIT.
            $table->string('employee_code')->nullable()->unique()->after('role');
            $table->string('department')->nullable()->after('employee_code');
            $table->boolean('is_active')->default(true)->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'employee_code', 'department', 'is_active']);
        });
    }
};
