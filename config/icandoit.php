<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tích hợp phần mềm ICANDOIT
    |--------------------------------------------------------------------------
    |
    | Ứng dụng điểm danh hoạt động độc lập. Khi ICANDOIT cung cấp API hoặc cơ
    | chế trao đổi dữ liệu, chỉ cần bật cấu hình ở đây và hoàn thiện lớp
    | App\Services\Icandoit\HttpIcandoitConnector — phần còn lại không phải sửa.
    |
    */

    // Bật/tắt việc đẩy dữ liệu điểm danh sang ICANDOIT.
    'enabled' => env('ICANDOIT_ENABLED', false),

    // Driver kết nối: 'null' (ghi log, chưa đẩy thật) hoặc 'http' (gọi REST API).
    'driver' => env('ICANDOIT_DRIVER', 'null'),

    // Cấu hình cho driver 'http' — điền khi đã có endpoint thật của ICANDOIT.
    'http' => [
        'base_url' => env('ICANDOIT_BASE_URL'),
        'api_key' => env('ICANDOIT_API_KEY'),
        'timeout' => env('ICANDOIT_TIMEOUT', 15),
        // Đường dẫn endpoint nhận sự kiện điểm danh.
        'attendance_endpoint' => env('ICANDOIT_ATTENDANCE_ENDPOINT', '/api/attendance'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Quy tắc chấm công
    |--------------------------------------------------------------------------
    */

    // Giờ vào chuẩn (HH:MM). Check-in sau mốc này bị tính là đi muộn.
    'work_start_time' => env('WORK_START_TIME', '08:30'),

    // Múi giờ áp dụng cho chấm công.
    'timezone' => env('ATTENDANCE_TIMEZONE', 'Asia/Ho_Chi_Minh'),

];
