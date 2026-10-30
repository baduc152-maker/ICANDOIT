@extends('layouts.app')

@section('title', $task->exists ? 'Sửa công việc' : 'Giao việc')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.tasks') }}" class="text-sm text-slate-500 hover:text-indigo-600">← Công việc đã giao</a>
    <h2 class="mt-2 text-lg font-semibold">{{ $task->exists ? 'Sửa công việc' : 'Giao việc mới' }}</h2>
    <p class="text-sm text-slate-500">Công việc sẽ hiện trong lịch của người nhận vào đúng ngày đã chọn.</p>
</div>

<form method="POST" action="{{ $task->exists ? route('admin.tasks.update', $task) : route('admin.tasks.store') }}"
      class="rounded-2xl bg-white p-6 shadow-sm">
    @csrf
    @if($task->exists) @method('PUT') @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="user_id">Giao cho</label>
            <select id="user_id" name="user_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                @foreach($staff as $person)
                    <option value="{{ $person->id }}" @selected((int) old('user_id', $task->user_id) === $person->id)>
                        {{ $person->name }}{{ $person->role === 'admin' ? ' (quản trị)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="work_date">Ngày thực hiện</label>
            <input id="work_date" type="date" name="work_date"
                   value="{{ old('work_date', $task->work_date instanceof \Illuminate\Support\Carbon ? $task->work_date->toDateString() : $task->work_date) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2">
            <label class="mb-1 block text-sm font-medium text-slate-700" for="title">Tên công việc</label>
            <input id="title" type="text" name="title" value="{{ old('title', $task->title) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Chấm bài Writing lớp INT-03">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="start_time">Giờ bắt đầu (không bắt buộc)</label>
            <input id="start_time" type="time" name="start_time" value="{{ old('start_time', substr((string) $task->start_time, 0, 5)) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="end_time">Giờ kết thúc (không bắt buộc)</label>
            <input id="end_time" type="time" name="end_time" value="{{ old('end_time', substr((string) $task->end_time, 0, 5)) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="priority">Mức ưu tiên</label>
            <select id="priority" name="priority" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                @foreach(\App\Models\WorkTask::priorities() as $key => $label)
                    <option value="{{ $key }}" @selected(old('priority', $task->priority) === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" for="status">Trạng thái</label>
            <select id="status" name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                @foreach(\App\Models\WorkTask::statuses() as $key => $label)
                    <option value="{{ $key }}" @selected(old('status', $task->status) === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2">
            <label class="mb-1 block text-sm font-medium text-slate-700" for="description">Mô tả chi tiết</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ old('description', $task->description) }}</textarea>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3 border-t pt-5">
        <button class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">Lưu</button>
        <a href="{{ route('admin.tasks') }}" class="text-sm text-slate-500 hover:text-slate-700">Huỷ</a>
    </div>
</form>
@endsection
