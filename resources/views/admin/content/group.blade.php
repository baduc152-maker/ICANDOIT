@extends('layouts.app')

@section('title', $schema['label'])

@section('content')
@include('admin.content._header', ['section' => $section, 'schema' => $schema, 'customised' => $customised, 'record' => $record])

<form method="POST" action="{{ route('admin.content.update', $section) }}" class="rounded-2xl bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')

    <div class="grid gap-5 sm:grid-cols-2">
        @foreach($schema['fields'] as $key => $field)
            @include('admin.content._field', [
                'field' => $field,
                'name' => "fields[$key]",
                'id' => "field-$key",
                'value' => old("fields.$key", $fields[$key]),
                'hasError' => $errors->has("fields.$key"),
                'errorMessage' => $errors->first("fields.$key"),
            ])
        @endforeach
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-3 border-t pt-5">
        <button class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
            Lưu thay đổi
        </button>
        <a href="{{ route('admin.content') }}" class="text-sm text-slate-500 hover:text-slate-700">Huỷ</a>
    </div>
</form>
@endsection
