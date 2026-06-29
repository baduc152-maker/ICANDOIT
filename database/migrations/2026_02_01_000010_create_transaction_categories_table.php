<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Danh mục thu / chi — phân loại các khoản trong sổ quỹ kế toán
     * (ví dụ: Học phí, Lương giáo viên, Tiền thuê mặt bằng...).
     */
    public function up(): void
    {
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // income (thu) | expense (chi)
            $table->string('type');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->unique(['name', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_categories');
    }
};
