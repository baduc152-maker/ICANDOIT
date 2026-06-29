@extends('layouts.app')

@section('title', $course->exists ? 'Sửa khóa học' : 'Thêm khóa học')

@section('content')
@include('partials.accounting-nav')

@php
    $action = $course->exists ? route('admin.courses.update', $course) : route('admin.courses.store');
@endphp

<div class="mx-auto max-w-2xl">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold">{{ $course->exists ? 'Sửa khóa học' : 'Thêm khóa học' }}</h1>
        <a href="{{ route('admin.courses.index') }}" class="text-sm text-slate-500 hover:underline">← Quay lại</a>
    </div>

    <form method="POST" action="{{ $action }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
        @csrf
        @if($course->exists) @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Mã khóa học</label>
                <input type="text" name="code" value="{{ old('code', $course->code) }}" required class="w-full rounded-lg border-slate-300" placeholder="IELTS-01" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Tên khóa học</label>
                <input type="text" name="name" value="{{ old('name', $course->name) }}" required class="w-full rounded-lg border-slate-300" placeholder="IELTS 6.0" />
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Học phí (₫)</label>
                <input type="number" name="fee" min="0" step="1000" value="{{ old('fee', $course->fee) }}" required class="w-full rounded-lg border-slate-300" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Số buổi</label>
                <input type="number" name="sessions" min="0" value="{{ old('sessions', $course->sessions) }}" class="w-full rounded-lg border-slate-300" />
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Mô tả</label>
            <textarea name="description" rows="3" class="w-full rounded-lg border-slate-300">{{ old('description', $course->description) }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $course->is_active)) class="rounded border-slate-300" />
            Đang mở (active)
        </label>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.courses.index') }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Hủy</a>
            <button class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ $course->exists ? 'Cập nhật' : 'Lưu' }}</button>
        </div>
    </form>
</div>
@endsection
