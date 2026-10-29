@extends('layouts.app')

@section('title', 'Giao việc')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-lg font-semibold">Công việc đã giao</h2>
        <p class="text-sm text-slate-500">Công việc hiện trong lịch của người được giao, ngay cạnh các buổi dạy.</p>
    </div>
    <div class="flex flex-wrap items-end gap-2">
        <form method="GET" action="{{ route('admin.tasks') }}" class="flex flex-wrap items-end gap-2">
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Nhân sự</label>
                <select name="nhan_vien" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Tất cả</option>
                    @foreach($staff as $person)
                        <option value="{{ $person->id }}" @selected((int) $selectedUserId === $person->id)>{{ $person->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Trạng thái</label>
                <select name="trang_thai" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Tất cả</option>
                    @foreach(\App\Models\WorkTask::statuses() as $key => $label)
                        <option value="{{ $key }}" @selected($selectedStatus === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Lọc</button>
        </form>
        <a href="{{ route('admin.tasks.create') }}"
           class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900">+ Giao việc</a>
        <a href="{{ route('schedule') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Xem lịch</a>
    </div>
</div>

<div class="rounded-2xl bg-white p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b text-slate-500">
                <tr>
                    <th class="py-2 pr-4">Ngày</th>
                    <th class="py-2 pr-4">Công việc</th>
                    <th class="py-2 pr-4">Người nhận</th>
                    <th class="py-2 pr-4">Giờ</th>
                    <th class="py-2 pr-4">Trạng thái</th>
                    <th class="py-2 pr-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($tasks as $task)
                    <tr>
                        <td class="py-3 pr-4 align-top">{{ $task->work_date?->format('d/m/Y') }}</td>
                        <td class="py-3 pr-4 align-top">
                            <p class="font-medium">
                                {{ $task->title }}
                                @if($task->isHighPriority())
                                    <span class="rounded-full bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-600">Ưu tiên</span>
                                @endif
                            </p>
                            @if($task->description)
                                <p class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($task->description, 90) }}</p>
                            @endif
                            @if($task->assigner)
                                <p class="text-xs text-slate-400">Giao bởi {{ $task->assigner->name }}</p>
                            @endif
                        </td>
                        <td class="py-3 pr-4 align-top">{{ $task->assignee?->name ?? '—' }}</td>
                        <td class="py-3 pr-4 align-top">{{ $task->timeRange() ?? 'Cả ngày' }}</td>
                        <td class="py-3 pr-4 align-top">
                            @php
                                $tone = match ($task->status) {
                                    \App\Models\WorkTask::STATUS_DONE => 'bg-emerald-100 text-emerald-700',
                                    \App\Models\WorkTask::STATUS_CANCELLED => 'bg-slate-100 text-slate-500',
                                    default => 'bg-amber-100 text-amber-700',
                                };
                            @endphp
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $tone }}">{{ $task->statusLabel() }}</span>
                        </td>
                        <td class="py-3 pr-4 align-top">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.tasks.edit', $task) }}" class="text-xs font-medium text-indigo-600 hover:underline">Sửa</a>
                                <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}"
                                      onsubmit="return confirm('Xoá công việc này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-medium text-rose-600 hover:underline">Xoá</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-slate-400">Chưa giao công việc nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tasks->links() }}</div>
</div>
@endsection
