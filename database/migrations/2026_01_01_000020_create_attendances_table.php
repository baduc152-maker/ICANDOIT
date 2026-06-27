<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mỗi nhân viên có tối đa một bản ghi điểm danh cho mỗi ngày làm việc.
     * Bản ghi lưu thời điểm check-in / check-out cùng trạng thái đồng bộ ICANDOIT.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');

            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();

            $table->string('check_in_ip')->nullable();
            $table->string('check_out_ip')->nullable();

            // on_time | late — đánh giá dựa trên giờ vào chuẩn cấu hình.
            $table->string('status')->nullable();
            $table->text('note')->nullable();

            // Trạng thái đồng bộ sang ICANDOIT: pending | synced | failed.
            $table->string('sync_status')->default('pending');
            $table->timestamp('synced_at')->nullable();
            $table->string('icandoit_ref')->nullable();
            $table->text('sync_message')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'work_date']);
            $table->index('work_date');
            $table->index('sync_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
