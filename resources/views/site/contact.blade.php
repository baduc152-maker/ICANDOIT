@extends('site.layouts.app')

@section('title', 'Liên hệ & đăng ký tư vấn')
@section('meta_description', 'Liên hệ ICANDOIT ACADEMIC ENGLISH để đăng ký kiểm tra trình độ miễn phí và nhận tư vấn lộ trình học tiếng Anh học thuật.')

@section('content')

    @php($contact = config('center.contact'))

    <section class="page-hero">
        <div class="container">
            <div class="breadcrumb"><a href="{{ route('site.home') }}">Trang chủ</a> / Liên hệ</div>
            <h1>Liên hệ &amp; đăng ký tư vấn</h1>
            <p>Đội ngũ tư vấn học thuật sẵn sàng hỗ trợ bạn chọn lộ trình phù hợp — hoàn toàn miễn phí và
                không có ràng buộc đăng ký.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="split" style="align-items:flex-start">
                <div>
                    <span class="eyebrow">Thông tin liên hệ</span>
                    <h2>Ghé thăm hoặc gọi cho trung tâm</h2>

                    <ul class="info-list" style="margin:26px 0 34px">
                        <li>@include('site.partials.icon', ['name' => 'location'])
                            <span><strong>Địa chỉ</strong>{{ $contact['address'] }}</span></li>
                        <li>@include('site.partials.icon', ['name' => 'phone'])
                            <span><strong>Hotline</strong><a href="{{ $contact['hotline_href'] }}">{{ $contact['hotline'] }}</a></span></li>
                        <li>@include('site.partials.icon', ['name' => 'mail'])
                            <span><strong>Email</strong><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></span></li>
                        <li>@include('site.partials.icon', ['name' => 'clock'])
                            <span><strong>Giờ làm việc</strong>{{ $contact['working_hours'] }}</span></li>
                        <li>@include('site.partials.icon', ['name' => 'chat'])
                            <span><strong>Kênh trực tuyến</strong>
                                <a href="{{ $contact['facebook'] }}" target="_blank" rel="noopener">Facebook</a> ·
                                <a href="{{ $contact['zalo'] }}" target="_blank" rel="noopener">Zalo</a> ·
                                <a href="{{ $contact['youtube'] }}" target="_blank" rel="noopener">YouTube</a>
                            </span></li>
                    </ul>

                    <div class="panel" style="padding:0;overflow:hidden">
                        <iframe
                            title="Bản đồ tới trung tâm"
                            src="https://www.google.com/maps?q={{ urlencode($contact['map_query'] . ' ' . $contact['address']) }}&output=embed"
                            style="width:100%;height:320px;border:0;display:block"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="sticky-side">
                    @include('site.partials.consult-form', [
                        'heading' => 'Đăng ký tư vấn miễn phí',
                        'note' => 'Điền thông tin bên dưới, trung tâm sẽ liên hệ trong vòng 24 giờ làm việc.',
                    ])
                </div>
            </div>
        </div>
    </section>

    <section class="section section--paper">
        <div class="container container--narrow">
            <div class="section-head">
                <span class="eyebrow">Trước khi liên hệ</span>
                <h2>Có thể bạn đang thắc mắc</h2>
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
