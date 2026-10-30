@extends('site.layouts.app')

@section('title', $course['name'])
@section('meta_description', $course['summary'])

@section('content')

    <section class="page-hero">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('site.home') }}">Trang chủ</a> /
                <a href="{{ route('site.courses') }}">Khoá học</a> / {{ $course['name'] }}
            </div>
            <h1>{{ $course['name'] }}</h1>
            <p>{{ $course['subtitle'] }} · {{ $course['target'] }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="split" style="align-items:flex-start">
                <div>
                    <span class="eyebrow">Tổng quan</span>
                    <h2>Khoá học dành cho ai?</h2>
                    <p class="lead">{{ $course['summary'] }}</p>

                    <ul class="check-list" style="margin-bottom:34px">
                        @foreach($course['for_whom'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>

                    <h2>Kết quả đầu ra</h2>
                    <ul class="check-list" style="margin-bottom:34px">
                        @foreach($course['outcomes'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>

                    <h2>Nội dung chương trình</h2>
                    <div class="panel">
                        @foreach($course['curriculum'] as $index => $module)
                            <div class="module">
                                <span class="module__idx">{{ $index + 1 }}</span>
                                <div>
                                    <h4>{{ $module['title'] }}</h4>
                                    <p>{{ $module['text'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="sticky-side">
                    <div class="panel" style="margin-bottom:24px">
                        <h3>Thông tin khoá học</h3>
                        <ul class="info-list">
                            <li>@include('site.partials.icon', ['name' => 'layers'])<span><strong>Trình độ đầu vào</strong>{{ $course['level'] }}</span></li>
                            <li>@include('site.partials.icon', ['name' => 'target'])<span><strong>Mục tiêu đầu ra</strong>{{ $course['target'] }}</span></li>
                            <li>@include('site.partials.icon', ['name' => 'timer'])<span><strong>Thời lượng</strong>{{ $course['duration'] }}</span></li>
                            <li>@include('site.partials.icon', ['name' => 'calendar'])<span><strong>Lịch học</strong>{{ $course['sessions'] }}</span></li>
                            <li>@include('site.partials.icon', ['name' => 'users'])<span><strong>Sĩ số</strong>{{ $course['class_size'] }}</span></li>
                            <li>@include('site.partials.icon', ['name' => 'award'])<span><strong>Học phí</strong>{{ $course['tuition'] }}</span></li>
                        </ul>
                    </div>

                    @include('site.partials.consult-form', [
                        'heading' => 'Đăng ký khoá ' . $course['name'],
                        'note' => 'Trung tâm sẽ liên hệ xếp lịch kiểm tra đầu vào và tư vấn lớp phù hợp.',
                        'selectedCourse' => $course['name'],
                    ])
                </div>
            </div>
        </div>
    </section>

    @if($related->isNotEmpty())
        <section class="section section--paper">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">Gợi ý</span>
                    <h2>Khoá học liên quan</h2>
                </div>
                <div class="grid grid--3">
                    @foreach($related as $item)
                        @include('site.partials.course-card', ['course' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
