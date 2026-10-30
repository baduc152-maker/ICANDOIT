@extends('site.layouts.app')

@section('title', 'Đội ngũ giảng viên')
@section('meta_description', 'Đội ngũ giảng viên ICANDOIT: trình độ IELTS 8.0+, chứng chỉ sư phạm quốc tế và kinh nghiệm giảng dạy tiếng Anh học thuật.')

@section('content')

    <section class="page-hero">
        <div class="container">
            <div class="breadcrumb"><a href="{{ route('site.home') }}">Trang chủ</a> / Giảng viên</div>
            <h1>Đội ngũ giảng viên</h1>
            <p>Người trực tiếp đứng lớp quyết định chất lượng buổi học. Mọi giảng viên tại ICANDOIT đều
                trải qua vòng tuyển chọn về chuyên môn, kỹ năng sư phạm và cách chữa bài học thuật.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="grid grid--2">
                @foreach($teachers as $teacher)
                    <div class="card reveal" style="display:flex;gap:20px;align-items:flex-start">
                        <span class="avatar" style="width:72px;height:72px;font-size:1.4rem;background:linear-gradient(140deg,var(--navy-700),var(--navy-600))">{{ mb_substr(last(explode(' ', $teacher['name'])), 0, 1) }}</span>
                        <div>
                            <h3 style="margin-bottom:2px">{{ $teacher['name'] }}</h3>
                            <div class="teacher-card__role">{{ $teacher['role'] }}</div>
                            <div class="teacher-card__cred">{{ $teacher['credentials'] }}</div>
                            <p style="margin-top:12px">{{ $teacher['bio'] }}</p>
                            <span class="teacher-card__focus">Phụ trách: {{ $teacher['focus'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--paper">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Tiêu chuẩn giảng viên</span>
                <h2>Bốn yêu cầu bắt buộc trước khi đứng lớp</h2>
            </div>

            <div class="grid grid--4">
                <div class="card reveal">
                    <div class="icon-badge">@include('site.partials.icon', ['name' => 'award'])</div>
                    <h3>Trình độ chuyên môn</h3>
                    <p>IELTS 7.5+ (đa số 8.0+) hoặc bằng cấp chuyên ngành ngôn ngữ Anh tương đương.</p>
                </div>
                <div class="card reveal">
                    <div class="icon-badge">@include('site.partials.icon', ['name' => 'graduation'])</div>
                    <h3>Chứng chỉ sư phạm</h3>
                    <p>TESOL, CELTA hoặc TKT — bảo đảm phương pháp giảng dạy có nền tảng khoa học.</p>
                </div>
                <div class="card reveal">
                    <div class="icon-badge">@include('site.partials.icon', ['name' => 'book'])</div>
                    <h3>Đào tạo nội bộ</h3>
                    <p>Hoàn thành khoá chuẩn hoá về giáo trình và tiêu chí chữa bài của trung tâm.</p>
                </div>
                <div class="card reveal">
                    <div class="icon-badge">@include('site.partials.icon', ['name' => 'chart'])</div>
                    <h3>Đánh giá định kỳ</h3>
                    <p>Dự giờ và khảo sát học viên mỗi khoá để duy trì chất lượng giảng dạy.</p>
                </div>
            </div>
        </div>
    </section>

@endsection
