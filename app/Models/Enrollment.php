<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ghi danh: học viên đăng ký một khóa học với mức học phí cụ thể.
 */
class Enrollment extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'student_id',
        'course_id',
        'enrolled_on',
        'tuition_amount',
        'discount',
        'status',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'enrolled_on' => 'date',
            'tuition_amount' => 'decimal:2',
            'discount' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Các phiếu thu học phí gắn với lần ghi danh này.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Học phí thực thu (đã trừ giảm giá), không âm.
     */
    public function netTuition(): float
    {
        return max(0, round((float) $this->tuition_amount - (float) $this->discount, 2));
    }

    /**
     * Số tiền đã thu cho lần ghi danh này.
     */
    public function paidAmount(): float
    {
        return (float) $this->transactions
            ->where('type', Transaction::TYPE_INCOME)
            ->sum('amount');
    }

    /**
     * Công nợ học phí còn lại của lần ghi danh.
     */
    public function balance(): float
    {
        if ($this->status === self::STATUS_CANCELLED) {
            return 0.0;
        }

        return round($this->netTuition() - $this->paidAmount(), 2);
    }

    public function isFullyPaid(): bool
    {
        return $this->balance() <= 0;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Đang học',
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_CANCELLED => 'Đã hủy',
            default => $this->status,
        };
    }
}
