{{-- Một ô nhập nội dung, dựng theo khai báo trong App\Support\SiteContentSchema. --}}
@php
    $inputClass = 'w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 '
        .($hasError ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500');
    $rows = match ($field['type']) {
        'paragraphs' => 8,
        'pairs', 'lines' => 4,
        default => 3,
    };
@endphp

<div class="{{ in_array($field['type'], ['textarea', 'lines', 'paragraphs', 'pairs'], true) ? 'sm:col-span-2' : '' }}">
    @if($field['type'] === 'checkbox')
        <label class="flex items-center gap-2 pt-6 text-sm font-medium text-slate-700">
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox" name="{{ $name }}" value="1" @checked($value)
                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            {{ $field['label'] }}
        </label>
    @else
        <label class="mb-1 block text-sm font-medium text-slate-700" for="{{ $id }}">{{ $field['label'] }}</label>

        @if($field['type'] === 'select')
            <select id="{{ $id }}" name="{{ $name }}" class="{{ $inputClass }}">
                @foreach($field['options'] as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                @endforeach
            </select>
        @elseif(in_array($field['type'], ['textarea', 'lines', 'paragraphs', 'pairs'], true))
            <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" class="{{ $inputClass }}">{{ $value }}</textarea>
        @else
            <input id="{{ $id }}" type="{{ $field['type'] === 'number' ? 'number' : 'text' }}"
                   name="{{ $name }}" value="{{ $value }}" class="{{ $inputClass }}">
        @endif
    @endif

    @if(! empty($field['help']))
        <p class="mt-1 text-xs text-slate-500">{{ $field['help'] }}</p>
    @endif
    @if($hasError)
        <p class="mt-1 text-xs font-medium text-rose-600">{{ $errorMessage }}</p>
    @endif
</div>
