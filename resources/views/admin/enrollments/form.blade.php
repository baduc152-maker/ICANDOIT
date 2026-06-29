@extends('layouts.app')

@section('title', $enrollment->exists ? 'Sửa ghi danh' : 'Ghi danh mới')

@section('content')
@include('partials.accounting-nav')

@php
    $action = $enrollment->exists ? route('admin.enrollments.update', $enrollment) : route('admin.enrollments.store');
    $courseFees = $courses->mapWithKeys(fn ($c) => [$c->id => (float) $c->fee]);
@endphp

<div class="mx-auto max-w-2xl">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold">{{ $enrollment->exists ? 'Sửa ghi danh' : 'Ghi danh mới' }}</h1>
        <a href="{{ route('admin.enrollments.index') }}" class="text-sm text-slate-500 hover:underline">← Quay lại</a>
    </div>

    <form method="POST" action="{{ $action }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
        @csrf
        @if($enrollment->exists) @method('PUT') @endif

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Học viên</label>
            <select name="student_id" required class="w-full rounded-lg border-slate-300">
                <option value="">— Chọn học viên —</option>
                @foreach($students as $s)
                    <option value="{{ $s->id }}" @selected(old('student_id', $enrollment->student_id) == $s->id)>{{ $s->code }} · {{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Khóa học</label>
            <select name="course_id" id="course_id" required class="w-full rounded-lg border-slate-300">
                <option value="">— Chọn khóa học —</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" @selected(old('course_id', $enrollment->course_id) == $c->id)>{{ $c->name }} ({{ \App\Support\Money::vnd($c->fee) }})</option>
                @endforeach
            </select>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Học phí (₫)</label>
                <input type="number" name="tuition_amount" id="tuition_amount" min="0" step="1000" value="{{ old('tuition_amount', $enrollment->tuition_amount) }}" required class="w-full rounded-lg border-slate-300" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Giảm giá (₫)</label>
                <input type="number" name="discount" min="0" step="1000" value="{{ old('discount', $enrollment->discount ?? 0) }}" class="w-full rounded-lg border-slate-300" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Ngày ghi danh</label>
                <input type="date" name="enrolled_on" value="{{ old('enrolled_on', optional($enrollment->enrolled_on)->toDateString()) }}" required class="w-full rounded-lg border-slate-300" />
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Trạng thái</label>
            <select name="status" class="w-full rounded-lg border-slate-300">
                <option value="active" @selected(old('status', $enrollment->status) === 'active')>Đang học</option>
                <option value="completed" @selected(old('status', $enrollment->status) === 'completed')>Hoàn thành</option>
                <option value="cancelled" @selected(old('status', $enrollment->status) === 'cancelled')>Đã hủy</option>
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Ghi chú</label>
            <textarea name="note" rows="2" class="w-full rounded-lg border-slate-300">{{ old('note', $enrollment->note) }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.enrollments.index') }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Hủy</a>
            <button class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ $enrollment->exists ? 'Cập nhật' : 'Ghi danh' }}</button>
        </div>
    </form>
</div>

<script>
    // Tự điền học phí theo khóa học khi chọn (nếu ô học phí đang trống / bằng 0).
    const fees = @json($courseFees);
    const courseSelect = document.getElementById('course_id');
    const tuitionInput = document.getElementById('tuition_amount');
    courseSelect.addEventListener('change', function () {
        const fee = fees[this.value];
        if (fee !== undefined && (!tuitionInput.value || Number(tuitionInput.value) === 0)) {
            tuitionInput.value = fee;
        }
    });
</script>
@endsection
