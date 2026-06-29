@extends('layouts.app')

@section('title', 'Sổ quỹ thu/chi')

@section('content')
@include('partials.accounting-nav')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-semibold">Sổ quỹ thu / chi</h1>
    <div class="flex gap-2">
        <a href="{{ route('admin.transactions.create', ['type' => 'income']) }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">+ Phiếu thu</a>
        <a href="{{ route('admin.transactions.create', ['type' => 'expense']) }}" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">+ Phiếu chi</a>
    </div>
</div>

{{-- Bộ lọc --}}
<form method="GET" class="mb-4 flex flex-wrap items-end gap-3 rounded-2xl bg-white p-4 shadow-sm">
    <div>
        <label class="block text-xs font-medium text-slate-500">Từ ngày</label>
        <input type="date" name="from" value="{{ $from }}" class="mt-1 rounded-lg border-slate-300 text-sm" />
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500">Đến ngày</label>
        <input type="date" name="to" value="{{ $to }}" class="mt-1 rounded-lg border-slate-300 text-sm" />
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500">Loại</label>
        <select name="type" class="mt-1 rounded-lg border-slate-300 text-sm">
            <option value="">Tất cả</option>
            <option value="income" @selected($type === 'income')>Thu</option>
            <option value="expense" @selected($type === 'expense')>Chi</option>
        </select>
    </div>
    <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Lọc</button>
    <a href="{{ route('admin.accounting.export', ['from' => $from, 'to' => $to]) }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Xuất CSV</a>
</form>

{{-- Tổng kết khoảng lọc --}}
<div class="mb-4 grid gap-3 sm:grid-cols-3">
    <div class="rounded-xl bg-white px-4 py-3 shadow-sm">
        <span class="text-xs text-slate-500">Tổng thu</span>
        <p class="font-bold text-emerald-600">@vnd($summary['income'])</p>
    </div>
    <div class="rounded-xl bg-white px-4 py-3 shadow-sm">
        <span class="text-xs text-slate-500">Tổng chi</span>
        <p class="font-bold text-rose-600">@vnd($summary['expense'])</p>
    </div>
    <div class="rounded-xl bg-white px-4 py-3 shadow-sm">
        <span class="text-xs text-slate-500">Chênh lệch</span>
        <p class="font-bold {{ $summary['profit'] >= 0 ? 'text-indigo-600' : 'text-amber-600' }}">@vnd($summary['profit'])</p>
    </div>
</div>

<div class="rounded-2xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-left text-xs uppercase text-slate-400">
                    <th class="px-4 py-3">Số phiếu</th>
                    <th class="px-4 py-3">Ngày</th>
                    <th class="px-4 py-3">Loại</th>
                    <th class="px-4 py-3">Danh mục</th>
                    <th class="px-4 py-3">Diễn giải</th>
                    <th class="px-4 py-3 text-right">Số tiền</th>
                    <th class="px-4 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $t)
                    <tr class="border-b last:border-0 hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $t->code }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $t->occurred_on->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $t->isIncome() ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $t->typeLabel() }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $t->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ $t->description }}
                            @if($t->student)
                                <span class="block text-xs text-slate-400">HV: {{ $t->student->name }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold {{ $t->isIncome() ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $t->isIncome() ? '+' : '−' }}@vnd($t->amount)
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.transactions.edit', $t) }}" class="text-indigo-600 hover:underline">Sửa</a>
                                <form method="POST" action="{{ route('admin.transactions.destroy', $t) }}" onsubmit="return confirm('Xóa phiếu {{ $t->code }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-600 hover:underline">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-slate-400">Không có phiếu nào trong kỳ.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $records->links() }}</div>
@endsection
