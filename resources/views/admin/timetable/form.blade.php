@extends('layouts.app')

@section('title', $entry->exists ? 'Sửa buổi dạy' : 'Thêm buổi dạy')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.timetable') }}" class="text-sm text-slate-500 hover:text-indigo-600">← Thời khoá biểu</a>
    <h2 class="mt-2 text-lg font-semibold">{{ $entry->exists ? 'Sửa buổi dạy' : 'Thêm buổi dạy' }}</h2>
    <p class="text-sm text-slate-500">Buổi dạy sẽ lặp lại hằng tuần trong khoảng ngày áp dụng.</p>
</div>

<form method="POST" action="{{ $entry->exists ? route('admin.timetable.update', $entry) : route('admin.timetable.store') }}"
      class="rounded-2xl bg-white p-6 shadow-sm">
    @csrf
    @if($entry->exists) @method('PUT') @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="user_id">Giảng viên</label>
            <select id="user_id" name="user_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                @foreach($staff as $person)
                    <option value="{{ $person->id }}" @selected((int) old('user_id', $entry->user_id) === $person->id)>{{ $person->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="weekday">Thứ trong tuần</label>
            <select id="weekday" name="weekday" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                @foreach(\App\Models\TimetableEntry::WEEKDAYS as $value => $label)
                    <option value="{{ $value }}" @selected((int) old('weekday', $entry->weekday) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="class_name">Tên lớp</label>
            <input id="class_name" type="text" name="class_name" value="{{ old('class_name', $entry->class_name) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="IELTS 5.5 – 6.5 · INT-03">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="subject">Nội dung buổi dạy</label>
            <input id="subject" type="text" name="subject" value="{{ old('subject', $entry->subject) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Writing Task 2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="start_time">Giờ bắt đầu</label>
            <input id="start_time" type="time" name="start_time" value="{{ old('start_time', substr((string) $entry->start_time, 0, 5)) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="end_time">Giờ kết thúc</label>
            <input id="end_time" type="time" name="end_time" value="{{ old('end_time', substr((string) $entry->end_time, 0, 5)) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="room">Phòng học</label>
            <input id="room" type="text" name="room" value="{{ old('room', $entry->room) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="A1">
        </div>

        <div class="flex items-center">
            <label class="flex items-center gap-2 pt-6 text-sm font-medium text-slate-700">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $entry->is_active ?? true))
                       class="rounded border-slate-300">
                Đang dạy
            </label>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="effective_from">Áp dụng từ ngày</label>
            <input id="effective_from" type="date" name="effective_from"
                   value="{{ old('effective_from', $entry->effective_from?->toDateString() ?? $entry->effective_from) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="effective_to">Đến ngày (để trống nếu chưa xác định)</label>
            <input id="effective_to" type="date" name="effective_to"
                   value="{{ old('effective_to', $entry->effective_to?->toDateString()) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2">
            <label class="mb-1 block text-sm font-medium text-slate-700" for="note">Ghi chú</label>
            <textarea id="note" name="note" rows="3"
                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ old('note', $entry->note) }}</textarea>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3 border-t pt-5">
        <button class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">Lưu</button>
        <a href="{{ route('admin.timetable') }}" class="text-sm text-slate-500 hover:text-slate-700">Huỷ</a>
    </div>
</form>
@endsection
