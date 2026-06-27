<?php

namespace App\Services\Icandoit;

/**
 * Kết quả của một lần đồng bộ điểm danh sang ICANDOIT.
 */
class SyncResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $reference = null,
        public readonly ?string $message = null,
    ) {
    }

    public static function success(?string $reference = null, ?string $message = null): self
    {
        return new self(true, $reference, $message);
    }

    public static function failure(string $message): self
    {
        return new self(false, null, $message);
    }
}
