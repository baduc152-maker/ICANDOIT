<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Giao việc cho nhân sự: tạo, sửa, xoá công việc theo ngày.
 */
class WorkTaskController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->input('nhan_vien');
        $status = $request->input('trang_thai');

        return view('admin.tasks.index', [
            'tasks' => WorkTask::query()
                ->with(['assignee', 'assigner'])
                ->when($userId, fn ($query) => $query->where('user_id', $userId))
                ->when($status && array_key_exists($status, WorkTask::statuses()),
                    fn ($query) => $query->where('status', $status))
                ->orderByDesc('work_date')
                ->orderByRaw('start_time is null, start_time')
                ->paginate(30)
                ->withQueryString(),
            'staff' => $this->staff(),
            'selectedUserId' => $userId,
            'selectedStatus' => $status,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.tasks.form', [
            'task' => new WorkTask([
                'work_date' => $request->input('ngay', now()->toDateString()),
                'user_id' => $request->input('nhan_vien'),
                'priority' => WorkTask::PRIORITY_NORMAL,
                'status' => WorkTask::STATUS_TODO,
            ]),
            'staff' => $this->staff(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['assigned_by'] = $request->user()->id;

        $task = WorkTask::create($data);

        return redirect()
            ->route('admin.tasks')
            ->with('status', 'Đã giao việc "'.$task->title.'" cho '.$task->assignee?->name.'.');
    }

    public function edit(WorkTask $task): View
    {
        return view('admin.tasks.form', [
            'task' => $task,
            'staff' => $this->staff(),
        ]);
    }

    public function update(Request $request, WorkTask $task): RedirectResponse
    {
        $data = $this->validated($request);

        // Ghi nhận thời điểm hoàn thành khi chuyển sang trạng thái đã xong.
        if ($data['status'] === WorkTask::STATUS_DONE && $task->status !== WorkTask::STATUS_DONE) {
            $data['completed_at'] = now();
        } elseif ($data['status'] !== WorkTask::STATUS_DONE) {
            $data['completed_at'] = null;
        }

        $task->update($data);

        return redirect()
            ->route('admin.tasks')
            ->with('status', 'Đã cập nhật công việc "'.$task->title.'".');
    }

    public function destroy(WorkTask $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('admin.tasks')->with('status', 'Đã xoá công việc.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1000'],
            'work_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'priority' => ['required', Rule::in(array_keys(WorkTask::priorities()))],
            'status' => ['required', Rule::in(array_keys(WorkTask::statuses()))],
        ], [], [
            'user_id' => 'người nhận việc',
            'title' => 'tên công việc',
            'work_date' => 'ngày thực hiện',
            'start_time' => 'giờ bắt đầu',
            'end_time' => 'giờ kết thúc',
        ]);
    }

    private function staff()
    {
        return User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'role']);
    }
}
