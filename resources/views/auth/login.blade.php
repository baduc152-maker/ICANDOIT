@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="flex min-h-[70vh] items-center justify-center">
    <div class="w-full max-w-sm">
        <div class="mb-6 text-center">
            <img src="{{ asset(config('center.brand.logo_mark')) }}" alt="ICANDOIT" class="mx-auto mb-3 h-16 w-auto">
            <h1 class="text-2xl font-bold">Khu vực nội bộ</h1>
            <p class="text-sm text-slate-500">Đăng nhập để check-in / check-out</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Mật khẩu</label>
                    <input type="password" name="password" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    Ghi nhớ đăng nhập
                </label>
                <button type="submit"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 font-medium text-white transition hover:bg-indigo-700">
                    Đăng nhập
                </button>
            </form>
        </div>

        <p class="mt-5 text-center text-sm text-slate-500">
            <a href="{{ route('site.home') }}" class="hover:text-indigo-600">← Về website trung tâm</a>
        </p>
    </div>
</div>
@endsection
