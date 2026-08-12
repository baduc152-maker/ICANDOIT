@extends('site.layouts.app')

@section('title', 'Lịch khai giảng')
@section('meta_description', 'Lịch khai giảng các lớp IELTS, Pre-IELTS và Academic Writing tại ICANDOIT ACADEMIC ENGLISH.')

@section('content')

    <section class="page-hero">
        <div class="container">
            <div class="breadcrumb"><a href="{{ route('site.home') }}">Trang chủ</a> / Lịch khai giảng</div>
            <h1>Lịch khai giảng</h1>
            <p>Sĩ số mỗi lớp được giới hạn để bảo đảm thời lượng chữa bài cho từng học viên. Vui lòng đăng
                ký sớm để giữ chỗ ở khung giờ mong muốn.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="split" style="align-items:flex-start">
                <div>
                    <div class="table-wrap">
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
                            @foreach($schedule as $row)
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

                    <div class="panel" style="margin-top:26px">
                        <h3>Lưu ý khi đăng ký</h3>
                        <ul class="check-list">
                            <li>Học viên mới cần làm bài kiểm tra xếp lớp trước ngày khai giảng ít nhất 3 ngày.</li>
                            <li>Lớp mở khi đủ số lượng tối thiểu; trường hợp hoãn, trung tâm báo trước 48 giờ.</li>
                            <li>Học viên có thể học thử một buổi trước khi quyết định nhập học.</li>
                            <li>Trung tâm nhận đăng ký giữ chỗ qua hotline, Zalo hoặc biểu mẫu bên cạnh.</li>
                        </ul>
                    </div>
                </div>

                <div class="sticky-side">
                    @include('site.partials.consult-form', [
                        'heading' => 'Giữ chỗ lớp khai giảng',
                        'note' => 'Chọn khoá học quan tâm, trung tâm sẽ xác nhận chỗ trống và khung giờ.',
                    ])
                </div>
            </div>
        </div>
    </section>

@endsection
