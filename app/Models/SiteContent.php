<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteContent extends Model
{
    protected $fillable = [
        'section',
        'payload',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
