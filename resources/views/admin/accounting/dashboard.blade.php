@extends('layouts.app')

@section('title', 'Kế toán')

@section('content')
@include('partials.accounting-nav')

@php
    $maxMonthly = max(1, collect($monthly)->flatMap(fn ($m) => [$m['income'], $m['expense']])->max());
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-semibold">Tổng quan kế toán</h1>
    <a href="{{ route('admin.transactions.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">+ Lập phiếu thu/chi</a>
</div>

{{-- Thẻ tổng quan tháng --}}
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Thu tháng {{ $monthLabel }}</p>
        <p class="mt-1 text-2xl font-bold text-emerald-600">@vnd($month['income'])</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Chi tháng {{ $monthLabel }}</p>
        <p class="mt-1 text-2xl font-bold text-rose-600">@vnd($month['expense'])</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Lợi nhuận tháng</p>
        <p class="mt-1 text-2xl font-bold {{ $month['profit'] >= 0 ? 'text-indigo-600' : 'text-rose-600' }}">@vnd($month['profit'])</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Học phí còn phải thu</p>
        <p class="mt-1 text-2xl font-bold text-amber-600">@vnd($totalReceivable)</p>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    {{-- Biểu đồ thu/chi theo tháng --}}
    <div class="lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold">Thu / chi theo tháng năm {{ $year }}</h2>
                <form method="GET" class="flex items-center gap-2">
                    <input type="number" name="year" value="{{ $year }}" class="w-24 rounded-lg border-slate-300 text-sm" />
                    <button class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium hover:bg-slate-200">Xem</button>
                </form>
            </div>
            <div class="flex items-end gap-2" style="height: 200px;">
                @foreach($monthly as $m)
                    <div class="flex flex-1 flex-col items-center justify-end gap-0.5" title="T{{ $m['month'] }}: thu {{ \App\Support\Money::vnd($m['income']) }}, chi {{ \App\Support\Money::vnd($m['expense']) }}">
                        <div class="flex w-full items-end justify-center gap-0.5" style="height: 170px;">
                            <div class="w-1/2 rounded-t bg-emerald-400" style="height: {{ max(2, round($m['income'] / $maxMonthly * 100)) }}%"></div>
                            <div class="w-1/2 rounded-t bg-rose-400" style="height: {{ max(2, round($m['expense'] / $maxMonthly * 100)) }}%"></div>
                        </div>
                        <span class="text-[10px] text-slate-400">T{{ $m['month'] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 flex gap-4 text-xs text-slate-500">
                <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-emerald-400"></span> Thu</span>
                <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-rose-400"></span> Chi</span>
            </div>
        </div>
    </div>

    {{-- Học viên còn nợ học phí --}}
    <div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 font-semibold">Công nợ học phí</h2>
            <ul class="space-y-3">
                @forelse($studentsWithDebt as $s)
                    <li class="flex items-center justify-between text-sm">
                        <a href="{{ route('admin.students.show', $s) }}" class="font-medium text-indigo-600 hover:underline">{{ $s->name }}</a>
                        <span class="font-semibold text-amber-600">@vnd($s->balance())</span>
                    </li>
                @empty
                    <li class="text-sm text-slate-400">Tất cả học viên đã thanh toán đủ. 🎉</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

{{-- Phiếu gần đây --}}
<div class="mt-6 rounded-2xl bg-white p-6 shadow-sm">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="font-semibold">Phiếu gần đây</h2>
        <a href="{{ route('admin.transactions.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">Xem sổ quỹ →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-left text-xs uppercase text-slate-400">
                    <th class="py-2 pr-4">Số phiếu</th>
                    <th class="py-2 pr-4">Ngày</th>
                    <th class="py-2 pr-4">Danh mục</th>
                    <th class="py-2 pr-4">Diễn giải</th>
                    <th class="py-2 pr-4 text-right">Số tiền</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent as $t)
                    <tr class="border-b last:border-0">
                        <td class="py-2 pr-4 font-mono text-xs">{{ $t->code }}</td>
                        <td class="py-2 pr-4 text-slate-500">{{ $t->occurred_on->format('d/m/Y') }}</td>
                        <td class="py-2 pr-4">{{ $t->category?->name ?? '—' }}</td>
                        <td class="py-2 pr-4 text-slate-600">{{ $t->description }}</td>
                        <td class="py-2 pr-4 text-right font-semibold {{ $t->isIncome() ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $t->isIncome() ? '+' : '−' }}@vnd($t->amount)
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-slate-400">Chưa có phiếu nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
