<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Một công việc được giao cho nhân sự trong một ngày cụ thể.
 */
class WorkTask extends Model
{
    public const STATUS_TODO = 'todo';
    public const STATUS_DONE = 'done';
    public const STATUS_CANCELLED = 'cancelled';

    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH = 'high';

    protected $fillable = [
        'user_id',
        'assigned_by',
        'title',
        'description',
        'work_date',
        'start_time',
        'end_time',
        'priority',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_TODO => 'Chưa xong',
            self::STATUS_DONE => 'Đã xong',
            self::STATUS_CANCELLED => 'Đã huỷ',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function priorities(): array
    {
        return [
            self::PRIORITY_NORMAL => 'Bình thường',
            self::PRIORITY_HIGH => 'Ưu tiên',
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function isHighPriority(): bool
    {
        return $this->priority === self::PRIORITY_HIGH;
    }

    /**
     * Khoảng giờ hiển thị; công việc không gắn giờ thì trả về null.
     */
    public function timeRange(): ?string
    {
        if (! $this->start_time) {
            return null;
        }

        $range = substr((string) $this->start_time, 0, 5);

        if ($this->end_time) {
            $range .= ' – '.substr((string) $this->end_time, 0, 5);
        }

        return $range;
    }
}
