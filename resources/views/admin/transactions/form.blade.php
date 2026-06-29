@extends('layouts.app')

@section('title', $transaction->exists ? 'Sửa phiếu' : 'Lập phiếu thu/chi')

@section('content')
@include('partials.accounting-nav')

@php
    $action = $transaction->exists ? route('admin.transactions.update', $transaction) : route('admin.transactions.store');
@endphp

<div class="mx-auto max-w-2xl">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold">{{ $transaction->exists ? 'Sửa phiếu '.$transaction->code : 'Lập phiếu thu / chi' }}</h1>
        <a href="{{ route('admin.transactions.index') }}" class="text-sm text-slate-500 hover:underline">← Quay lại</a>
    </div>

    <form method="POST" action="{{ $action }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
        @csrf
        @if($transaction->exists) @method('PUT') @endif

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Loại phiếu</label>
            <div class="flex gap-3">
                <label class="flex flex-1 cursor-pointer items-center gap-2 rounded-lg border border-slate-300 px-4 py-3 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                    <input type="radio" name="type" value="income" @checked(old('type', $transaction->type) === 'income')>
                    <span class="font-medium text-emerald-700">Phiếu thu</span>
                </label>
                <label class="flex flex-1 cursor-pointer items-center gap-2 rounded-lg border border-slate-300 px-4 py-3 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50">
                    <input type="radio" name="type" value="expense" @checked(old('type', $transaction->type) === 'expense')>
                    <span class="font-medium text-rose-700">Phiếu chi</span>
                </label>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Số tiền (₫)</label>
                <input type="number" name="amount" min="0" step="1000" value="{{ old('amount', $transaction->amount) }}" required class="w-full rounded-lg border-slate-300" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Ngày</label>
                <input type="date" name="occurred_on" value="{{ old('occurred_on', optional($transaction->occurred_on)->toDateString()) }}" required class="w-full rounded-lg border-slate-300" />
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Danh mục</label>
                <select name="category_id" class="w-full rounded-lg border-slate-300">
                    <option value="">— Chọn danh mục —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $transaction->category_id) == $cat->id)>
                            [{{ $cat->typeLabel() }}] {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Hình thức thanh toán</label>
                <select name="payment_method" class="w-full rounded-lg border-slate-300">
                    <option value="cash" @selected(old('payment_method', $transaction->payment_method) === 'cash')>Tiền mặt</option>
                    <option value="bank" @selected(old('payment_method', $transaction->payment_method) === 'bank')>Chuyển khoản</option>
                    <option value="other" @selected(old('payment_method', $transaction->payment_method) === 'other')>Khác</option>
                </select>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Học viên (nếu là thu học phí)</label>
            <select name="student_id" class="w-full rounded-lg border-slate-300">
                <option value="">— Không gắn học viên —</option>
                @foreach($students as $s)
                    <option value="{{ $s->id }}" @selected(old('student_id', $transaction->student_id) == $s->id)>{{ $s->code }} · {{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Diễn giải</label>
            <input type="text" name="description" value="{{ old('description', $transaction->description) }}" maxlength="255" class="w-full rounded-lg border-slate-300" placeholder="Ví dụ: Thu học phí tháng 6, chi tiền điện..." />
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.transactions.index') }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Hủy</a>
            <button class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ $transaction->exists ? 'Cập nhật' : 'Lưu phiếu' }}</button>
        </div>
    </form>
</div>
@endsection
