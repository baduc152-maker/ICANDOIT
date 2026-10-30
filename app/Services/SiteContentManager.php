<?php

namespace App\Services;

use App\Models\SiteContent;
use App\Support\SiteContentSchema;
use Illuminate\Support\Facades\Cache;

/**
 * Quản lý nội dung website: đọc nội dung đang dùng (bản đã sửa trong trang
 * quản trị, nếu chưa sửa thì lấy mặc định ở config/center.php), lưu bản sửa và
 * khôi phục về mặc định.
 */
class SiteContentManager
{
    public const CACHE_KEY = 'site_content_overrides';

    /** Bản mặc định đọc thẳng từ config/center.php, không bị ảnh hưởng bởi ghi đè lúc chạy. */
    private static ?array $defaults = null;

    /**
     * Toàn bộ nhóm nội dung đã được chỉnh sửa, lấy từ cache để tránh truy vấn lặp.
     *
     * @return array<string, array<mixed>>
     */
    public function overrides(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                return SiteContent::query()->pluck('payload', 'section')->all();
            });
        } catch (\Throwable) {
            // Chưa chạy migrate hoặc chưa cấu hình CSDL: dùng nội dung mặc định.
            return [];
        }
    }

    /**
     * Nội dung đang hiển thị của một nhóm.
     */
    public function section(string $section): mixed
    {
        return $this->overrides()[$section] ?? $this->defaults($section);
    }

    /**
     * Nội dung mặc định khai báo trong config/center.php.
     */
    public function defaults(string $section): mixed
    {
        if (self::$defaults === null) {
            self::$defaults = require config_path('center.php');
        }

        return self::$defaults[$section] ?? null;
    }

    public function isCustomised(string $section): bool
    {
        return array_key_exists($section, $this->overrides());
    }

    /**
     * Ghi đè nội dung của website bằng bản đã chỉnh sửa.
     */
    public function applyToConfig(): void
    {
        // Gán lại toàn bộ nhóm nội dung: nhóm nào chưa sửa thì trả về bản mặc định,
        // nhờ vậy thao tác khôi phục mặc định có hiệu lực ngay.
        foreach (array_keys(SiteContentSchema::sections()) as $section) {
            config(["center.$section" => $this->section($section)]);
        }
    }

    public function save(string $section, mixed $payload, ?int $userId = null): void
    {
        SiteContent::updateOrCreate(
            ['section' => $section],
            ['payload' => $payload, 'updated_by' => $userId],
        );

        $this->flush();
    }

    public function reset(string $section): void
    {
        SiteContent::query()->where('section', $section)->delete();

        $this->flush();
    }

    public function lastUpdated(string $section): ?SiteContent
    {
        return SiteContent::query()->with('editor')->where('section', $section)->first();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
