{{-- Trang báo không tìm thấy, dùng cho cả bản Laravel và bản trang tĩnh (404.html). --}}
@extends('site.layouts.app', ['hideCta' => true])

@section('title', 'Không tìm thấy trang')

@section('content')
    <section class="section">
        <div class="container" style="max-width:640px;text-align:center">
            <p class="eyebrow">Lỗi 404</p>
            <h1>Không tìm thấy trang bạn cần</h1>
            <p>Trang này có thể đã được đổi tên hoặc không còn tồn tại. Bạn thử quay về trang chủ
                hoặc xem danh sách khoá học nhé.</p>
            <div class="cta__actions" style="justify-content:center;margin-top:24px">
                <a class="btn btn--primary" href="{{ route('site.home') }}">Về trang chủ</a>
                <a class="btn btn--ghost" href="{{ route('site.courses') }}">Xem khoá học</a>
                <a class="btn btn--ghost" href="{{ route('site.contact') }}">Liên hệ</a>
            </div>
        </div>
    </section>
@endsection
