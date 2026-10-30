@extends('site.layouts.app')

@section('title', 'Giới thiệu trung tâm')
@section('meta_description', 'Câu chuyện, triết lý đào tạo và cam kết chất lượng của ICANDOIT ACADEMIC ENGLISH – trung tâm tiếng Anh học thuật.')

@section('content')

    <section class="page-hero">
        <div class="container">
            <div class="breadcrumb"><a href="{{ route('site.home') }}">Trang chủ</a> / Giới thiệu</div>
            <h1>Trung tâm tiếng Anh học thuật ICANDOIT</h1>
            <p>{{ config('center.brand.slogan') }} — đồng hành cùng học viên bằng chương trình bài bản,
                giảng viên tận tâm và hệ thống đo lường tiến độ minh bạch.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="split">
                <div>
                    <span class="eyebrow">Câu chuyện của chúng tôi</span>
                    <h2>Từ một lớp học nhỏ đến cộng đồng học thuật</h2>
                    <p>ICANDOIT ACADEMIC ENGLISH được thành lập năm {{ config('center.brand.founded') }}
                        với một lớp học duy nhất và niềm tin rằng bất kỳ ai cũng có thể chinh phục tiếng Anh
                        học thuật nếu được hướng dẫn đúng phương pháp.</p>
                    <p>Sau nhiều năm, trung tâm đã đồng hành cùng hàng nghìn học viên từ mất gốc đến band
                        điểm 7.0+, từ học sinh THCS đến nghiên cứu sinh cần viết luận bằng tiếng Anh. Điều
                        không thay đổi là cách chúng tôi làm việc: lớp nhỏ, chữa bài kỹ và theo sát tiến độ
                        của từng người học.</p>
                    <p>Cái tên <strong>I CAN DO IT</strong> là lời nhắc mỗi ngày cho học viên và cho chính
                        đội ngũ giảng viên: kết quả đến từ nỗ lực bền bỉ, không phải từ những lối tắt.</p>
                </div>

                <div class="panel">
                    <h3>Triết lý đào tạo</h3>
                    <ul class="check-list">
                        <li><strong>Nền tảng trước, kỹ thuật sau.</strong> Học viên phải hiểu ngôn ngữ trước khi học chiến lược thi.</li>
                        <li><strong>Phản hồi là trung tâm.</strong> Mỗi bài viết, mỗi bản ghi âm đều được nhận xét cụ thể.</li>
                        <li><strong>Đo lường được.</strong> Tiến độ thể hiện bằng điểm số và tiêu chí, không bằng cảm tính.</li>
                        <li><strong>Trung thực với học viên.</strong> Mục tiêu và thời gian được tư vấn dựa trên năng lực thật.</li>
                        <li><strong>Học thuật đi cùng thái độ.</strong> Kỷ luật tự học là kỹ năng theo học viên suốt đời.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--paper">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Cam kết</span>
                <h2>Những gì trung tâm bảo đảm với học viên</h2>
            </div>

            <div class="grid grid--3">
                @foreach(config('center.values') as $value)
                    <div class="card reveal">
                        <div class="icon-badge icon-badge--red">@include('site.partials.icon', ['name' => $value['icon']])</div>
                        <h3>{{ $value['title'] }}</h3>
                        <p>{{ $value['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--navy">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Con số</span>
                <h2>Kết quả sau {{ (int) date('Y') - (int) config('center.brand.founded') }} năm đào tạo</h2>
            </div>
            <div class="stats">
                @foreach(config('center.stats') as $stat)
                    <div class="stat reveal">
                        <div class="stat__value">{{ $stat['value'] }}</div>
                        <div class="stat__label">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Quy trình</span>
                <h2>Học viên được đồng hành như thế nào</h2>
            </div>
            <div class="roadmap">
                @foreach(config('center.roadmap') as $step)
                    <div class="roadmap__item reveal">
                        <span class="roadmap__num">{{ $step['step'] }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
