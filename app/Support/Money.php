<?php

namespace App\Support;

/**
 * Tiện ích định dạng tiền tệ (đồng Việt Nam).
 */
class Money
{
    /**
     * Định dạng số tiền theo kiểu Việt Nam, ví dụ: 1.500.000 ₫.
     */
    public static function vnd(float|int|string|null $amount): string
    {
        $value = (float) ($amount ?? 0);

        return number_format($value, 0, ',', '.').' ₫';
    }
}
