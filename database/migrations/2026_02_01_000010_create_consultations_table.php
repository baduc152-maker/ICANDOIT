<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lưu yêu cầu tư vấn gửi từ website trung tâm (form "Đăng ký tư vấn").
     */
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('course')->nullable();
            $table->text('note')->nullable();

            // new | contacted | enrolled | closed
            $table->string('status')->default('new');
            $table->text('staff_note')->nullable();
            $table->timestamp('contacted_at')->nullable();

            $table->string('source')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
