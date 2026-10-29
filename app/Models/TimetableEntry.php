<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Một buổi dạy cố định hằng tuần trong thời khoá biểu.
 */
class TimetableEntry extends Model
{
    protected $fillable = [
        'user_id',
        'class_name',
        'subject',
        'weekday',
        'start_time',
        'end_time',
        'room',
        'effective_from',
        'effective_to',
        'note',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'weekday' => 'integer',
            'effective_from' => 'date',
            'effective_to' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /** Nhãn các thứ trong tuần, khoá 1 = Thứ 2. */
    public const WEEKDAYS = [
        1 => 'Thứ 2',
        2 => 'Thứ 3',
        3 => 'Thứ 4',
        4 => 'Thứ 5',
        5 => 'Thứ 6',
        6 => 'Thứ 7',
        7 => 'Chủ nhật',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function weekdayLabel(): string
    {
        return self::WEEKDAYS[$this->weekday] ?? '—';
    }

    /**
     * Các buổi còn hiệu lực trong một ngày cụ thể.
     */
    public function scopeActiveOn(Builder $query, Carbon $date): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('weekday', (int) $date->isoWeekday())
            ->whereDate('effective_from', '<=', $date)
            ->where(function (Builder $inner) use ($date) {
                $inner->whereNull('effective_to')->orWhereDate('effective_to', '>=', $date);
            });
    }

    /**
     * Khoảng thời gian hiển thị, ví dụ "18:00 – 20:00".
     */
    public function timeRange(): string
    {
        return substr((string) $this->start_time, 0, 5).' – '.substr((string) $this->end_time, 0, 5);
    }
}
