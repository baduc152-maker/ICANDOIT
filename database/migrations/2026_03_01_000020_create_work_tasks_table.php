<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Công việc được giao cho từng nhân sự trong một ngày cụ thể.
     * Hiển thị chung màn lịch với các buổi dạy lấy từ thời khoá biểu.
     */
    public function up(): void
    {
        Schema::create('work_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->date('work_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // normal | high
            $table->string('priority')->default('normal');
            // todo | done | cancelled
            $table->string('status')->default('todo');
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'work_date']);
            $table->index('work_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_tasks');
    }
};
