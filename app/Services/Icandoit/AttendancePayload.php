<?php

namespace App\Services\Icandoit;

use App\Models\Attendance;

/**
 * Chuẩn hoá dữ liệu điểm danh thành payload gửi sang ICANDOIT.
 *
 * Tách riêng để khi định dạng ICANDOIT yêu cầu thay đổi, chỉ sửa một nơi.
 */
class AttendancePayload
{
    /**
     * @return array<string, mixed>
     */
    public function forAttendance(Attendance $attendance): array
    {
        $user = $attendance->user;

        return [
            'employee_code' => $user?->employee_code,
            'employee_name' => $user?->name,
            'department' => $user?->department,
            'work_date' => $attendance->work_date?->toDateString(),
            'check_in_at' => $attendance->check_in_at?->toIso8601String(),
            'check_out_at' => $attendance->check_out_at?->toIso8601String(),
            'status' => $attendance->status,
            'worked_hours' => $attendance->workedHours(),
            'source' => 'remote-web',
            'local_id' => $attendance->id,
        ];
    }
}
