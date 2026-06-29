@extends('layouts.app')

@section('title', 'Chi tiết ghi danh')

@section('content')
@include('partials.accounting-nav')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-semibold">{{ $enrollment->student?->name }} — {{ $enrollment->course?->name }}</h1>
        <p class="text-sm text-slate-500">Ghi danh ngày {{ $enrollment->enrolled_on->format('d/m/Y') }} · {{ $enrollment->statusLabel() }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Sửa</a>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        {{-- Tổng quan công nợ --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Học phí (sau giảm)</p>
                <p class="mt-1 text-xl font-bold">@vnd($enrollment->netTuition())</p>
                @if($enrollment->discount > 0)
                    <p class="text-xs text-slate-400">Đã giảm @vnd($enrollment->discount)</p>
                @endif
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Đã nộp</p>
                <p class="mt-1 text-xl font-bold text-emerald-600">@vnd($enrollment->paidAmount())</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Còn lại</p>
                <p class="mt-1 text-xl font-bold {{ $enrollment->balance() > 0 ? 'text-amber-600' : 'text-slate-400' }}">@vnd($enrollment->balance())</p>
            </div>
        </div>

        {{-- Lịch sử thu --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 font-semibold">Lịch sử nộp học phí</h2>
            <table class="min-w-full text-sm">
                <tbody>
                    @forelse($enrollment->transactions->sortByDesc('occurred_on') as $t)
                        <tr class="border-b last:border-0">
                            <td class="py-2 font-mono text-xs">{{ $t->code }}</td>
                            <td class="py-2 text-slate-500">{{ $t->occurred_on->format('d/m/Y') }}</td>
                            <td class="py-2">{{ $t->paymentMethodLabel() }}</td>
                            <td class="py-2 text-right font-semibold text-emerald-600">@vnd($t->amount)</td>
                        </tr>
                    @empty
                        <tr><td class="py-3 text-slate-400">Chưa có khoản thu nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($enrollment->note)
            <p class="text-sm text-slate-500">Ghi chú: {{ $enrollment->note }}</p>
        @endif
    </div>

    {{-- Form thu học phí --}}
    <div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 font-semibold">Thu học phí</h2>
            @if($enrollment->status === 'cancelled')
                <p class="text-sm text-slate-400">Ghi danh đã hủy, không thể thu thêm.</p>
            @else
                <form method="POST" action="{{ route('admin.enrollments.pay', $enrollment) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Số tiền (₫)</label>
                        <input type="number" name="amount" min="1" step="1000" value="{{ max(0, $enrollment->balance()) }}" required class="w-full rounded-lg border-slate-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Ngày thu</label>
                        <input type="date" name="occurred_on" value="{{ now(config('icandoit.timezone'))->toDateString() }}" required class="w-full rounded-lg border-slate-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Hình thức</label>
                        <select name="payment_method" class="w-full rounded-lg border-slate-300">
                            <option value="cash">Tiền mặt</option>
                            <option value="bank">Chuyển khoản</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <button class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Lập phiếu thu</button>
                </form>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}" class="mt-4" onsubmit="return confirm('Xóa ghi danh này?')">
            @csrf @method('DELETE')
            <button class="text-sm text-rose-600 hover:underline">Xóa ghi danh</button>
        </form>
    </div>
</div>
@endsection
