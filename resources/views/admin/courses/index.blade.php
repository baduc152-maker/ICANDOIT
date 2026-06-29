@extends('layouts.app')

@section('title', 'Khóa học')

@section('content')
@include('partials.accounting-nav')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-semibold">Khóa học</h1>
    <a href="{{ route('admin.courses.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">+ Thêm khóa học</a>
</div>

<div class="rounded-2xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-left text-xs uppercase text-slate-400">
                    <th class="px-4 py-3">Mã</th>
                    <th class="px-4 py-3">Tên khóa học</th>
                    <th class="px-4 py-3 text-right">Học phí</th>
                    <th class="px-4 py-3 text-right">Số buổi</th>
                    <th class="px-4 py-3 text-right">Đã ghi danh</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $c)
                    <tr class="border-b last:border-0 hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $c->code }}</td>
                        <td class="px-4 py-3">
                            <span class="font-medium">{{ $c->name }}</span>
                            @unless($c->is_active)<span class="ml-1 rounded bg-slate-100 px-1.5 text-xs text-slate-500">ngừng</span>@endunless
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">@vnd($c->fee)</td>
                        <td class="px-4 py-3 text-right text-slate-500">{{ $c->sessions ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-slate-500">{{ $c->enrollments_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.courses.edit', $c) }}" class="text-indigo-600 hover:underline">Sửa</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">Chưa có khóa học nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $courses->links() }}</div>
@endsection
