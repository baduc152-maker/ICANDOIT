<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Quản lý thời khoá biểu: các buổi dạy lặp lại hằng tuần của từng giảng viên.
 */
class TimetableController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->input('nhan_vien');

        return view('admin.timetable.index', [
            'entries' => TimetableEntry::query()
                ->with('teacher')
                ->when($userId, fn ($query) => $query->where('user_id', $userId))
                ->orderBy('weekday')
                ->orderBy('start_time')
                ->get(),
            'staff' => $this->staff(),
            'selectedUserId' => $userId,
        ]);
    }

    public function create(): View
    {
        return view('admin.timetable.form', [
            'entry' => new TimetableEntry(['weekday' => 1, 'is_active' => true, 'effective_from' => now()->toDateString()]),
            'staff' => $this->staff(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $entry = TimetableEntry::create($this->validated($request));

        return redirect()
            ->route('admin.timetable')
            ->with('status', 'Đã thêm buổi dạy "'.$entry->class_name.'" vào thời khoá biểu.');
    }

    public function edit(TimetableEntry $timetable): View
    {
        return view('admin.timetable.form', [
            'entry' => $timetable,
            'staff' => $this->staff(),
        ]);
    }

    public function update(Request $request, TimetableEntry $timetable): RedirectResponse
    {
        $timetable->update($this->validated($request));

        return redirect()
            ->route('admin.timetable')
            ->with('status', 'Đã cập nhật buổi dạy "'.$timetable->class_name.'".');
    }

    public function destroy(TimetableEntry $timetable): RedirectResponse
    {
        $timetable->delete();

        return redirect()
            ->route('admin.timetable')
            ->with('status', 'Đã xoá buổi dạy khỏi thời khoá biểu.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'class_name' => ['required', 'string', 'max:150'],
            'subject' => ['nullable', 'string', 'max:150'],
            'weekday' => ['required', 'integer', 'between:1,7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['nullable', 'string', 'max:60'],
            'effective_from' => ['required', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'note' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ], [], [
            'user_id' => 'giảng viên',
            'class_name' => 'tên lớp',
            'weekday' => 'thứ trong tuần',
            'start_time' => 'giờ bắt đầu',
            'end_time' => 'giờ kết thúc',
            'effective_from' => 'ngày bắt đầu áp dụng',
            'effective_to' => 'ngày kết thúc áp dụng',
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }

    private function staff()
    {
        return User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'role']);
    }
}
