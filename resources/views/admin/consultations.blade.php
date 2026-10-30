@extends('layouts.app')

@section('title', 'Đăng ký tư vấn')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-lg font-semibold">Yêu cầu tư vấn từ website</h2>
        <p class="text-sm text-slate-500">Danh sách khách để lại thông tin qua biểu mẫu đăng ký.</p>
    </div>
    <form method="GET" action="{{ route('admin.consultations') }}" class="flex items-end gap-2">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Trạng thái</label>
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Tất cả</option>
                @foreach(\App\Models\Consultation::statuses() as $key => $label)
                    <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Lọc</button>
        <a href="{{ route('admin.consultations.export') }}"
           class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Xuất CSV</a>
    </form>
</div>

<div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
    @foreach(\App\Models\Consultation::statuses() as $key => $label)
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-500">{{ $label }}</p>
            <p class="text-2xl font-bold text-indigo-600">{{ $summary[$key] ?? 0 }}</p>
        </div>
    @endforeach
</div>

<div class="rounded-2xl bg-white p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b text-slate-500">
                <tr>
                    <th class="py-2 pr-4">Thời gian</th>
                    <th class="py-2 pr-4">Khách đăng ký</th>
                    <th class="py-2 pr-4">Khoá quan tâm</th>
                    <th class="py-2 pr-4">Ghi chú</th>
                    <th class="py-2 pr-4">Xử lý</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($records as $r)
                    <tr>
                        <td class="py-3 pr-4 align-top text-xs text-slate-500">
                            {{ $r->created_at?->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3 pr-4 align-top">
                            <p class="font-medium">{{ $r->name }}</p>
                            <p class="text-xs text-slate-500">{{ $r->phone }}</p>
                            @if($r->email)
                                <p class="text-xs text-slate-500">{{ $r->email }}</p>
                            @endif
                        </td>
                        <td class="py-3 pr-4 align-top">{{ $r->course ?? '—' }}</td>
                        <td class="py-3 pr-4 align-top text-slate-600">{{ $r->note ?? '—' }}</td>
                        <td class="py-3 pr-4 align-top">
                            <form method="POST" action="{{ route('admin.consultations.update', $r) }}"
                                  class="flex flex-wrap items-center gap-2">
                                @csrf
                                <select name="status" class="rounded-lg border border-slate-300 px-2 py-1 text-xs">
                                    @foreach(\App\Models\Consultation::statuses() as $key => $label)
                                        <option value="{{ $key }}" @selected($r->status === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="staff_note" value="{{ $r->staff_note }}"
                                       placeholder="Ghi chú nội bộ"
                                       class="w-44 rounded-lg border border-slate-300 px-2 py-1 text-xs">
                                <button class="rounded-lg bg-slate-800 px-3 py-1 text-xs font-medium text-white hover:bg-slate-900">Lưu</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-400">Chưa có yêu cầu tư vấn nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $records->links() }}</div>
</div>
@endsection
