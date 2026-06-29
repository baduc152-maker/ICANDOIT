@php
    $tabs = [
        ['route' => 'admin.accounting', 'active' => 'admin.accounting', 'label' => 'Tổng quan'],
        ['route' => 'admin.transactions.index', 'active' => 'admin.transactions.*', 'label' => 'Sổ quỹ thu/chi'],
        ['route' => 'admin.students.index', 'active' => 'admin.students.*', 'label' => 'Học viên'],
        ['route' => 'admin.courses.index', 'active' => 'admin.courses.*', 'label' => 'Khóa học'],
        ['route' => 'admin.enrollments.index', 'active' => 'admin.enrollments.*', 'label' => 'Ghi danh'],
        ['route' => 'admin.accounting.report', 'active' => 'admin.accounting.report', 'label' => 'Báo cáo'],
        ['route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'label' => 'Danh mục'],
    ];
@endphp
<div class="mb-6 flex flex-wrap gap-1 rounded-xl bg-white p-1 shadow-sm">
    @foreach($tabs as $tab)
        <a href="{{ route($tab['route']) }}"
           class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($tab['active']) ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
