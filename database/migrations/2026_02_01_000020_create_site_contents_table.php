<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lưu nội dung website do quản trị viên chỉnh sửa trong trang quản trị.
     * Mỗi bản ghi là một nhóm nội dung (thương hiệu, khoá học, giảng viên...).
     * Nhóm nào chưa được sửa thì website dùng giá trị mặc định ở config/center.php.
     */
    public function up(): void
    {
        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section')->unique();
            $table->json('payload');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_contents');
    }
};
