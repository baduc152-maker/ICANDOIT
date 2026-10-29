# Hướng dẫn đưa website ICANDOIT lên mạng

Website là ứng dụng **Laravel (PHP)** nên nơi chạy phải hỗ trợ PHP 8.3+ và một
cơ sở dữ liệu. Nếu chỉ thuê nơi lưu file tĩnh (GitHub Pages, Cloudflare Pages)
thì phần giới thiệu vẫn chạy, nhưng **mất trang quản trị nội dung và form đăng
ký tư vấn**.

## Chọn cách phù hợp

| Cách | Chi phí | Giữ được trang quản trị? | Phù hợp khi |
|------|---------|--------------------------|-------------|
| **A. Máy sẵn có + Tailscale Funnel** | Miễn phí | Có | Trung tâm đã chạy app bài tập trên máy này, muốn lên mạng ngay |
| **B. Hosting PHP miễn phí** (InfinityFree…) | Miễn phí | Có | Muốn website chạy độc lập, không phụ thuộc máy ở trung tâm |
| **C. VPS giá rẻ** | ~30.000–100.000đ/tháng | Có | Cần chạy 24/7 ổn định, tên miền riêng, nhiều người truy cập |
| **D. Bản tĩnh trên Cloudflare Pages / GitHub Pages** | Miễn phí | Không | Chỉ cần trang giới thiệu, nhận liên hệ qua hotline và Zalo |

---

## Cách A — Chạy trên máy sẵn có, công khai bằng Tailscale Funnel

Máy đang chạy app bài tập (`ducnguyen.tailce49e0.ts.net`) chạy luôn được website.
Tailscale Funnel cấp sẵn địa chỉ HTTPS công khai, không tốn phí tên miền.

```bash
# 1. Lấy mã nguồn về máy
git clone https://github.com/baduc152-maker/ICANDOIT.git
cd ICANDOIT
git checkout claude/icandoit-language-center-website-asx6l9

# 2. Cài đặt
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force          # tạo tài khoản quản trị mẫu

# 3. Sửa .env cho môi trường thật
#    APP_ENV=production
#    APP_DEBUG=false
#    APP_URL=https://ducnguyen.tailce49e0.ts.net

# 4. Chạy website ở cổng 8080
php artisan serve --host=127.0.0.1 --port=8080

# 5. Công khai ra Internet (cửa sổ dòng lệnh khác)
sudo tailscale funnel --bg 8080
```

Sau bước 5, website chạy tại `https://ducnguyen.tailce49e0.ts.net/`, còn app bài
tập vẫn giữ nguyên ở `/baitap/`.

> **Lưu ý:** đặt website ở **đường dẫn gốc** (`tailscale funnel --bg 8080`), đừng
> đặt vào đường dẫn con như `/web`, vì Laravel sinh liên kết theo gốc tên miền.

Muốn website tự chạy lại khi khởi động máy, tạo dịch vụ systemd:

```ini
# /etc/systemd/system/icandoit-web.service
[Unit]
Description=Website ICANDOIT
After=network.target

[Service]
WorkingDirectory=/duong/dan/toi/ICANDOIT
ExecStart=/usr/bin/php artisan serve --host=127.0.0.1 --port=8080
Restart=always
User=ducnguyen

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable --now icandoit-web
```

---

## Cách B — Hosting PHP miễn phí

Nhà cung cấp miễn phí phổ biến còn nhận đăng ký (không cần thẻ ngân hàng):

| Nhà cung cấp | Có sẵn | Tên miền phụ miễn phí |
|--------------|--------|------------------------|
| **InfinityFree** | PHP 8.x, MySQL, SSL miễn phí | `ten-cua-ban.rf.gd`, `.infinityfreeapp.com` |
| **AwardSpace** | PHP, MySQL, 1GB | `ten-cua-ban.atwebpages.com` |
| **ByetHost** | PHP, MySQL | `ten-cua-ban.byethost.com` |

> **Làm từng bước với InfinityFree + tên miền `.rf.gd`:** xem hướng dẫn chi tiết
> trong [HUONG-DAN-TEN-MIEN-RF-GD.md](HUONG-DAN-TEN-MIEN-RF-GD.md).

Các bước tóm tắt:

1. Đăng ký tài khoản (cần xác thực email), tạo một "hosting account" và chọn tên
   miền phụ miễn phí.
2. Trong control panel, tạo **MySQL database**; ghi lại host, tên database, user
   và mật khẩu.
3. Trên máy mình, đóng gói mã nguồn:
   ```bash
   bash scripts/build-release.sh      # tạo release/icandoit-website.zip
   php artisan key:generate --show    # ghi lại chuỗi base64:... để điền APP_KEY
   ```
