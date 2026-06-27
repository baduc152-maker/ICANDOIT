<?php

namespace App\Services\Icandoit;

use App\Models\Attendance;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Driver mặc định khi chưa có API ICANDOIT.
 *
 * Không gọi mạng — chỉ ghi log payload sẽ được gửi và trả về thành công với một
 * mã tham chiếu giả lập. Nhờ vậy toàn bộ luồng điểm danh chạy được ngay, và khi
 * ICANDOIT có API thật chỉ cần đổi driver sang 'http'.
 */
class NullIcandoitConnector implements IcandoitConnector
{
    public function pushAttendance(Attendance $attendance): SyncResult
    {
        $payload = (new AttendancePayload)->forAttendance($attendance);

        Log::info('[ICANDOIT] (null driver) Bỏ qua gọi API, ghi log payload điểm danh.', $payload);

        return SyncResult::success(
            reference: 'LOCAL-'.Str::upper(Str::random(10)),
            message: 'Đã ghi nhận cục bộ (chưa kết nối ICANDOIT thật).',
        );
    }
}
