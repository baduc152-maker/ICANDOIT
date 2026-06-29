@extends('layouts.app')

@section('title', 'Ghi danh')

@section('content')
@include('partials.accounting-nav')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-semibold">Ghi danh</h1>
    <a href="{{ route('admin.enrollments.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">+ Ghi danh mới</a>
</div>

<form method="GET" class="mb-4">
    <select name="status" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm">
        <option value="">Tất cả trạng thái</option>
        <option value="active" @selected($status === 'active')>Đang học</option>
        <option value="completed" @selected($status === 'completed')>Hoàn thành</option>
        <option value="cancelled" @selected($status === 'cancelled')>Đã hủy</option>
    </select>
</form>

<div class="rounded-2xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-left text-xs uppercase text-slate-400">
                    <th class="px-4 py-3">Học viên</th>
                    <th class="px-4 py-3">Khóa học</th>
                    <th class="px-4 py-3">Ngày</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3 text-right">Học phí</th>
                    <th class="px-4 py-3 text-right">Đã nộp</th>
                    <th class="px-4 py-3 text-right">Còn lại</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $e)
                    <tr class="border-b last:border-0 hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium">{{ $e->student?->name }}</td>
                        <td class="px-4 py-3">{{ $e->course?->name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $e->enrolled_on->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @php $cls = ['active' => 'bg-indigo-100 text-indigo-700', 'completed' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-slate-100 text-slate-500'][$e->status] ?? 'bg-slate-100'; @endphp
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $cls }}">{{ $e->statusLabel() }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">@vnd($e->netTuition())</td>
                        <td class="px-4 py-3 text-right text-emerald-600">@vnd($e->paidAmount())</td>
                        <td class="px-4 py-3 text-right font-semibold {{ $e->balance() > 0 ? 'text-amber-600' : 'text-slate-400' }}">@vnd($e->balance())</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.enrollments.show', $e) }}" class="text-indigo-600 hover:underline">Chi tiết</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-6 text-center text-slate-400">Chưa có ghi danh nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $enrollments->links() }}</div>
@endsection
