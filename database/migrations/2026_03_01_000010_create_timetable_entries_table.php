<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thời khoá biểu: các buổi dạy lặp lại hằng tuần của từng giảng viên.
     * Mỗi dòng là một buổi cố định trong tuần, có hiệu lực trong một khoảng ngày.
     */
    public function up(): void
    {
        Schema::create('timetable_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('class_name');
            $table->string('subject')->nullable();

            // 1 = Thứ 2 … 7 = Chủ nhật.
            $table->unsignedTinyInteger('weekday');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();

            $table->date('effective_from');
            $table->date('effective_to')->nullable();

            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'weekday']);
            $table->index('weekday');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_entries');
    }
};
