@extends('layouts.app')

@section('title', 'Thời khoá biểu')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-lg font-semibold">Thời khoá biểu</h2>
        <p class="text-sm text-slate-500">Các buổi dạy lặp lại hằng tuần, tự động hiện trong lịch công việc của giảng viên.</p>
    </div>
    <div class="flex flex-wrap items-end gap-2">
        <form method="GET" action="{{ route('admin.timetable') }}" class="flex items-end gap-2">
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Giảng viên</label>
                <select name="nhan_vien" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Tất cả</option>
                    @foreach($staff as $person)
                        <option value="{{ $person->id }}" @selected((int) $selectedUserId === $person->id)>{{ $person->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Lọc</button>
        </form>
        <a href="{{ route('admin.timetable.create') }}"
           class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900">+ Thêm buổi dạy</a>
    </div>
</div>

<div class="rounded-2xl bg-white p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b text-slate-500">
                <tr>
                    <th class="py-2 pr-4">Thứ</th>
                    <th class="py-2 pr-4">Giờ</th>
                    <th class="py-2 pr-4">Lớp</th>
                    <th class="py-2 pr-4">Giảng viên</th>
                    <th class="py-2 pr-4">Phòng</th>
                    <th class="py-2 pr-4">Áp dụng</th>
                    <th class="py-2 pr-4">Trạng thái</th>
                    <th class="py-2 pr-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($entries as $entry)
                    <tr>
                        <td class="py-3 pr-4 font-medium">{{ $entry->weekdayLabel() }}</td>
                        <td class="py-3 pr-4">{{ $entry->timeRange() }}</td>
                        <td class="py-3 pr-4">
                            <p class="font-medium">{{ $entry->class_name }}</p>
                            @if($entry->subject)
                                <p class="text-xs text-slate-500">{{ $entry->subject }}</p>
                            @endif
                        </td>
                        <td class="py-3 pr-4">{{ $entry->teacher?->name ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ $entry->room ?? '—' }}</td>
                        <td class="py-3 pr-4 text-xs text-slate-500">
                            {{ $entry->effective_from?->format('d/m/Y') }}
                            → {{ $entry->effective_to?->format('d/m/Y') ?? 'không thời hạn' }}
                        </td>
                        <td class="py-3 pr-4">
                            @if($entry->is_active)
                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">Đang dạy</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Tạm dừng</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.timetable.edit', $entry) }}" class="text-xs font-medium text-indigo-600 hover:underline">Sửa</a>
                                <form method="POST" action="{{ route('admin.timetable.destroy', $entry) }}"
                                      onsubmit="return confirm('Xoá buổi dạy này khỏi thời khoá biểu?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-medium text-rose-600 hover:underline">Xoá</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-6 text-center text-slate-400">Chưa có buổi dạy nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
