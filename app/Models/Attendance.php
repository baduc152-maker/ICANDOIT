<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    public const SYNC_PENDING = 'pending';
    public const SYNC_SYNCED = 'synced';
    public const SYNC_FAILED = 'failed';

    public const STATUS_ON_TIME = 'on_time';
    public const STATUS_LATE = 'late';

    protected $fillable = [
        'user_id',
        'work_date',
        'check_in_at',
        'check_out_at',
        'check_in_ip',
        'check_out_ip',
        'status',
        'note',
        'sync_status',
        'synced_at',
        'icandoit_ref',
        'sync_message',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
            'synced_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Số giờ làm việc đã hoàn thành (nếu đã check-out).
     */
    public function workedHours(): ?float
    {
        if (! $this->check_in_at || ! $this->check_out_at) {
            return null;
        }

        return round($this->check_in_at->floatDiffInHours($this->check_out_at), 2);
    }

    public function isLate(): bool
    {
        return $this->status === self::STATUS_LATE;
    }
}
