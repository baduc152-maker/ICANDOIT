@extends('site.layouts.app')

@section('title', 'Cẩm nang học thuật')
@section('meta_description', 'Bài viết chia sẻ lộ trình học, kỹ năng Writing, Speaking và kinh nghiệm luyện thi IELTS từ phòng học thuật ICANDOIT.')

@section('content')

    <section class="page-hero">
        <div class="container">
            <div class="breadcrumb"><a href="{{ route('site.home') }}">Trang chủ</a> / Cẩm nang</div>
            <h1>Cẩm nang học thuật</h1>
            <p>Những bài viết được biên soạn bởi đội ngũ học thuật của trung tâm, tập trung vào phương pháp
                học đúng thay vì mẹo làm bài ngắn hạn.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="grid grid--3">
                @foreach($posts as $post)
                    <article class="post-card reveal">
                        <div class="post-card__cover"><span>{{ $post['category'] }}</span></div>
                        <div class="post-card__body">
                            <span class="post-card__date">{{ $post['date'] }}</span>
                            <h3>{{ $post['title'] }}</h3>
                            <p>{{ $post['excerpt'] }}</p>
                            <a class="more" href="{{ route('site.post', $post['slug']) }}">Đọc tiếp →</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

@endsection
