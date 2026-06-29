<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ghi danh: một học viên đăng ký một khóa học, kèm học phí phải thu
     * (đã trừ giảm giá). Các phiếu thu học phí sẽ tham chiếu tới bản ghi này
     * để theo dõi công nợ còn lại.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->date('enrolled_on');
            // Học phí áp dụng cho lần ghi danh này (mặc định lấy theo khóa).
            $table->decimal('tuition_amount', 15, 2)->default(0);
            // Giảm giá / học bổng (VND).
            $table->decimal('discount', 15, 2)->default(0);
            // active (đang học) | completed (hoàn thành) | cancelled (đã hủy)
            $table->string('status')->default('active');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('enrolled_on');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
