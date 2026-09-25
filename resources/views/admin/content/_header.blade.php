{{-- Tiêu đề, trạng thái và các thao tác chung của một nhóm nội dung. --}}
<div class="mb-6">
    <a href="{{ route('admin.content') }}" class="text-sm text-slate-500 hover:text-indigo-600">← Tất cả nội dung</a>

    <div class="mt-2 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold">{{ $schema['label'] }}</h2>
            <p class="text-sm text-slate-500">{{ $schema['summary'] }}</p>
            @if($record)
                <p class="mt-1 text-xs text-slate-400">
                    Cập nhật lần cuối {{ $record->updated_at?->format('d/m/Y H:i') }}
                    @if($record->editor) · {{ $record->editor->name }} @endif
                </p>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if(! empty($schema['preview']))
                <a href="{{ route($schema['preview']) }}" target="_blank" rel="noopener"
                   class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    Xem trên website
                </a>
            @endif

            @if($customised)
                <form method="POST" action="{{ route('admin.content.reset', $section) }}"
                      onsubmit="return confirm('Khôi phục nhóm nội dung này về bản mặc định? Các chỉnh sửa hiện tại sẽ bị xoá.');">
                    @csrf
                    <button class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100">
                        Khôi phục mặc định
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
