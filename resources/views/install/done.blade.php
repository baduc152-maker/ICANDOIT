@extends('layouts.app')

@section('title', 'Cài đặt xong')

@section('content')
<div class="mx-auto max-w-sm text-center">
    <img src="{{ asset(config('center.brand.logo_mark')) }}" alt="ICANDOIT" class="mx-auto mb-3 h-16 w-auto">
    <h1 class="text-2xl font-bold">Cài đặt xong</h1>
    <p class="mt-2 text-sm text-slate-500">Website đã sẵn sàng tại {{ $appUrl }}</p>

    <div class="mt-6 rounded-2xl bg-white p-6 text-left shadow-sm">
        <p class="text-sm font-medium text-slate-700">Tài khoản quản trị</p>
        <p class="mt-1 text-sm text-slate-600">{{ $admin->email }}</p>
        <p class="text-xs text-slate-500">Mật khẩu là mật khẩu anh/chị vừa đặt.</p>

        <a href="{{ route('login') }}"
           class="mt-4 block rounded-lg bg-indigo-600 px-4 py-2.5 text-center font-medium text-white hover:bg-indigo-700">
            Đăng nhập ngay
        </a>
        <a href="{{ route('site.home') }}" class="mt-2 block text-center text-sm text-slate-500 hover:text-slate-700">
            Xem website
        </a>
    </div>

    @unless($locked)
        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-100 px-4 py-3 text-left text-sm text-amber-700">
            Không ghi được file đánh dấu trong thư mục <code>storage/app</code>. Trang cài đặt vẫn tự khoá nhờ đã có tài
            khoản quản trị, nhưng nên đặt quyền ghi cho thư mục <code>storage</code> để website hoạt động đầy đủ.
        </div>
    @endunless

    <div class="mt-4 rounded-2xl bg-white p-6 text-left shadow-sm">
        <p class="text-sm font-medium text-slate-700">Việc nên làm tiếp</p>
        <ul class="mt-2 list-disc pl-5 text-sm text-slate-600">
            <li>Vào <strong>Nội dung</strong> để điền hotline, địa chỉ, học phí, giảng viên thật.</li>
            <li>Bật SSL miễn phí trong Control Panel của hosting để website chạy https.</li>
            <li>Xoá file <code>giai-nen.php</code> và file nén nếu chúng còn trong thư mục.</li>
        </ul>
    </div>
</div>
@endsection
