<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkTask;
use App\Services\WorkScheduleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * Màn lịch công việc theo tuần: gộp buổi dạy trong thời khoá biểu và công việc
 * được giao. Quản trị viên xem được lịch của mọi người, nhân sự khác chỉ xem
 * được lịch của chính mình.
 */
class WorkScheduleController extends Controller
{
    public function __construct(private readonly WorkScheduleService $schedule)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $week = $this->weekFromRequest($request);

        // Nhân sự thường luôn bị giới hạn ở lịch của chính mình.
        $selectedUserId = $user->isAdmin()
            ? $this->selectedUserId($request)
            : $user->id;

        $data = $this->schedule->week($week, $selectedUserId);

        return view('schedule.index', [
            'week' => $data,
            'selectedUserId' => $selectedUserId,
            'staff' => $user->isAdmin()
                ? User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'role'])
                : collect(),
            'previousWeek' => $data['start']->copy()->subWeek()->toDateString(),
            'nextWeek' => $data['start']->copy()->addWeek()->toDateString(),
        ]);
    }

    /**
     * Đánh dấu một công việc đã xong hoặc trả lại trạng thái chưa xong.
     */
    public function toggleTask(Request $request, WorkTask $task): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isAdmin() || $task->user_id === $user->id, 403, 'Bạn chỉ cập nhật được công việc của mình.');

        $done = $task->status === WorkTask::STATUS_DONE;

        $task->update([
            'status' => $done ? WorkTask::STATUS_TODO : WorkTask::STATUS_DONE,
            'completed_at' => $done ? null : now(),
        ]);

        return back()->with('status', $done
            ? 'Đã chuyển "'.$task->title.'" về chưa xong.'
            : 'Đã đánh dấu "'.$task->title.'" là xong.');
    }

    private function weekFromRequest(Request $request): Carbon
    {
        $value = $request->input('tuan');

        try {
            return $value ? Carbon::parse($value) : Carbon::now();
        } catch (\Throwable) {
            return Carbon::now();
        }
    }

    private function selectedUserId(Request $request): ?int
    {
        $value = $request->input('nhan_vien');

        return ($value === null || $value === '' || $value === 'tat-ca') ? null : (int) $value;
    }
}
