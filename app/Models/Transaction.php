<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Một phiếu thu (income) hoặc phiếu chi (expense) trong sổ quỹ.
 */
class Transaction extends Model
{
    public const TYPE_INCOME = 'income';

    public const TYPE_EXPENSE = 'expense';

    public const METHOD_CASH = 'cash';

    public const METHOD_BANK = 'bank';

    public const METHOD_OTHER = 'other';

    protected $fillable = [
        'code',
        'type',
        'category_id',
        'amount',
        'occurred_on',
        'payment_method',
        'description',
        'student_id',
        'enrollment_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'occurred_on' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeIncome(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_INCOME);
    }

    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_EXPENSE);
    }

    /**
     * Lọc theo khoảng ngày (theo cột occurred_on).
     */
    public function scopeBetween(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('occurred_on', [$from, $to]);
    }

    public function isIncome(): bool
    {
        return $this->type === self::TYPE_INCOME;
    }

    public function typeLabel(): string
    {
        return $this->isIncome() ? 'Thu' : 'Chi';
    }

    /**
     * Nhãn tiếng Việt cho hình thức thanh toán.
     */
    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            self::METHOD_CASH => 'Tiền mặt',
            self::METHOD_BANK => 'Chuyển khoản',
            default => 'Khác',
        };
    }
}
