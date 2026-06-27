<?php

namespace App\Services\Icandoit;

use App\Models\Attendance;

/**
 * Hợp đồng (interface) cho mọi cách kết nối tới ICANDOIT.
 *
 * Hiện tại ICANDOIT chưa cung cấp API, nên mặc định dùng NullIcandoitConnector.
 * Khi có API thật, chỉ cần hoàn thiện HttpIcandoitConnector mà không phải sửa
 * bất kỳ controller hay model nào khác.
 */
interface IcandoitConnector
{
    /**
     * Đẩy một bản ghi điểm danh sang ICANDOIT.
     *
     * @return SyncResult Kết quả đồng bộ (thành công/thất bại + mã tham chiếu).
     */
    public function pushAttendance(Attendance $attendance): SyncResult;
}
