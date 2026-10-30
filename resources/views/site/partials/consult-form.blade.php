{{-- Form đăng ký nhận tư vấn lộ trình. --}}
@php
    $heading = $heading ?? 'Đăng ký tư vấn miễn phí';
    $note = $note ?? 'Điền thông tin, trung tâm sẽ liên hệ trong vòng 24 giờ làm việc.';
    $options = array_merge(
        array_column(config('center.courses'), 'name'),
        config('center.consult_topics'),
    );

    $contact = config('center.contact');

    // Bản trang tĩnh không có PHP để nhận form. Nếu trung tâm đã khai báo một
    // dịch vụ nhận form miễn phí thì gửi sang đó, nếu chưa thì thay form bằng
    // các kênh liên hệ trực tiếp.
    $endpoint = trim((string) ($contact['form_endpoint'] ?? ''));
    $formKey = trim((string) ($contact['form_key'] ?? ''));
    $external = $endpoint !== '';
    $action = $external ? $endpoint : (config('center.static_build', false) ? null : route('site.consult'));
@endphp

<div class="lead-card" id="dang-ky">
    <h3>{{ $heading }}</h3>
    <p class="lead-card__note">{{ $note }}</p>

    @if(session('consult_success'))
        <div class="alert alert--success" role="status">{{ session('consult_success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert--error" role="alert">Vui lòng kiểm tra lại thông tin bên dưới.</div>
    @endif

    @if($action)
    <form method="POST" action="{{ $action }}" novalidate>
        @if($external)
            {{-- Dịch vụ nhận form bên ngoài: không có token CSRF của website. --}}
            @if($formKey !== '')
                <input type="hidden" name="access_key" value="{{ $formKey }}">
            @endif
            <input type="hidden" name="subject" value="Đăng ký tư vấn từ website {{ config('center.brand.name') }}">
            <input type="hidden" name="_subject" value="Đăng ký tư vấn từ website {{ config('center.brand.name') }}">
        @else
            @csrf
        @endif

        <div class="field">
            <label for="cf-name">Họ và tên <span class="req">*</span></label>
            <input id="cf-name" type="text" name="name" value="{{ old('name') }}" required
                   class="{{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nguyễn Văn A">
            @error('name')<span class="error">{{ $message }}</span>@enderror
        </div>

        <div class="field-row">
            <div class="field">
                <label for="cf-phone">Số điện thoại <span class="req">*</span></label>
                <input id="cf-phone" type="tel" name="phone" value="{{ old('phone') }}" required
                       class="{{ $errors->has('phone') ? 'is-invalid' : '' }}" placeholder="09xx xxx xxx">
                @error('phone')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="cf-email">Email</label>
                <input id="cf-email" type="email" name="email" value="{{ old('email') }}"
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="email@example.com">
                @error('email')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field">
            <label for="cf-course">Khoá học quan tâm</label>
            <select id="cf-course" name="course" class="{{ $errors->has('course') ? 'is-invalid' : '' }}">
                @foreach($options as $option)
                    <option value="{{ $option }}" @selected(old('course', $selectedCourse ?? '') === $option)>{{ $option }}</option>
                @endforeach
            </select>
            @error('course')<span class="error">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label for="cf-note">Trình độ hiện tại / mục tiêu</label>
            <textarea id="cf-note" name="note" rows="3"
                      class="{{ $errors->has('note') ? 'is-invalid' : '' }}"
                      placeholder="Ví dụ: đang ở mức 5.0, cần 6.5 trước tháng 12.">{{ old('note') }}</textarea>
            @error('note')<span class="error">{{ $message }}</span>@enderror
        </div>

        {{-- Bẫy spam: người dùng thật không nhìn thấy ô này. --}}
        <div style="position:absolute;left:-9999px" aria-hidden="true">
            <label for="cf-website">Website</label>
            <input id="cf-website" type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <button type="submit" class="btn btn--primary btn--block">Gửi thông tin đăng ký</button>
        <p class="form-note">Thông tin của bạn chỉ dùng để liên hệ tư vấn và không chia sẻ cho bên thứ ba.</p>
    </form>
    @else
        {{-- Trang tĩnh chưa khai báo dịch vụ nhận form: mời liên hệ trực tiếp. --}}
        <div class="lead-card__direct">
            <a class="btn btn--primary btn--block" href="{{ $contact['hotline_href'] }}">☎ Gọi {{ $contact['hotline'] }}</a>
            <a class="btn btn--ghost btn--block" href="{{ $contact['zalo'] }}" target="_blank" rel="noopener">Nhắn tin Zalo</a>
            <a class="btn btn--ghost btn--block" href="{{ $contact['messenger'] }}" target="_blank" rel="noopener">Nhắn tin Messenger</a>
            <a class="btn btn--ghost btn--block" href="mailto:{{ $contact['email'] }}">✉ {{ $contact['email'] }}</a>
            <p class="form-note">Trung tâm phản hồi trong giờ làm việc: {{ $contact['working_hours'] }}.</p>
        </div>
    @endif
</div>
