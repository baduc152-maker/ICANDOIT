@extends('site.layouts.app')

@section('title', 'Khoá học')
@section('meta_description', 'Danh mục khoá học tại ICANDOIT: IELTS Foundation, IELTS 5.5-6.5, IELTS 7.0+, Pre-IELTS cho học sinh THCS, Academic Writing và lớp 1 kèm 1.')

@section('content')

    <section class="page-hero">
        <div class="container">
            <div class="breadcrumb"><a href="{{ route('site.home') }}">Trang chủ</a> / Khoá học</div>
            <h1>Hệ thống khoá học học thuật</h1>
            <p>Từ nền tảng đến chuyên sâu, mỗi khoá học có mục tiêu đầu ra rõ ràng và bài kiểm tra xếp lớp
                trước khi nhập học.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="grid grid--3">
                @foreach($courses as $course)
                    @include('site.partials.course-card', ['course' => $course])
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--paper">
        <div class="container container--narrow">
            <div class="section-head">
                <span class="eyebrow">Giải đáp</span>
                <h2>Chọn khoá học phù hợp</h2>
                <p>Nếu vẫn phân vân, hãy làm bài kiểm tra đầu vào miễn phí — trung tâm sẽ tư vấn lớp đúng
                    trình độ thay vì để bạn tự phỏng đoán.</p>
            </div>

            @foreach(config('center.faqs') as $faq)
                <details class="faq-item">
                    <summary>{{ $faq['q'] }}</summary>
                    <p>{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
    </section>

@endsection
