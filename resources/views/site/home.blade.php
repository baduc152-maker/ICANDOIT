@extends('site.layouts.app')

@section('title', 'Trung tâm tiếng Anh học thuật')
@section('meta_description', 'ICANDOIT ACADEMIC ENGLISH – trung tâm đào tạo IELTS và tiếng Anh học thuật với lộ trình cá nhân hoá, lớp nhỏ 8-12 học viên và cam kết đầu ra bằng văn bản.')

@section('content')

    {{-- ------------------------------------------------------------- Hero --}}
    <section class="hero">
        <div class="container">
            <div class="hero__grid">
                <div class="hero__text">
                    <span class="hero__badge"><b>Mới</b> Khai giảng lớp IELTS 7.0+ tháng này</span>
                    <h1>Tiếng Anh học thuật <em>vững nền tảng</em>, tự tin chạm mốc điểm mục tiêu</h1>
                    <p>{{ config('center.brand.description') }}</p>

                    <ul class="hero__points">
                        <li>Kiểm tra đầu vào 4 kỹ năng miễn phí</li>
                        <li>Lớp nhỏ 8 – 12 học viên</li>
                        <li>Chữa Writing – Speaking chi tiết</li>
                        <li>Cam kết đầu ra bằng văn bản</li>
                    </ul>

                    <div class="hero__cta">
                        <a class="btn btn--primary" href="#dang-ky">Nhận tư vấn lộ trình</a>
                        <a class="btn btn--outline-light" href="{{ route('site.courses') }}">Xem khoá học</a>
                    </div>
                </div>

                <div>
                    @include('site.partials.consult-form', [
                        'heading' => 'Nhận lộ trình học miễn phí',
                        'note' => 'Để lại thông tin, học thuật trưởng sẽ gọi tư vấn trong 24 giờ làm việc.',
                    ])
                </div>
            </div>
        </div>
    </section>

    {{-- ----------------------------------------------------- Con số nổi bật --}}
    <section class="section section--tight">
        <div class="container">
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

    {{-- --------------------------------------------------------- Giá trị --}}
    <section class="section section--paper">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Vì sao chọn ICANDOIT</span>
                <h2>Chương trình học thuật bài bản, đo lường được từng tuần</h2>
                <p>Chúng tôi không dạy mẹo làm bài. Học viên xây nền tảng ngôn ngữ thật, sau đó áp dụng
                    chiến lược thi để đạt điểm số tương xứng với năng lực.</p>
            </div>

            <div class="grid grid--3">
                @foreach(config('center.values') as $value)
                    <div class="card reveal">
                        <div class="icon-badge">@include('site.partials.icon', ['name' => $value['icon']])</div>
                        <h3>{{ $value['title'] }}</h3>
                        <p>{{ $value['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ------------------------------------------------------- Khoá học --}}
    <section class="section">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Khoá học nổi bật</span>
                <h2>Chọn lộ trình đúng với trình độ hiện tại của bạn</h2>
                <p>Mỗi khoá học đều bắt đầu bằng bài kiểm tra xếp lớp để đảm bảo học viên ngồi đúng lớp,
                    đúng tốc độ.</p>
            </div>

            <div class="grid grid--3">
                @foreach($featuredCourses as $course)
                    @include('site.partials.course-card', ['course' => $course])
                @endforeach
            </div>

            <p style="text-align:center;margin-top:36px">
                <a class="btn btn--navy" href="{{ route('site.courses') }}">
                    Xem toàn bộ {{ count(config('center.courses')) }} khoá học
                    @include('site.partials.icon', ['name' => 'arrow-right'])
                </a>
            </p>
        </div>
    </section>

    {{-- -------------------------------------------------------- Lộ trình --}}
    <section class="section section--paper">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Quy trình đào tạo</span>
                <h2>Năm bước đồng hành cùng học viên</h2>
                <p>Từ buổi kiểm tra đầu tiên đến khi cầm kết quả thi, mỗi bước đều có tiêu chí rõ ràng.</p>
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

    {{-- ------------------------------------------------------ Cảm nhận --}}
    <section class="section">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Học viên nói gì</span>
                <h2>Kết quả đến từ sự kiên trì và lộ trình đúng</h2>
            </div>

            <div class="grid grid--3">
                @foreach(config('center.testimonials') as $item)
                    <blockquote class="quote-card reveal" style="margin:0">
                        <div class="quote-card__mark">&ldquo;</div>
                        <p>{{ $item['quote'] }}</p>
                        <footer class="quote-card__who">
                            <span class="avatar">{{ mb_substr(last(explode(' ', $item['name'])), 0, 1) }}</span>
                            <span>
                                <strong>{{ $item['name'] }}</strong>
                                <span>{{ $item['detail'] }}</span>
                            </span>
                        </footer>
                    </blockquote>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ----------------------------------------------------- Giảng viên --}}
    <section class="section section--navy">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Đội ngũ</span>
                <h2>Giảng viên trực tiếp đứng lớp</h2>
                <p>100% giảng viên đạt IELTS 7.5+ và trải qua chương trình đào tạo nội bộ về phương pháp
                    chữa bài học thuật.</p>
            </div>

            <div class="grid grid--4">
                @foreach(config('center.teachers') as $teacher)
                    <div class="teacher-card reveal">
                        <span class="avatar">{{ mb_substr(last(explode(' ', $teacher['name'])), 0, 1) }}</span>
                        <h3>{{ $teacher['name'] }}</h3>
                        <div class="teacher-card__role">{{ $teacher['role'] }}</div>
                        <div class="teacher-card__cred">{{ $teacher['credentials'] }}</div>
                        <span class="teacher-card__focus">{{ $teacher['focus'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ------------------------------------------------ Lịch khai giảng --}}
    <section class="section">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Lịch khai giảng</span>
                <h2>Các lớp sắp mở</h2>
                <p>Sĩ số giới hạn, học viên đăng ký sớm được ưu tiên xếp lớp đúng khung giờ mong muốn.</p>
            </div>

            <div class="table-wrap reveal">
                <table class="data">
                    <thead>
                    <tr>
                        <th>Khoá học</th>
                        <th>Mã lớp</th>
                        <th>Khai giảng</th>
                        <th>Lịch học</th>
                        <th>Chỗ trống</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(array_slice(config('center.schedule'), 0, 4) as $row)
                        <tr>
                            <td><strong>{{ $row['course'] }}</strong></td>
                            <td>{{ $row['code'] }}</td>
                            <td>{{ $row['open_at'] }}</td>
                            <td>{{ $row['time'] }}</td>
                            <td><span class="seat-pill">{{ $row['seats'] }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <p style="text-align:center;margin-top:30px">
                <a class="btn btn--ghost" href="{{ route('site.schedule') }}">Xem đầy đủ lịch khai giảng</a>
            </p>
        </div>
    </section>

    {{-- -------------------------------------------------------- Cẩm nang --}}
    <section class="section section--paper">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Cẩm nang học thuật</span>
                <h2>Kiến thức và kinh nghiệm từ phòng học thuật</h2>
            </div>

            <div class="grid grid--3">
                @foreach(config('center.posts') as $post)
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

    {{-- ------------------------------------------------------------ FAQ --}}
    <section class="section">
        <div class="container container--narrow">
            <div class="section-head">
                <span class="eyebrow">Giải đáp</span>
                <h2>Câu hỏi thường gặp</h2>
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
