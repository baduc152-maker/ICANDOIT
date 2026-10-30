{{-- Một mục trong danh sách nội dung (khoá học, giảng viên, bài viết...). --}}
<div class="item-card rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" data-item>
    <div class="mb-4 flex items-center justify-between gap-3 border-b pb-3">
        <h3 class="text-sm font-semibold text-slate-700">
            {{ $schema['item_label'] ?? 'Mục' }} <span data-number>#</span>
        </h3>
        <div class="flex items-center gap-1">
            <button type="button" data-move="up" title="Chuyển lên"
                    class="rounded-md border border-slate-200 px-2 py-1 text-xs text-slate-500 hover:bg-slate-50">↑</button>
            <button type="button" data-move="down" title="Chuyển xuống"
                    class="rounded-md border border-slate-200 px-2 py-1 text-xs text-slate-500 hover:bg-slate-50">↓</button>
            <button type="button" data-remove title="Xoá mục này"
                    class="rounded-md border border-rose-200 px-2 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50">Xoá</button>
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        @foreach($schema['fields'] as $key => $field)
            @include('admin.content._field', [
                'field' => $field,
                'name' => "items[$index][$key]",
                'id' => "item-$index-$key",
                'value' => $item[$key] ?? '',
                'hasError' => $errors->has("items.$index.$key"),
                'errorMessage' => $errors->first("items.$index.$key"),
            ])
        @endforeach
    </div>
</div>
