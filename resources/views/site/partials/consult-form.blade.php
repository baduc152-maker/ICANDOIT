{{-- Form đăng ký nhận tư vấn lộ trình. --}}
@php
    $heading = $heading ?? 'Đăng ký tư vấn miễn phí';
    $note = $note ?? 'Điền thông tin, trung tâm sẽ liên hệ trong vòng 24 giờ làm việc.';
    $options = array_merge(
        array_column(config('center.courses'), 'name'),
        config('center.consult_topics'),
    );
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

    <form method="POST" action="{{ route('site.consult') }}" novalidate>
        @csrf

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
</div>
