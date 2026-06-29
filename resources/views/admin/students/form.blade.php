@extends('layouts.app')

@section('title', $student->exists ? 'Sửa học viên' : 'Thêm học viên')

@section('content')
@include('partials.accounting-nav')

@php
    $action = $student->exists ? route('admin.students.update', $student) : route('admin.students.store');
@endphp

<div class="mx-auto max-w-2xl">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold">{{ $student->exists ? 'Sửa học viên' : 'Thêm học viên' }}</h1>
        <a href="{{ route('admin.students.index') }}" class="text-sm text-slate-500 hover:underline">← Quay lại</a>
    </div>

    <form method="POST" action="{{ $action }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
        @csrf
        @if($student->exists) @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Mã học viên</label>
                <input type="text" name="code" value="{{ old('code', $student->code) }}" required class="w-full rounded-lg border-slate-300" placeholder="HV001" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Họ tên</label>
                <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="w-full rounded-lg border-slate-300" />
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full rounded-lg border-slate-300" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $student->email) }}" class="w-full rounded-lg border-slate-300" />
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Ngày sinh</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($student->date_of_birth)->toDateString()) }}" class="w-full rounded-lg border-slate-300" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Địa chỉ</label>
                <input type="text" name="address" value="{{ old('address', $student->address) }}" class="w-full rounded-lg border-slate-300" />
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Ghi chú</label>
            <textarea name="note" rows="2" class="w-full rounded-lg border-slate-300">{{ old('note', $student->note) }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $student->is_active)) class="rounded border-slate-300" />
            Đang theo học (active)
        </label>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.students.index') }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Hủy</a>
            <button class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ $student->exists ? 'Cập nhật' : 'Lưu' }}</button>
        </div>
    </form>
</div>
@endsection
