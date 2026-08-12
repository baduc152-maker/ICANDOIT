# ICANDOIT ACADEMIC ENGLISH

Hệ thống web của trung tâm ngoại ngữ **I CAN DO IT ENGLISH – Tiếng Anh Học Thuật**,
gồm hai phần chạy chung một ứng dụng Laravel:

1. **Website giới thiệu trung tâm** (công khai) — trang chủ, khoá học, giảng viên,
   lịch khai giảng, cẩm nang học thuật, liên hệ và biểu mẫu đăng ký tư vấn.
2. **Khu vực nội bộ** (yêu cầu đăng nhập) — điểm danh từ xa của nhân sự và trang
   quản trị, bao gồm quản lý các yêu cầu tư vấn gửi từ website.

---

## Phần 1 · Website trung tâm

### Trang có sẵn

| Đường dẫn | Nội dung |
|-----------|----------|
| `/` | Trang chủ: hero + form đăng ký, con số nổi bật, giá trị khác biệt, khoá học nổi bật, lộ trình 5 bước, cảm nhận học viên, đội ngũ, lịch khai giảng, cẩm nang, FAQ |
| `/gioi-thieu` | Câu chuyện, triết lý đào tạo, cam kết, thống kê |
| `/khoa-hoc` | Danh mục toàn bộ khoá học |
| `/khoa-hoc/{slug}` | Chi tiết khoá học: đối tượng, đầu ra, chương trình, thông tin lớp, form đăng ký |
| `/giang-vien` | Hồ sơ giảng viên và tiêu chuẩn tuyển chọn |
| `/lich-khai-giang` | Bảng lớp sắp mở kèm form giữ chỗ |
| `/cam-nang`, `/cam-nang/{slug}` | Bài viết học thuật |
| `/lien-he` | Thông tin liên hệ, bản đồ, form tư vấn, FAQ |

### Nhận diện thương hiệu

- Logo được dựng lại dạng vector: `public/images/logo.svg` (đầy đủ chữ) và
  `public/images/logo-mark.svg` (chỉ biểu tượng, dùng cho header/favicon).
- Bảng màu lấy từ logo: xanh navy học thuật `#1b3a73`, đỏ nhấn `#e1232b`, nền trắng.
- Giao diện viết bằng CSS thuần tại `public/css/site.css` (không cần bước build),
  responsive từ điện thoại đến màn hình lớn, kèm hiệu ứng xuất hiện khi cuộn trang.

### Cập nhật nội dung

Toàn bộ nội dung website nằm trong **`config/center.php`** — sửa file này là đủ,
không cần đụng tới giao diện:

| Khoá cấu hình | Nội dung |
|---------------|----------|
| `brand` | Tên, slogan, mô tả, năm thành lập |
| `contact` | Hotline, email, địa chỉ, giờ làm việc, Facebook/YouTube/Zalo |
| `stats`, `values`, `roadmap` | Con số nổi bật, giá trị khác biệt, quy trình đào tạo |
| `courses` | Danh sách khoá học (slug, mục tiêu, thời lượng, đầu ra, chương trình) |
| `teachers`, `testimonials` | Hồ sơ giảng viên, cảm nhận học viên |
| `schedule`, `posts`, `faqs` | Lịch khai giảng, bài viết, câu hỏi thường gặp |

> ⚠️ **Lưu ý quan trọng:** thông tin liên hệ, học phí, hồ sơ giảng viên, cảm nhận
> học viên và lịch khai giảng hiện là **dữ liệu mẫu** để minh hoạ bố cục. Hãy thay
> bằng thông tin thật của trung tâm trước khi đưa website lên môi trường thật.

### Đăng ký tư vấn

- Form đăng ký xuất hiện ở trang chủ, trang chi tiết khoá học, lịch khai giảng và
  trang liên hệ; dữ liệu lưu vào bảng `consultations`.
- Có xác thực dữ liệu, bẫy spam (honeypot) và giới hạn 10 lượt gửi/phút theo IP.
- Quản trị viên xem tại `/admin/consultations`: lọc theo trạng thái
  (Mới / Đã liên hệ / Đã nhập học / Đóng), ghi chú nội bộ và **xuất CSV**.

---

## Phần 2 · Khu vực nội bộ (điểm danh từ xa)

Ứng dụng giúp nhân viên **điểm danh từ xa** (check-in / check-out) bằng cách
đăng nhập rồi bấm nút, và quản trị viên theo dõi bảng điểm danh toàn công ty.
Ứng dụng được thiết kế để **kết nối phần mềm ICANDOIT** thông qua một lớp tích
hợp (adapter) — chạy độc lập ngay cả khi ICANDOIT chưa cung cấp API.

### Tính năng

- **Đăng nhập tài khoản** (nhân viên / quản trị viên).
- **Check-in / Check-out một chạm** với đồng hồ thời gian thực, tự đánh giá
  đúng giờ / đi muộn theo giờ vào chuẩn cấu hình.
- **Lịch sử điểm danh** cá nhân (phân trang).
- **Trang quản trị**: xem bảng điểm danh theo ngày, thống kê (có mặt / đi muộn /
  trạng thái đồng bộ), **xuất CSV** (UTF-8, mở được bằng Excel tiếng Việt), và
  nút **đồng bộ lại** từng bản ghi.
- **Lớp tích hợp ICANDOIT** dạng adapter, sẵn sàng bật khi có API thật.

---

## Công nghệ

- PHP 8.2+ / **Laravel 13**
- SQLite (mặc định, đổi được sang MySQL/PostgreSQL trong `.env`)
- Website trung tâm: CSS thuần (`public/css/site.css`), không cần bước build
- Khu vực nội bộ: Tailwind CSS qua CDN

## Cài đặt & chạy

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Mở http://127.0.0.1:8000 để xem website trung tâm, `/login` để vào khu vực nội bộ.

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
  Models/                  User, Attendance, Consultation
  Services/
    AttendanceService.php  Nghiệp vụ check-in/out + đồng bộ
    Icandoit/              Lớp tích hợp ICANDOIT (adapter)
resources/views/site/      Giao diện website trung tâm (Blade + CSS thuần)
resources/views/           Giao diện khu vực nội bộ (Blade + Tailwind CDN)
public/css/site.css        Hệ thống giao diện website
public/images/logo*.svg    Logo dạng vector
config/center.php          Toàn bộ nội dung website
routes/web.php             Định tuyến
database/                  Migrations + seeder
tests/Feature/             Kiểm thử luồng điểm danh
```