4. Dùng **File Manager** hoặc FTP tải file zip lên thư mục gốc (`htdocs`) rồi
   giải nén. File `.htaccess` ở thư mục gốc đã có sẵn để chuyển hướng vào `public/`.
5. Tạo file `.env` ngay trong thư mục gốc với nội dung:
   ```dotenv
   APP_NAME="I CAN DO IT ENGLISH"
   APP_ENV=production
   APP_KEY=base64:...        # dán chuỗi ở bước 3
   APP_DEBUG=false
   APP_URL=https://ten-cua-ban.rf.gd

   DB_CONNECTION=mysql
   DB_HOST=sqlxxx.infinityfree.com
   DB_PORT=3306
   DB_DATABASE=if0_xxxxx_icandoit
   DB_USERNAME=if0_xxxxx
   DB_PASSWORD=...

   SESSION_DRIVER=file
   CACHE_STORE=file
   ```
6. Tạo bảng dữ liệu: hosting miễn phí **không có SSH** nên không chạy được
   `php artisan migrate`. Dùng **phpMyAdmin** trong control panel, tab *Import*,
   nhập sẵn file `database/schema/mysql-schema.sql` có trong mã nguồn.
   Sau đó tạo tài khoản quản trị bằng câu lệnh SQL sinh từ:
   ```bash
   php artisan icandoit:admin-sql email@cua-ban "MatKhauManh"
   ```
7. Mở website, đăng nhập `/login` và **đổi ngay mật khẩu tài khoản quản trị**.

> Hạn chế của hosting miễn phí: không có SSH và cron, giới hạn lượt truy cập mỗi
> ngày, tốc độ chậm hơn. Dùng tốt cho giai đoạn đầu; khi trung tâm có nhiều học
> viên truy cập thì nên chuyển sang VPS.

---

## Cách C — VPS giá rẻ (ổn định nhất)

Nếu muốn website chạy 24/7 không phụ thuộc máy ở trung tâm, một VPS nhỏ (1GB RAM)
là đủ. Cài đặt như Cách A nhưng chạy sau Nginx và bật HTTPS bằng Let's Encrypt
(miễn phí). Oracle Cloud có gói **Always Free** (cần thẻ để xác minh).

---

## Cách D — Chỉ đưa bản giới thiệu lên (miễn phí, không có trang quản trị)

Nếu chỉ cần trang giới thiệu công khai, có thể xuất website thành file tĩnh rồi
tải lên Cloudflare Pages, GitHub Pages hoặc Netlify. Khi đó form đăng ký tư vấn
cần thay bằng liên kết Zalo / số điện thoại, và nội dung chỉ sửa được bằng cách
xuất lại.

---

## Tên miền miễn phí

1. **Tên miền quốc gia `.id.vn`** — Nhà nước miễn lệ phí đăng ký và phí duy trì
   cho **công dân Việt Nam từ 18 đến 23 tuổi** (một tên miền mỗi người). Đăng ký
   tại các nhà đăng ký được uỷ quyền (Tenten, iNET, PA Việt Nam, Nhân Hoà…).
   Doanh nghiệp mới thành lập có chính sách tương tự với `.biz.vn`. Điều kiện và
   thời hạn ưu đãi thay đổi theo thông tư của Bộ Tài chính, nên kiểm tra lại
   trên trang của VNNIC trước khi đăng ký.
2. **Tên miền phụ miễn phí kèm hosting**: `*.rf.gd`, `*.infinityfreeapp.com`,
   `*.atwebpages.com`, `*.pages.dev`, `*.github.io`.
3. **Địa chỉ Tailscale sẵn có**: `https://ducnguyen.tailce49e0.ts.net` — miễn phí,
   dùng được ngay, nhưng không gắn được tên miền riêng.

Sau khi có tên miền riêng, trỏ bản ghi **A** về địa chỉ IP của hosting/VPS (hoặc
bản ghi **CNAME** nếu nhà cung cấp yêu cầu), rồi sửa `APP_URL` trong `.env`.

---

## Việc cần làm sau khi website lên mạng

- [ ] Đổi mật khẩu tài khoản quản trị mẫu (`admin@icandoit.test`).
- [ ] Kiểm tra `APP_DEBUG=false` trong `.env`.
- [ ] Vào `/admin/noi-dung` điền thông tin thật: hotline, địa chỉ, học phí,
      giảng viên, lịch khai giảng.
- [ ] Kiểm tra form đăng ký tư vấn gửi được và xem được ở `/admin/consultations`.
- [ ] Sao lưu định kỳ file `database/database.sqlite` (hoặc database MySQL).
