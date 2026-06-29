<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Học viên của trung tâm.
 */
class Student extends Model
{
    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'date_of_birth',
        'address',
        'note',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Tổng học phí phải thu của tất cả lần ghi danh còn hiệu lực.
     */
    public function totalTuition(): float
    {
        return (float) $this->enrollments
            ->where('status', '!=', Enrollment::STATUS_CANCELLED)
            ->sum(fn (Enrollment $e) => $e->netTuition());
    }

    /**
     * Tổng số tiền học viên đã nộp (các phiếu thu).
     */
    public function totalPaid(): float
    {
        return (float) $this->transactions
            ->where('type', Transaction::TYPE_INCOME)
            ->sum('amount');
    }

    /**
     * Công nợ học phí còn lại (có thể âm nếu nộp dư).
     */
    public function balance(): float
    {
        return round($this->totalTuition() - $this->totalPaid(), 2);
    }
}
