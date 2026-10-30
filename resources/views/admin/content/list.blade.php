@extends('layouts.app')

@section('title', $schema['label'])

@php
    // Sau khi xác thực lỗi thì giữ lại đúng những gì người dùng vừa nhập.
    $rows = old('items', $items);
    $blank = array_fill_keys(array_keys($schema['fields']), '');
@endphp

@section('content')
@include('admin.content._header', ['section' => $section, 'schema' => $schema, 'customised' => $customised, 'record' => $record])

<form method="POST" action="{{ route('admin.content.update', $section) }}">
    @csrf
    @method('PUT')

    <div class="space-y-4" data-items>
        @foreach($rows as $index => $item)
            @include('admin.content._item', ['index' => $index, 'item' => $item, 'schema' => $schema])
        @endforeach
    </div>

    <p class="mt-4 hidden rounded-xl border border-dashed border-slate-300 bg-white px-4 py-6 text-center text-sm text-slate-500"
       data-empty>
        Chưa có mục nào. Bấm “Thêm {{ mb_strtolower($schema['item_label'] ?? 'mục') }}” để nhập mục đầu tiên.
    </p>

    <div class="sticky bottom-0 mt-5 flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm backdrop-blur">
        <button type="button" data-add
                class="rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
            + Thêm {{ mb_strtolower($schema['item_label'] ?? 'mục') }}
        </button>
        <button class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
            Lưu thay đổi
        </button>
        <a href="{{ route('admin.content') }}" class="text-sm text-slate-500 hover:text-slate-700">Huỷ</a>
        <span class="ml-auto text-xs text-slate-500"><span data-count>0</span> mục</span>
    </div>
</form>

<template data-template>
    @include('admin.content._item', ['index' => '__IDX__', 'item' => $blank, 'schema' => $schema])
</template>

<script>
(function () {
    'use strict';

    var list = document.querySelector('[data-items]');
    var template = document.querySelector('[data-template]');
    var empty = document.querySelector('[data-empty]');
    var counter = document.querySelector('[data-count]');
    var nextIndex = {{ count($rows) ? (max(array_map('intval', array_keys($rows))) + 1) : 0 }};

    function refresh() {
        var cards = list.querySelectorAll('[data-item]');
        cards.forEach(function (card, i) {
            card.querySelector('[data-number]').textContent = '#' + (i + 1);
            card.querySelector('[data-move="up"]').disabled = i === 0;
            card.querySelector('[data-move="down"]').disabled = i === cards.length - 1;
            card.querySelectorAll('button[disabled]').forEach(function (b) { b.classList.add('opacity-30'); });
            card.querySelectorAll('button:not([disabled])').forEach(function (b) { b.classList.remove('opacity-30'); });
        });
        counter.textContent = cards.length;
        empty.classList.toggle('hidden', cards.length > 0);
    }

    document.querySelector('[data-add]').addEventListener('click', function () {
        var html = template.innerHTML.replace(/__IDX__/g, String(nextIndex++));
        var holder = document.createElement('div');
        holder.innerHTML = html.trim();
        var card = holder.firstElementChild;
        list.appendChild(card);
        refresh();
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        var first = card.querySelector('input, textarea, select');
        if (first) { first.focus({ preventScroll: true }); }
    });

    list.addEventListener('click', function (event) {
        var card = event.target.closest('[data-item]');
        if (!card) { return; }

        if (event.target.hasAttribute('data-remove')) {
            if (window.confirm('Xoá mục này khỏi danh sách?')) {
                card.remove();
                refresh();
            }
            return;
        }

        var move = event.target.getAttribute('data-move');
        if (move === 'up' && card.previousElementSibling) {
            list.insertBefore(card, card.previousElementSibling);
            refresh();
        } else if (move === 'down' && card.nextElementSibling) {
            list.insertBefore(card.nextElementSibling, card);
            refresh();
        }
    });

    refresh();
})();
</script>
@endsection
