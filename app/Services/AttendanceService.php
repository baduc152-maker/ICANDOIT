<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use App\Services\Icandoit\IcandoitConnector;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * Nghiệp vụ điểm danh: xử lý check-in / check-out và đồng bộ sang ICANDOIT.
 */
class AttendanceService
{
    public function __construct(private readonly IcandoitConnector $connector)
    {
    }

    /**
     * Bản ghi điểm danh của hôm nay cho một nhân viên (nếu có).
     */
    public function todayRecord(User $user): ?Attendance
    {
        return $user->attendances()
            ->whereDate('work_date', $this->today()->toDateString())
            ->first();
    }

    /**
     * Nhân viên check-in cho ngày hôm nay.
     *
     * @throws ValidationException nếu đã check-in trước đó.
     */
    public function checkIn(User $user, ?string $ip = null, ?string $note = null): Attendance
    {
        $now = $this->now();
        $record = $this->todayRecord($user);

        if ($record && $record->check_in_at) {
            throw ValidationException::withMessages([
                'attendance' => 'Bạn đã check-in hôm nay rồi.',
            ]);
        }

        $record ??= new Attendance([
            'user_id' => $user->id,
            'work_date' => $this->today()->toDateString(),
        ]);

        $record->check_in_at = $now;
        $record->check_in_ip = $ip;
        $record->status = $this->resolveStatus($now);
        $record->sync_status = Attendance::SYNC_PENDING;

        if ($note) {
            $record->note = $note;
        }

        $record->save();

        $this->sync($record);

        return $record;
    }

    /**
     * Nhân viên check-out cho ngày hôm nay.
     *
     * @throws ValidationException nếu chưa check-in hoặc đã check-out.
     */
    public function checkOut(User $user, ?string $ip = null): Attendance
    {
        $record = $this->todayRecord($user);

        if (! $record || ! $record->check_in_at) {
            throw ValidationException::withMessages([
                'attendance' => 'Bạn cần check-in trước khi check-out.',
            ]);
        }

        if ($record->check_out_at) {
            throw ValidationException::withMessages([
                'attendance' => 'Bạn đã check-out hôm nay rồi.',
            ]);
        }

        $record->check_out_at = $this->now();
        $record->check_out_ip = $ip;
        $record->sync_status = Attendance::SYNC_PENDING;
        $record->save();

        $this->sync($record);

        return $record;
    }

    /**
     * Đẩy bản ghi sang ICANDOIT và cập nhật trạng thái đồng bộ.
     */
    public function sync(Attendance $attendance): Attendance
    {
        if (! config('icandoit.enabled')) {
            $attendance->forceFill([
                'sync_status' => Attendance::SYNC_PENDING,
                'sync_message' => 'Đồng bộ ICANDOIT đang tắt (ICANDOIT_ENABLED=false).',
            ])->save();

            return $attendance;
        }

        $result = $this->connector->pushAttendance($attendance);

        $attendance->forceFill([
            'sync_status' => $result->success ? Attendance::SYNC_SYNCED : Attendance::SYNC_FAILED,
            'synced_at' => $result->success ? $this->now() : null,
            'icandoit_ref' => $result->reference,
            'sync_message' => $result->message,
        ])->save();

        return $attendance;
    }

    private function resolveStatus(Carbon $checkInAt): string
    {
        $start = Carbon::parse(
            $checkInAt->toDateString().' '.config('icandoit.work_start_time', '08:30'),
            $this->timezone()
        );

        return $checkInAt->lessThanOrEqualTo($start)
            ? Attendance::STATUS_ON_TIME
            : Attendance::STATUS_LATE;
    }

    private function now(): Carbon
    {
        return Carbon::now($this->timezone());
    }

    private function today(): Carbon
    {
        return Carbon::today($this->timezone());
    }

    private function timezone(): string
    {
        return config('icandoit.timezone', config('app.timezone', 'UTC'));
    }
}
