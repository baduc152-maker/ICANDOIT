# Hướng dẫn chạy ICANDOIT trên laptop (chạy độc lập)

Phần mềm **ICANDOIT – Điểm danh & Kế toán** chạy hoàn toàn trên máy của bạn,
**không cần Internet** (ngoại trừ lần đầu tải khung giao diện Tailwind), không
cần cài MySQL hay Node.js.

- Cơ sở dữ liệu dùng **SQLite** (một file, đã kèm sẵn cách tạo tự động).
- Thư viện PHP (`vendor/`) **đã đóng gói sẵn** — không cần chạy Composer.
- Giao diện dùng **Tailwind qua CDN** — không cần build.

> Bạn chỉ cần cài **PHP 8.3 trở lên**. Vậy là đủ.

---

## 1. Cài PHP (chỉ làm một lần)

### Windows
1. Tải PHP bản **Thread Safe x64** mới nhất tại <https://windows.php.net/download/>
   (hoặc cài nhanh bằng [Laragon](https://laragon.org/) / [XAMPP](https://www.apachefriends.org/)).
2. Giải nén vào ví dụ `C:\php`, rồi thêm `C:\php` vào biến môi trường **PATH**.
3. Mở `php.ini` (copy từ `php.ini-development`) và bỏ dấu `;` ở các dòng:
   `extension=pdo_sqlite`, `extension=sqlite3`, `extension=mbstring`,
   `extension=fileinfo`, `extension=openssl`.
4. Mở **Command Prompt** mới, gõ `php -v` để kiểm tra.

### macOS
```bash
brew install php
php -v
```

### Linux (Ubuntu/Debian)
```bash
sudo apt update
sudo apt install -y php-cli php-sqlite3 php-mbstring php-xml
php -v
```

---

## 2. Chạy phần mềm

1. Giải nén thư mục `ICANDOIT` ra ổ đĩa (ví dụ Desktop).
2. Khởi động:
   - **Windows:** nháy đúp **`start.bat`**
   - **macOS / Linux:** mở Terminal trong thư mục rồi chạy:
     ```bash
     bash start.sh
     ```
3. Lần đầu chạy, phần mềm tự tạo cấu hình, cơ sở dữ liệu và **dữ liệu mẫu**.
4. Mở trình duyệt vào: <http://127.0.0.1:8000>

### Tài khoản đăng nhập mẫu

| Vai trò       | Email                 | Mật khẩu   |
|---------------|-----------------------|------------|
| Quản trị viên | `admin@icandoit.test` | `password` |
| Nhân viên     | `an@icandoit.test`    | `password` |

> Phân hệ **Kế toán** nằm ở menu **Kế toán** (chỉ quản trị viên thấy).

Để **dừng** phần mềm: nhấn `Ctrl + C` trong cửa sổ dòng lệnh.

---

## 3. Một số thao tác thường dùng

Mở dòng lệnh trong thư mục `ICANDOIT`:

| Việc cần làm                        | Lệnh                                      |
|-------------------------------------|-------------------------------------------|
| Chạy lại phần mềm                   | `php artisan serve`                       |
| Xóa sạch & tạo lại dữ liệu mẫu      | `php artisan migrate:fresh --seed`        |
| Chạy ở cổng khác (vd 9000)          | `php artisan serve --port=9000`           |
| Cho máy khác trong mạng LAN truy cập | `php artisan serve --host=0.0.0.0`        |

Khi chạy với `--host=0.0.0.0`, máy khác trong cùng mạng mở được bằng
`http://<địa-chỉ-IP-laptop>:8000`.

---

## 4. Sao lưu dữ liệu

Toàn bộ dữ liệu nằm trong **một file duy nhất**:

```
database/database.sqlite
```

Chỉ cần **copy file này** ra nơi an toàn là đã sao lưu xong. Muốn phục hồi,
chép đè file đó trở lại.

---

## 5. Khắc phục sự cố

- **`php` không phải là lệnh hợp lệ** → chưa thêm PHP vào PATH (xem mục 1).
- **Lỗi `could not find driver`** → chưa bật `pdo_sqlite`/`sqlite3` trong `php.ini`.
- **Cổng 8000 đang bận** → chạy `php artisan serve --port=9000`.
- **Muốn làm lại từ đầu** → xóa file `.env` và `database/database.sqlite`,
  rồi chạy lại `start`.

---

Phần mềm dùng PHP / Laravel. Mọi nghiệp vụ kế toán nằm trong
`app/Services/AccountingService.php`; chi tiết tính năng xem `README.md`.
