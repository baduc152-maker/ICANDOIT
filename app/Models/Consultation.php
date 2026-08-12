<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_ENROLLED = 'enrolled';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'course',
        'note',
        'status',
        'staff_note',
        'contacted_at',
        'source',
        'ip',
    ];

    protected function casts(): array
    {
        return [
            'contacted_at' => 'datetime',
        ];
    }

    /**
     * Danh sách trạng thái kèm nhãn tiếng Việt dùng cho giao diện quản trị.
     *
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => 'Mới',
            self::STATUS_CONTACTED => 'Đã liên hệ',
            self::STATUS_ENROLLED => 'Đã nhập học',
            self::STATUS_CLOSED => 'Đóng',
        ];
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }
}
