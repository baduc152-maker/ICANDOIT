<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse as BaseStreamedResponse;

class AttendanceAdminController extends Controller
{
    public function __construct(private readonly AttendanceService $attendance)
    {
    }

    public function index(Request $request): View
    {
        $date = $request->input('date', now(config('icandoit.timezone'))->toDateString());

        $query = Attendance::query()
            ->with('user')
            ->whereDate('work_date', $date)
            ->orderBy('check_in_at');

        $records = $query->paginate(50)->withQueryString();

        $summary = [
            'total' => (clone $query)->count(),
            'late' => (clone $query)->where('status', Attendance::STATUS_LATE)->count(),
            'pending_sync' => (clone $query)->where('sync_status', Attendance::SYNC_PENDING)->count(),
            'failed_sync' => (clone $query)->where('sync_status', Attendance::SYNC_FAILED)->count(),
        ];

        return view('admin.attendance', [
            'records' => $records,
            'date' => $date,
            'summary' => $summary,
        ]);
    }

    /**
     * Thử đồng bộ lại một bản ghi sang ICANDOIT.
     */
    public function resync(Request $request, Attendance $attendance): RedirectResponse
    {
        $this->attendance->sync($attendance);

        return back()->with('status', 'Đã thử đồng bộ lại bản ghi #'.$attendance->id);
    }

    /**
     * Xuất bảng điểm danh theo ngày ra file CSV (để đối chiếu/nhập ICANDOIT thủ công).
     */
    public function export(Request $request): BaseStreamedResponse
    {
        $date = $request->input('date', now(config('icandoit.timezone'))->toDateString());

        $records = Attendance::query()
            ->with('user')
            ->whereDate('work_date', $date)
            ->orderBy('check_in_at')
            ->get();

        $filename = "diem-danh-{$date}.csv";

        return response()->streamDownload(function () use ($records) {
            $out = fopen('php://output', 'w');
            // BOM để Excel đọc đúng tiếng Việt UTF-8.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Mã NV', 'Họ tên', 'Phòng ban', 'Ngày', 'Giờ vào', 'Giờ ra', 'Số giờ', 'Trạng thái', 'Đồng bộ']);

            foreach ($records as $r) {
                fputcsv($out, [
                    $r->user?->employee_code,
                    $r->user?->name,
                    $r->user?->department,
                    $r->work_date?->toDateString(),
                    $r->check_in_at?->format('H:i:s'),
                    $r->check_out_at?->format('H:i:s'),
                    $r->workedHours(),
                    $r->status,
                    $r->sync_status,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
