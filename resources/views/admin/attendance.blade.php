@extends('layouts.app')

@section('title', 'Quản trị điểm danh')

@php $tz = config('icandoit.timezone'); @endphp

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-lg font-semibold">Bảng điểm danh toàn công ty</h2>
        <p class="text-sm text-slate-500">Ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
    </div>
    <form method="GET" action="{{ route('admin.attendance') }}" class="flex items-end gap-2">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Chọn ngày</label>
            <input type="date" name="date" value="{{ $date }}"
                   class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Xem</button>
        <a href="{{ route('admin.attendance.export', ['date' => $date]) }}"
           class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Xuất CSV</a>
    </form>
</div>

{{-- Thẻ tổng hợp --}}
<div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
    @foreach([
        ['Có mặt', $summary['total'], 'text-indigo-600'],
        ['Đi muộn', $summary['late'], 'text-amber-600'],
        ['Chờ đồng bộ', $summary['pending_sync'], 'text-slate-600'],
        ['Lỗi đồng bộ', $summary['failed_sync'], 'text-rose-600'],
    ] as [$label, $value, $color])
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-500">{{ $label }}</p>
            <p class="text-2xl font-bold {{ $color }}">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="rounded-2xl bg-white p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b text-slate-500">
                <tr>
                    <th class="py-2 pr-4">Mã NV</th>
                    <th class="py-2 pr-4">Nhân viên</th>
                    <th class="py-2 pr-4">Phòng ban</th>
                    <th class="py-2 pr-4">Giờ vào</th>
                    <th class="py-2 pr-4">Giờ ra</th>
                    <th class="py-2 pr-4">Số giờ</th>
                    <th class="py-2 pr-4">Trạng thái</th>
                    <th class="py-2 pr-4">Đồng bộ</th>
                    <th class="py-2 pr-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($records as $r)
                    <tr>
                        <td class="py-3 pr-4 font-mono text-xs">{{ $r->user?->employee_code ?? '—' }}</td>
                        <td class="py-3 pr-4 font-medium">{{ $r->user?->name }}</td>
                        <td class="py-3 pr-4 text-slate-500">{{ $r->user?->department ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ $r->check_in_at?->timezone($tz)->format('H:i') ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ $r->check_out_at?->timezone($tz)->format('H:i') ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ $r->workedHours() ?? '—' }}</td>
                        <td class="py-3 pr-4">
                            @if($r->isLate())
                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">Muộn</span>
                            @else
                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs text-emerald-700">Đúng giờ</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4">@include('partials.sync-badge', ['record' => $r])</td>
                        <td class="py-3 pr-4">
                            @if($r->sync_status !== \App\Models\Attendance::SYNC_SYNCED)
                                <form method="POST" action="{{ route('admin.attendance.resync', $r) }}">
                                    @csrf
                                    <button class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-200">Đồng bộ lại</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-6 text-center text-slate-400">Không có dữ liệu điểm danh cho ngày này.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $records->links() }}</div>
</div>
@endsection
