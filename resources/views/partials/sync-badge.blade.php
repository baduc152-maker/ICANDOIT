@php
    $map = [
        \App\Models\Attendance::SYNC_SYNCED => ['Đã đồng bộ', 'bg-emerald-100 text-emerald-700'],
        \App\Models\Attendance::SYNC_FAILED => ['Lỗi', 'bg-rose-100 text-rose-700'],
        \App\Models\Attendance::SYNC_PENDING => ['Chờ', 'bg-slate-100 text-slate-600'],
    ];
    [$label, $class] = $map[$record->sync_status] ?? ['—', 'bg-slate-100 text-slate-600'];
@endphp
<span class="rounded-full px-2 py-0.5 text-xs {{ $class }}" @if($record->sync_message) title="{{ $record->sync_message }}" @endif>
    {{ $label }}@if($record->icandoit_ref) · {{ $record->icandoit_ref }}@endif
</span>
