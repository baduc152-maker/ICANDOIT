# ICANDOIT – Web điểm danh & kế toán trung tâm ngoại ngữ

Ứng dụng web cho trung tâm ngoại ngữ **ICANDOIT ACADEMIC ENGLISH** gồm hai phân hệ:

1. **Điểm danh từ xa** — nhân viên check-in / check-out bằng một chạm, quản trị
   viên theo dõi bảng điểm danh toàn trung tâm.
2. **Kế toán** — quản lý học viên, khóa học, ghi danh & học phí, sổ quỹ thu/chi
   và báo cáo tài chính.

Ứng dụng được thiết kế để **kết nối phần mềm ICANDOIT** thông qua một lớp tích
hợp (adapter) — chạy độc lập ngay cả khi ICANDOIT chưa cung cấp API.

## Tính năng

### Điểm danh
- **Đăng nhập tài khoản** (nhân viên / quản trị viên).
- **Check-in / Check-out một chạm** với đồng hồ thời gian thực, tự đánh giá
  đúng giờ / đi muộn theo giờ vào chuẩn cấu hình.
- **Lịch sử điểm danh** cá nhân (phân trang).
- **Trang quản trị**: xem bảng điểm danh theo ngày, thống kê (có mặt / đi muộn /
  trạng thái đồng bộ), **xuất CSV** (UTF-8, mở được bằng Excel tiếng Việt), và
  nút **đồng bộ lại** từng bản ghi.
- **Lớp tích hợp ICANDOIT** dạng adapter, sẵn sàng bật khi có API thật.

### Kế toán (chỉ quản trị viên)
- **Bảng điều khiển kế toán**: tổng thu / chi / lợi nhuận tháng, biểu đồ thu–chi
  12 tháng, công nợ học phí và các phiếu gần đây.
- **Học viên**: hồ sơ học viên, theo dõi học phí phải thu / đã thu / còn nợ.
- **Khóa học**: danh mục khóa học kèm mức học phí chuẩn và số buổi.
- **Ghi danh**: gắn học viên với khóa học (có giảm giá), thu học phí trực tiếp —
  hệ thống tự lập **phiếu thu** và cập nhật công nợ.
- **Sổ quỹ thu / chi**: lập phiếu thu (PT-…) / phiếu chi (PC-…) với số phiếu tự
  sinh theo ngày, phân loại theo **danh mục**, lọc theo khoảng thời gian.
- **Báo cáo tài chính**: tổng hợp thu / chi theo danh mục trong kỳ, **xuất CSV**.
- Định dạng tiền tệ đồng Việt Nam qua chỉ thị Blade `@vnd`.

Nghiệp vụ kế toán nằm gọn trong `App\Services\AccountingService` (sinh số phiếu,
ghi nhận thu/chi, tổng hợp báo cáo).

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
  Http/Controllers/        Auth, Dashboard, Attendance
    Admin/                 Attendance + Kế toán (Accounting, Transaction,
                           Student, Course, Enrollment, TransactionCategory)
  Http/Middleware/         EnsureUserIsAdmin
  Models/                  User, Attendance, Student, Course, Enrollment,
                           Transaction, TransactionCategory
  Services/
    AttendanceService.php  Nghiệp vụ check-in/out + đồng bộ
    AccountingService.php  Nghiệp vụ thu/chi, sinh số phiếu, báo cáo
    Icandoit/              Lớp tích hợp ICANDOIT (adapter)
  Support/Money.php        Định dạng tiền tệ VND
resources/views/
  admin/accounting|transactions|students|courses|enrollments|categories
  partials/accounting-nav  Thanh điều hướng phân hệ kế toán
routes/web.php             Định tuyến
database/                  Migrations + seeder (DatabaseSeeder, AccountingSeeder)
tests/Feature/             Kiểm thử luồng điểm danh & kế toán
```
