<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Điểm danh từ xa') · ICANDOIT</title>
    <link rel="icon" href="{{ asset(config('center.brand.logo_mark')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="h-full text-slate-800">
<div class="min-h-full">
    @auth
    <nav class="bg-white shadow-sm">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-[#e1232b]">
                        <img src="{{ asset(config('center.brand.logo_mark')) }}" alt="ICANDOIT" class="h-9 w-auto">
                        ICANDOIT
                    </a>
                    <div class="hidden flex-wrap gap-1 sm:flex">
                        <a href="{{ route('dashboard') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Điểm danh</a>
                        <a href="{{ route('attendance.history') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Lịch sử</a>
                        <a href="{{ route('schedule') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Lịch công việc</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.attendance') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Điểm danh chung</a>
                            <a href="{{ route('admin.consultations') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Đăng ký tư vấn</a>
                            <a href="{{ route('admin.timetable') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Thời khoá biểu</a>
                            <a href="{{ route('admin.tasks') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Giao việc</a>
                            <a href="{{ route('admin.content') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Nội dung</a>
                        @endif
                        <a href="{{ route('site.home') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Website</a>
                        @foreach(\App\Support\AppLink::for(\App\Support\AppLink::INTERNAL) as $app)
                            <a href="{{ $app['url'] }}" target="_blank" rel="noopener" title="{{ $app['description'] }}"
                               class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50">{{ $app['label'] }} ↗</a>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden text-sm text-slate-500 sm:block">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-md bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if(session('status'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        Khu vực nội bộ ICANDOIT · <a href="{{ route('site.home') }}" class="underline hover:text-slate-600">Về website trung tâm</a>
    </footer>
</div>
</body>
</html>
