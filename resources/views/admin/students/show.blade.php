@extends('layouts.app')

@section('title', $student->name)

@section('content')
@include('partials.accounting-nav')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-semibold">{{ $student->name }}</h1>
        <p class="text-sm text-slate-500">Mã: {{ $student->code }} · {{ $student->phone ?? 'chưa có SĐT' }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.enrollments.create', ['student_id' => $student->id]) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">+ Ghi danh khóa học</a>
        <a href="{{ route('admin.students.edit', $student) }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Sửa</a>
    </div>
</div>

{{-- Tổng quan công nợ --}}
<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Học phí phải thu</p>
        <p class="mt-1 text-2xl font-bold">@vnd($student->totalTuition())</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Đã thu</p>
        <p class="mt-1 text-2xl font-bold text-emerald-600">@vnd($student->totalPaid())</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Còn nợ</p>
        <p class="mt-1 text-2xl font-bold {{ $student->balance() > 0 ? 'text-amber-600' : 'text-slate-400' }}">@vnd($student->balance())</p>
    </div>
</div>

{{-- Ghi danh --}}
<div class="mb-6 rounded-2xl bg-white p-6 shadow-sm">
    <h2 class="mb-4 font-semibold">Khóa học đã ghi danh</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-left text-xs uppercase text-slate-400">
                    <th class="py-2 pr-4">Khóa học</th>
                    <th class="py-2 pr-4">Ngày ghi danh</th>
                    <th class="py-2 pr-4">Trạng thái</th>
                    <th class="py-2 pr-4 text-right">Học phí</th>
                    <th class="py-2 pr-4 text-right">Đã nộp</th>
                    <th class="py-2 pr-4 text-right">Còn lại</th>
                    <th class="py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->enrollments as $e)
                    <tr class="border-b last:border-0">
                        <td class="py-2 pr-4 font-medium">{{ $e->course?->name }}</td>
                        <td class="py-2 pr-4 text-slate-500">{{ $e->enrolled_on->format('d/m/Y') }}</td>
                        <td class="py-2 pr-4">{{ $e->statusLabel() }}</td>
                        <td class="py-2 pr-4 text-right">@vnd($e->netTuition())</td>
                        <td class="py-2 pr-4 text-right text-emerald-600">@vnd($e->paidAmount())</td>
                        <td class="py-2 pr-4 text-right font-semibold {{ $e->balance() > 0 ? 'text-amber-600' : 'text-slate-400' }}">@vnd($e->balance())</td>
                        <td class="py-2 text-right"><a href="{{ route('admin.enrollments.show', $e) }}" class="text-indigo-600 hover:underline">Chi tiết</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-center text-slate-400">Chưa ghi danh khóa học nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Lịch sử thu --}}
<div class="rounded-2xl bg-white p-6 shadow-sm">
    <h2 class="mb-4 font-semibold">Lịch sử nộp học phí</h2>
    <ul class="space-y-2 text-sm">
        @forelse($student->transactions->where('type', 'income')->sortByDesc('occurred_on') as $t)
            <li class="flex items-center justify-between border-b py-2 last:border-0">
                <span><span class="font-mono text-xs text-slate-400">{{ $t->code }}</span> · {{ $t->occurred_on->format('d/m/Y') }} · {{ $t->paymentMethodLabel() }}</span>
                <span class="font-semibold text-emerald-600">@vnd($t->amount)</span>
            </li>
        @empty
            <li class="py-2 text-slate-400">Chưa có khoản thu nào.</li>
        @endforelse
    </ul>
</div>

@if($student->note)
    <p class="mt-4 text-sm text-slate-500">Ghi chú: {{ $student->note }}</p>
@endif

<form method="POST" action="{{ route('admin.students.destroy', $student) }}" class="mt-6" onsubmit="return confirm('Xóa học viên này? Toàn bộ ghi danh sẽ bị xóa theo.')">
    @csrf @method('DELETE')
    <button class="text-sm text-rose-600 hover:underline">Xóa học viên</button>
</form>
@endsection
