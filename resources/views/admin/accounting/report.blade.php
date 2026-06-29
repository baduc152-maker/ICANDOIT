@extends('layouts.app')

@section('title', 'Báo cáo tài chính')

@section('content')
@include('partials.accounting-nav')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-semibold">Báo cáo tài chính</h1>
</div>

{{-- Bộ lọc khoảng thời gian --}}
<form method="GET" class="mb-6 flex flex-wrap items-end gap-3 rounded-2xl bg-white p-4 shadow-sm">
    <div>
        <label class="block text-xs font-medium text-slate-500">Từ ngày</label>
        <input type="date" name="from" value="{{ $from }}" class="mt-1 rounded-lg border-slate-300 text-sm" />
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500">Đến ngày</label>
        <input type="date" name="to" value="{{ $to }}" class="mt-1 rounded-lg border-slate-300 text-sm" />
    </div>
    <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Xem báo cáo</button>
    <a href="{{ route('admin.accounting.export', ['from' => $from, 'to' => $to]) }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Xuất CSV</a>
</form>

{{-- Tổng kết --}}
<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <div class="rounded-2xl bg-emerald-50 p-5">
        <p class="text-sm text-emerald-700">Tổng thu</p>
        <p class="mt-1 text-2xl font-bold text-emerald-700">@vnd($summary['income'])</p>
    </div>
    <div class="rounded-2xl bg-rose-50 p-5">
        <p class="text-sm text-rose-700">Tổng chi</p>
        <p class="mt-1 text-2xl font-bold text-rose-700">@vnd($summary['expense'])</p>
    </div>
    <div class="rounded-2xl {{ $summary['profit'] >= 0 ? 'bg-indigo-50' : 'bg-amber-50' }} p-5">
        <p class="text-sm {{ $summary['profit'] >= 0 ? 'text-indigo-700' : 'text-amber-700' }}">Chênh lệch (Lợi nhuận)</p>
        <p class="mt-1 text-2xl font-bold {{ $summary['profit'] >= 0 ? 'text-indigo-700' : 'text-amber-700' }}">@vnd($summary['profit'])</p>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    {{-- Thu theo danh mục --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 font-semibold text-emerald-700">Khoản thu theo danh mục</h2>
        <table class="min-w-full text-sm">
            <tbody>
                @forelse($incomeRows as $row)
                    <tr class="border-b last:border-0">
                        <td class="py-2">{{ $row->category ?? 'Chưa phân loại' }}</td>
                        <td class="py-2 text-right font-semibold text-emerald-600">@vnd($row->total)</td>
                    </tr>
                @empty
                    <tr><td class="py-3 text-slate-400">Không có khoản thu trong kỳ.</td></tr>
                @endforelse
            </tbody>
            @if($incomeRows->isNotEmpty())
                <tfoot>
                    <tr class="border-t-2 font-bold">
                        <td class="py-2">Tổng</td>
                        <td class="py-2 text-right text-emerald-700">@vnd($summary['income'])</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    {{-- Chi theo danh mục --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 font-semibold text-rose-700">Khoản chi theo danh mục</h2>
        <table class="min-w-full text-sm">
            <tbody>
                @forelse($expenseRows as $row)
                    <tr class="border-b last:border-0">
                        <td class="py-2">{{ $row->category ?? 'Chưa phân loại' }}</td>
                        <td class="py-2 text-right font-semibold text-rose-600">@vnd($row->total)</td>
                    </tr>
                @empty
                    <tr><td class="py-3 text-slate-400">Không có khoản chi trong kỳ.</td></tr>
                @endforelse
            </tbody>
            @if($expenseRows->isNotEmpty())
                <tfoot>
                    <tr class="border-t-2 font-bold">
                        <td class="py-2">Tổng</td>
                        <td class="py-2 text-right text-rose-700">@vnd($summary['expense'])</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
