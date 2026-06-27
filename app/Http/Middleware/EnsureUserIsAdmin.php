<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Chỉ cho phép quản trị viên truy cập các trang quản lý.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
    }
}
