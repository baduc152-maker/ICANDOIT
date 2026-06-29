@extends('layouts.app')

@section('title', 'Danh mục thu/chi')

@section('content')
@include('partials.accounting-nav')

<h1 class="mb-6 text-xl font-semibold">Danh mục thu / chi</h1>

<div class="grid gap-6 lg:grid-cols-2">
    @foreach([['income', 'Danh mục THU', $income, 'emerald'], ['expense', 'Danh mục CHI', $expense, 'rose']] as [$type, $title, $items, $color])
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 font-semibold text-{{ $color }}-700">{{ $title }}</h2>

            <ul class="mb-4 space-y-2">
                @forelse($items as $cat)
                    <li class="flex items-center justify-between gap-2 border-b py-2 last:border-0">
                        <form method="POST" action="{{ route('admin.categories.update', $cat) }}" class="flex flex-1 items-center gap-2">
                            @csrf @method('PUT')
                            <input type="hidden" name="type" value="{{ $cat->type }}" />
                            <input type="text" name="name" value="{{ $cat->name }}" class="flex-1 rounded-lg border-slate-200 text-sm" />
                            <span class="text-xs text-slate-400">{{ $cat->transactions_count }} phiếu</span>
                            <button class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium hover:bg-slate-200">Lưu</button>
                        </form>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Xóa danh mục {{ $cat->name }}?')">
                            @csrf @method('DELETE')
                            <button class="rounded-lg px-2 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-50">Xóa</button>
                        </form>
                    </li>
                @empty
                    <li class="py-2 text-sm text-slate-400">Chưa có danh mục.</li>
                @endforelse
            </ul>

            <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-2 border-t pt-4">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}" />
                <input type="text" name="name" placeholder="Tên danh mục mới..." required class="flex-1 rounded-lg border-slate-300 text-sm" />
                <button class="rounded-lg bg-{{ $color }}-600 px-4 py-2 text-sm font-semibold text-white hover:bg-{{ $color }}-700">Thêm</button>
            </form>
        </div>
    @endforeach
</div>
@endsection
