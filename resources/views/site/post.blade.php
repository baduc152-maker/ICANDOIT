@extends('site.layouts.app')

@section('title', $post['title'])
@section('meta_description', $post['excerpt'])

@section('content')

    <section class="page-hero">
        <div class="container container--narrow">
            <div class="breadcrumb">
                <a href="{{ route('site.home') }}">Trang chủ</a> /
                <a href="{{ route('site.posts') }}">Cẩm nang</a> / {{ $post['category'] }}
            </div>
            <h1>{{ $post['title'] }}</h1>
            <p>{{ $post['date'] }} · {{ $post['category'] }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container container--narrow article">
            <p class="lead">{{ $post['excerpt'] }}</p>
            @foreach($post['body'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach

            <div class="panel" style="margin-top:36px">
                <h3>Cần lộ trình cụ thể cho trường hợp của bạn?</h3>
                <p style="color:var(--muted)">Đăng ký buổi kiểm tra đầu vào miễn phí để nhận tư vấn chi tiết
                    từ đội ngũ học thuật.</p>
                <a class="btn btn--primary" href="{{ route('site.contact') }}#dang-ky">Đăng ký tư vấn</a>
            </div>
        </div>
    </section>

    @if($related->isNotEmpty())
        <section class="section section--paper">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">Đọc thêm</span>
                    <h2>Bài viết liên quan</h2>
                </div>
                <div class="grid grid--3">
                    @foreach($related as $item)
                        <article class="post-card reveal">
                            <div class="post-card__cover"><span>{{ $item['category'] }}</span></div>
                            <div class="post-card__body">
                                <span class="post-card__date">{{ $item['date'] }}</span>
                                <h3>{{ $item['title'] }}</h3>
                                <p>{{ $item['excerpt'] }}</p>
                                <a class="more" href="{{ route('site.post', $item['slug']) }}">Đọc tiếp →</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
