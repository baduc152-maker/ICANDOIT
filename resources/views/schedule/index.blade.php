@extends('layouts.app')

@section('title', 'Lịch công việc')

@php
    $viewer = auth()->user();
    $isAdmin = $viewer->isAdmin();
    $showPerson = $isAdmin && $selectedUserId === null;
@endphp

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-lg font-semibold">Lịch công việc</h2>
        <p class="text-sm text-slate-500">
            Tuần {{ $week['start']->format('d/m/Y') }} – {{ $week['end']->format('d/m/Y') }} ·
            {{ $week['summary']['classes'] }} buổi dạy ·
            {{ $week['summary']['tasks'] }} công việc
            @if($week['summary']['tasks'] > 0)
                ({{ $week['summary']['done'] }} đã xong)
            @endif
        </p>
    </div>

    <div class="flex flex-wrap items-end gap-2">
        @if($isAdmin)
            <form method="GET" action="{{ route('schedule') }}" class="flex flex-wrap items-end gap-2">
                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Xem lịch của</label>
                    <select name="nhan_vien" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Tất cả nhân sự</option>
                        @foreach($staff as $person)
                            <option value="{{ $person->id }}" @selected((int) $selectedUserId === $person->id)>
                                {{ $person->name }}{{ $person->role === 'admin' ? ' (quản trị)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="tuan" value="{{ $week['start']->toDateString() }}">
                <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Xem</button>
            </form>

            <a href="{{ route('admin.tasks.create', ['nhan_vien' => $selectedUserId]) }}"
               class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900">+ Giao việc</a>
            <a href="{{ route('admin.timetable') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Thời khoá biểu</a>
        @endif
    </div>
</div>

{{-- Điều hướng tuần --}}
<div class="mb-4 flex flex-wrap items-center gap-2">
    @php $query = $isAdmin && $selectedUserId ? ['nhan_vien' => $selectedUserId] : []; @endphp
    <a href="{{ route('schedule', $query + ['tuan' => $previousWeek]) }}"
       class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">← Tuần trước</a>
    <a href="{{ route('schedule', $query) }}"
       class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Tuần này</a>
    <a href="{{ route('schedule', $query + ['tuan' => $nextWeek]) }}"
       class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Tuần sau →</a>

    <span class="ml-auto flex items-center gap-3 text-xs text-slate-500">
        <span class="flex items-center gap-1"><span class="legend-dot legend-class"></span> Buổi dạy</span>
        <span class="flex items-center gap-1"><span class="legend-dot legend-task"></span> Công việc</span>
    </span>
</div>

<div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-7">
    @foreach($week['days'] as $day)
        <div class="day-column rounded-xl bg-white p-3 shadow-sm {{ $day['is_today'] ? 'day-today' : '' }}">
            <div class="mb-2 border-b pb-2">
                <p class="text-sm font-semibold text-slate-700">
                    {{ \App\Models\TimetableEntry::WEEKDAYS[$day['date']->isoWeekday()] }}
                </p>
                <p class="text-xs text-slate-500">{{ $day['date']->format('d/m') }}</p>
            </div>

            <div class="space-y-3">
                @forelse($day['items'] as $item)
                    @if($item['type'] === 'class')
                        <div class="item-card item-class">
                            <p class="text-xs font-semibold text-slate-700">{{ $item['time'] }}</p>
                            <p class="text-sm font-medium text-slate-800">{{ $item['title'] }}</p>
                            @if($item['subtitle'])
                                <p class="text-xs text-slate-500">{{ $item['subtitle'] }}</p>
                            @endif
                            <p class="text-xs text-slate-500">
                                @if($item['room']) Phòng {{ $item['room'] }} @endif
                                @if($showPerson) · {{ $item['person'] }} @endif
                            </p>
                        </div>
                    @else
                        <div class="item-card item-task {{ $item['priority'] === 'high' ? 'item-high' : '' }} {{ $item['status'] === 'done' ? 'item-done' : '' }}">
                            <p class="text-xs font-semibold text-slate-700">{{ $item['time'] ?? 'Cả ngày' }}</p>
                            <p class="text-sm font-medium text-slate-800">{{ $item['title'] }}</p>
                            @if($item['subtitle'])
                                <p class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($item['subtitle'], 80) }}</p>
                            @endif
                            @if($showPerson)
                                <p class="text-xs text-slate-500">{{ $item['person'] }}</p>
                            @endif

                            <div class="mt-2 flex items-center gap-2">
                                @if($item['status'] === 'cancelled')
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">Đã huỷ</span>
                                @else
                                    <form method="POST" action="{{ route('schedule.task.toggle', $item['id']) }}">
                                        @csrf
                                        <button class="rounded-md border border-slate-300 px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-50">
                                            {{ $item['status'] === 'done' ? '↺ Chưa xong' : '✓ Xong' }}
                                        </button>
                                    </form>
                                @endif
                                @if($isAdmin)
                                    <a href="{{ route('admin.tasks.edit', $item['id']) }}" class="text-xs text-indigo-600 hover:underline">Sửa</a>
                                @endif
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="py-4 text-center text-xs text-slate-400">Trống</p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
