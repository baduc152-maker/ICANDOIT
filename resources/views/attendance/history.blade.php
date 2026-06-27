@extends('layouts.app')

@section('title', 'Lịch sử điểm danh')

@php $tz = config('icandoit.timezone'); @endphp

@section('content')
<div class="rounded-2xl bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Lịch sử điểm danh của tôi</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b text-slate-500">
                <tr>
                    <th class="py-2 pr-4">Ngày</th>
                    <th class="py-2 pr-4">Giờ vào</th>
                    <th class="py-2 pr-4">Giờ ra</th>
                    <th class="py-2 pr-4">Số giờ</th>
                    <th class="py-2 pr-4">Trạng thái</th>
                    <th class="py-2 pr-4">Đồng bộ ICANDOIT</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($records as $r)
                    <tr>
                        <td class="py-3 pr-4 font-medium">{{ $r->work_date->format('d/m/Y') }}</td>
                        <td class="py-3 pr-4">{{ $r->check_in_at?->timezone($tz)->format('H:i') ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ $r->check_out_at?->timezone($tz)->format('H:i') ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ $r->workedHours() ?? '—' }}</td>
                        <td class="py-3 pr-4">
                            @if($r->isLate())
                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">Đi muộn</span>
                            @else
                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs text-emerald-700">Đúng giờ</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4">
                            @include('partials.sync-badge', ['record' => $r])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-slate-400">Chưa có bản ghi nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $records->links() }}
    </div>
</div>
@endsection
