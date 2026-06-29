<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sổ quỹ thu / chi — trái tim của phần mềm kế toán. Mỗi dòng là một
     * phiếu thu (income) hoặc phiếu chi (expense). Phiếu thu học phí có thể
     * tham chiếu tới học viên và lần ghi danh tương ứng.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Số phiếu, ví dụ: PT-20260629-0001 (thu) / PC-20260629-0001 (chi).
            $table->string('code')->unique();
            // income (thu) | expense (chi)
            $table->string('type');
            $table->foreignId('category_id')->nullable()->constrained('transaction_categories')->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->date('occurred_on');
            // cash (tiền mặt) | bank (chuyển khoản) | other
            $table->string('payment_method')->default('cash');
            $table->string('description')->nullable();

            // Liên kết tùy chọn tới học viên / ghi danh (cho phiếu thu học phí).
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();

            // Người lập phiếu.
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('type');
            $table->index('occurred_on');
            $table->index(['type', 'occurred_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
