# ICANDOIT – Web điểm danh từ xa

Ứng dụng web giúp nhân viên **điểm danh từ xa** (check-in / check-out) bằng cách
đăng nhập rồi bấm nút, và quản trị viên theo dõi bảng điểm danh toàn công ty.
Ứng dụng được thiết kế để **kết nối phần mềm ICANDOIT** thông qua một lớp tích
hợp (adapter) — chạy độc lập ngay cả khi ICANDOIT chưa cung cấp API.

## Tính năng

- **Đăng nhập tài khoản** (nhân viên / quản trị viên).
- **Check-in / Check-out một chạm** với đồng hồ thời gian thực, tự đánh giá
  đúng giờ / đi muộn theo giờ vào chuẩn cấu hình.
- **Lịch sử điểm danh** cá nhân (phân trang).
- **Trang quản trị**: xem bảng điểm danh theo ngày, thống kê (có mặt / đi muộn /
  trạng thái đồng bộ), **xuất CSV** (UTF-8, mở được bằng Excel tiếng Việt), và
  nút **đồng bộ lại** từng bản ghi.
- **Lớp tích hợp ICANDOIT** dạng adapter, sẵn sàng bật khi có API thật.

## Công nghệ

- PHP 8.2+ / **Laravel 13**
- SQLite (mặc định, đổi được sang MySQL/PostgreSQL trong `.env`)
- Tailwind CSS (qua CDN, không cần bước build)

## Cài đặt & chạy

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Mở http://127.0.0.1:8000

### Tài khoản mẫu (sau khi seed)

| Vai trò      | Email                  | Mật khẩu   |
|--------------|------------------------|------------|
| Quản trị viên | `admin@icandoit.test`  | `password` |
| Nhân viên     | `an@icandoit.test`     | `password` |
| Nhân viên     | `binh@icandoit.test`   | `password` |
| Nhân viên     | `cuong@icandoit.test`  | `password` |

## Kết nối phần mềm ICANDOIT

Hiện ICANDOIT **chưa có API**, nên ứng dụng dùng driver `null`: vẫn ghi nhận
điểm danh đầy đủ ở local và ghi log payload sẽ gửi đi. Khi ICANDOIT công bố API:

1. Khai báo trong `.env`:
   ```dotenv
   ICANDOIT_ENABLED=true
   ICANDOIT_DRIVER=http
   ICANDOIT_BASE_URL=https://api.icandoit.example
   ICANDOIT_API_KEY=xxxxx
   ICANDOIT_ATTENDANCE_ENDPOINT=/api/attendance
   ```
2. Đối chiếu định dạng request/response trong
   `app/Services/Icandoit/HttpIcandoitConnector.php` và payload trong
   `app/Services/Icandoit/AttendancePayload.php` với tài liệu API thực tế.

Toàn bộ controller/model **không phải sửa** — chỉ thay đổi ở lớp adapter.

### Kiến trúc tích hợp

```
AttendanceService  ──>  IcandoitConnector (interface)
                              ├─ NullIcandoitConnector   (mặc định: ghi log)
                              └─ HttpIcandoitConnector   (gọi REST API ICANDOIT)
```

Driver được chọn tại `App\Providers\AppServiceProvider` dựa theo
`config/icandoit.php`.

## Cấu hình chấm công

| Biến môi trường        | Ý nghĩa                                   | Mặc định          |
|------------------------|-------------------------------------------|-------------------|
| `WORK_START_TIME`      | Giờ vào chuẩn (HH:MM), sau mốc này là muộn | `08:30`           |
| `ATTENDANCE_TIMEZONE`  | Múi giờ chấm công                          | `Asia/Ho_Chi_Minh`|

## Kiểm thử

```bash
php artisan test
```

## Cấu trúc chính

```
app/
  Http/Controllers/        Auth, Dashboard, Attendance, Admin
  Http/Middleware/         EnsureUserIsAdmin
  Models/                  User, Attendance
  Services/
    AttendanceService.php  Nghiệp vụ check-in/out + đồng bộ
    Icandoit/              Lớp tích hợp ICANDOIT (adapter)
resources/views/           Giao diện Blade (Tailwind)
routes/web.php             Định tuyến
database/                  Migrations + seeder
tests/Feature/             Kiểm thử luồng điểm danh
```
