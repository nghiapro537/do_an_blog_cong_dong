<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem user đã đăng nhập và có phải là admin không
        // (Bạn có thể đổi điều kiện kiểm tra tùy thuộc vào cột role trong bảng users của bạn)
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Nếu không phải admin, đá về trang chủ hoặc báo lỗi 403
        abort(403, 'Bạn không có quyền truy cập trang quản trị này.');
    }
}
