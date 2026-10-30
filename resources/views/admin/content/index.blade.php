@extends('layouts.app')

@section('title', 'Nội dung website')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-lg font-semibold">Nội dung website</h2>
        <p class="text-sm text-slate-500">
            Chỉnh sửa mọi thông tin hiển thị trên website trung tâm. Lưu xong là website cập nhật ngay.
        </p>
    </div>
    <a href="{{ route('site.home') }}" target="_blank" rel="noopener"
       class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900">Xem website</a>
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @foreach($sections as $key => $item)
        <a href="{{ route('admin.content.edit', $key) }}"
           class="group flex h-full flex-col rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="mb-2 flex items-start justify-between gap-3">
                <h3 class="font-semibold text-slate-800 group-hover:text-indigo-600">{{ $item['schema']['label'] }}</h3>
                @if($item['customised'])
                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">Đã sửa</span>
                @else
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Mặc định</span>
                @endif
            </div>

            <p class="flex-1 text-sm text-slate-500">{{ $item['schema']['summary'] }}</p>

            <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                <span>
                    {{ $item['schema']['type'] === 'list'
                        ? $item['count'].' '.mb_strtolower($item['schema']['item_label'] ?? 'mục')
                        : $item['count'].' thông tin' }}
                </span>
                @if($item['record'])
                    <span>Cập nhật {{ $item['record']->updated_at?->format('d/m/Y H:i') }}</span>
                @endif
            </div>
        </a>
    @endforeach
</div>

<div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 text-sm text-slate-600">
    <p class="mb-1 font-medium text-slate-800">Cách hoạt động</p>
    <p>
        Nội dung sửa ở đây được lưu vào cơ sở dữ liệu và ghi đè lên bản mặc định trong mã nguồn.
        Nhóm nào chưa chỉnh sửa vẫn dùng nội dung gốc; bấm <em>Khôi phục mặc định</em> trong từng
        nhóm để quay lại bản gốc bất cứ lúc nào.
    </p>
</div>
@endsection
