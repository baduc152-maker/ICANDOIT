<?php

namespace App\Http\Middleware;

use App\Services\SiteContentManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Nạp nội dung website đã chỉnh sửa trong trang quản trị vào cấu hình trước khi
 * render, để các trang vẫn đọc qua config('center.*') như cũ.
 */
class ApplySiteContent
{
    public function __construct(private readonly SiteContentManager $content)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $this->content->applyToConfig();

        return $next($request);
    }
}
