<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Liên kết sang các ứng dụng khác của trung tâm (giao bài tập, giao việc).
 *
 * Liên kết chưa khai báo đường dẫn sẽ bị bỏ qua để website không hiện link chết.
 */
class AppLink
{
    public const PUBLIC = 'public';
    public const INTERNAL = 'internal';

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function all(): Collection
    {
        return collect(config('center.apps', []))
            ->filter(fn ($app) => filled($app['url'] ?? null))
            ->values();
    }

    /**
     * Liên kết dành cho một nhóm người dùng: 'public' (học viên, phụ huynh)
     * hoặc 'internal' (nhân sự trung tâm).
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function for(string $audience): Collection
    {
        return self::all()->where('audience', $audience)->values();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(string $key): ?array
    {
        return self::all()->firstWhere('key', $key);
    }
}
