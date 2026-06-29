<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Khóa học / lớp học của trung tâm cùng mức học phí chuẩn.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            // Học phí chuẩn của khóa (VND).
            $table->decimal('fee', 15, 2)->default(0);
            // Số buổi học của khóa.
            $table->unsignedInteger('sessions')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
