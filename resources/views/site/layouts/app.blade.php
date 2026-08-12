@php
    $brand = config('center.brand');
    $contact = config('center.contact');
    $navItems = [
        ['route' => 'site.home', 'label' => 'Trang chủ'],
        ['route' => 'site.about', 'label' => 'Giới thiệu'],
        ['route' => 'site.courses', 'label' => 'Khoá học'],
        ['route' => 'site.teachers', 'label' => 'Giảng viên'],
        ['route' => 'site.schedule', 'label' => 'Lịch khai giảng'],
        ['route' => 'site.posts', 'label' => 'Cẩm nang'],
        ['route' => 'site.contact', 'label' => 'Liên hệ'],
    ];
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1b3a73">

    <title>@yield('title', $brand['slogan']) · {{ $brand['name'] }}</title>
    <meta name="description" content="@yield('meta_description', $brand['description'])">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $brand['name'] }}">
    <meta property="og:title" content="@yield('title', $brand['slogan']) · {{ $brand['name'] }}">
    <meta property="og:description" content="@yield('meta_description', $brand['description'])">
    <meta property="og:image" content="{{ asset($brand['logo_full']) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ asset($brand['logo_mark']) }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=be-vietnam-pro:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <script>document.documentElement.classList.add('js');</script>
    @stack('head')

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => $brand['name'],
            'alternateName' => $brand['tagline'],
            'description' => $brand['description'],
            'url' => url('/'),
            'logo' => asset($brand['logo_full']),
            'telephone' => $contact['hotline'],
            'email' => $contact['email'],
            'address' => ['@type' => 'PostalAddress', 'streetAddress' => $contact['address'], 'addressCountry' => 'VN'],
            'openingHours' => $contact['working_hours'],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
</head>
<body>
<a class="skip-link" href="#main" style="position:absolute;left:-9999px">Bỏ qua tới nội dung chính</a>

<header class="site-header">
    <div class="topbar">
        <div class="container">
            <ul class="topbar__list" style="list-style:none;margin:0;padding:0">
                <li><a href="{{ $contact['hotline_href'] }}">☎ Hotline: <strong>{{ $contact['hotline'] }}</strong></a></li>
                <li><a href="mailto:{{ $contact['email'] }}">✉ {{ $contact['email'] }}</a></li>
                <li>🕗 {{ $contact['working_hours'] }}</li>
            </ul>
            <ul class="topbar__list" style="list-style:none;margin:0;padding:0">
                <li><a href="{{ route('login') }}">Khu vực nhân sự</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="header-bar">
            <a class="brand" href="{{ route('site.home') }}">
                <img class="brand__mark" src="{{ asset($brand['logo_mark']) }}" alt="Logo {{ $brand['name'] }}" width="50" height="54">
                <span class="brand__text">
                    <span class="brand__name">{{ $brand['name'] }}</span>
                    <span class="brand__tagline">{{ $brand['tagline'] }}</span>
                </span>
            </a>

            <nav class="nav" data-nav aria-label="Điều hướng chính">
                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="{{ request()->routeIs($item['route']) || ($item['route'] === 'site.courses' && request()->routeIs('site.course')) || ($item['route'] === 'site.posts' && request()->routeIs('site.post')) ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="header-actions">
                <a class="btn btn--primary btn--sm btn--nav-cta" href="{{ route('site.contact') }}#dang-ky">Đăng ký tư vấn</a>
                <button class="nav-toggle" type="button" data-nav-toggle aria-label="Mở menu" aria-expanded="false"><span></span></button>
            </div>
        </div>
    </div>
</header>

<main id="main">
    @yield('content')
</main>

@if(! ($hideCta ?? false))
    <section class="section section--tight">
        <div class="container">
            <div class="cta">
                <div>
                    <h2>Bắt đầu bằng một buổi kiểm tra trình độ miễn phí</h2>
                    <p>Để lại thông tin, bộ phận học thuật sẽ liên hệ trong vòng 24 giờ để xếp lịch kiểm tra
                        đầu vào và tư vấn lộ trình phù hợp với mục tiêu của bạn.</p>
                </div>
                <div class="cta__actions">
                    <a class="btn btn--light" href="{{ route('site.contact') }}#dang-ky">Đăng ký ngay</a>
                    <a class="btn btn--outline-light" href="{{ $contact['hotline_href'] }}">Gọi {{ $contact['hotline'] }}</a>
                </div>
            </div>
        </div>
    </section>
@endif

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <img src="{{ asset($brand['logo_light']) }}" alt="" width="50" height="54">
                    <span>
                        <strong>{{ $brand['name'] }}</strong>
                        <span>{{ $brand['tagline'] }}</span>
                    </span>
                </div>
                <p class="footer-desc">{{ $brand['description'] }}</p>
                <div class="social">
                    <a href="{{ $contact['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook">@include('site.partials.icon', ['name' => 'facebook'])</a>
                    <a href="{{ $contact['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube">@include('site.partials.icon', ['name' => 'youtube'])</a>
                    <a href="{{ $contact['messenger'] }}" target="_blank" rel="noopener" aria-label="Messenger">@include('site.partials.icon', ['name' => 'chat'])</a>
                </div>
            </div>

            <div>
                <h4>Khoá học</h4>
                <ul>
                    @foreach(collect(config('center.courses'))->take(5) as $course)
                        <li><a href="{{ route('site.course', $course['slug']) }}">{{ $course['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4>Liên kết</h4>
                <ul>
                    <li><a href="{{ route('site.about') }}">Về trung tâm</a></li>
                    <li><a href="{{ route('site.teachers') }}">Đội ngũ giảng viên</a></li>
                    <li><a href="{{ route('site.schedule') }}">Lịch khai giảng</a></li>
                    <li><a href="{{ route('site.posts') }}">Cẩm nang học thuật</a></li>
                    <li><a href="{{ route('site.contact') }}">Liên hệ &amp; tư vấn</a></li>
                    <li><a href="{{ route('login') }}">Khu vực nhân sự</a></li>
                </ul>
            </div>

            <div>
                <h4>Liên hệ</h4>
                <ul>
                    <li>📍 {{ $contact['address'] }}</li>
                    <li>☎ <a href="{{ $contact['hotline_href'] }}">{{ $contact['hotline'] }}</a></li>
                    <li>✉ <a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></li>
                    <li>🕗 {{ $contact['working_hours'] }}</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} {{ $brand['name'] }}. Bảo lưu mọi quyền.</span>
            <span>Thành lập {{ $brand['founded'] }} · {{ $brand['slogan'] }}</span>
        </div>
    </div>
</footer>

<div class="floating-contact">
    <a class="fc-phone" href="{{ $contact['hotline_href'] }}" aria-label="Gọi hotline">@include('site.partials.icon', ['name' => 'phone'])</a>
    <a class="fc-zalo" href="{{ $contact['zalo'] }}" target="_blank" rel="noopener" aria-label="Chat Zalo">Zalo</a>
</div>

<script src="{{ asset('js/site.js') }}" defer></script>
@stack('scripts')
</body>
</html>
