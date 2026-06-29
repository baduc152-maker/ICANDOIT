@extends('layouts.app')

@section('title', 'Học viên')

@section('content')
@include('partials.accounting-nav')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-semibold">Học viên</h1>
    <a href="{{ route('admin.students.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">+ Thêm học viên</a>
</div>

<form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="q" value="{{ $search }}" placeholder="Tìm theo tên, mã, số điện thoại..." class="w-full max-w-sm rounded-lg border-slate-300 text-sm" />
    <button class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Tìm</button>
</form>

<div class="rounded-2xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-left text-xs uppercase text-slate-400">
                    <th class="px-4 py-3">Mã</th>
                    <th class="px-4 py-3">Họ tên</th>
                    <th class="px-4 py-3">Liên hệ</th>
                    <th class="px-4 py-3 text-right">Học phí phải thu</th>
                    <th class="px-4 py-3 text-right">Đã thu</th>
                    <th class="px-4 py-3 text-right">Còn nợ</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                    <tr class="border-b last:border-0 hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $s->code }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.students.show', $s) }}" class="font-medium text-indigo-600 hover:underline">{{ $s->name }}</a>
                            @unless($s->is_active)<span class="ml-1 rounded bg-slate-100 px-1.5 text-xs text-slate-500">ngừng</span>@endunless
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $s->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">@vnd($s->totalTuition())</td>
                        <td class="px-4 py-3 text-right text-emerald-600">@vnd($s->totalPaid())</td>
                        <td class="px-4 py-3 text-right font-semibold {{ $s->balance() > 0 ? 'text-amber-600' : 'text-slate-400' }}">@vnd($s->balance())</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.students.edit', $s) }}" class="text-indigo-600 hover:underline">Sửa</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-slate-400">Chưa có học viên nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $students->links() }}</div>
@endsection
