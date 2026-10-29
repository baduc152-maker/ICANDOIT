# Hướng dẫn lấy tên miền miễn phí và đưa website lên InfinityFree

**InfinityFree** là nhà cung cấp hosting PHP miễn phí (không cần thẻ ngân hàng),
kèm tên miền phụ miễn phí với các đuôi **`.ct.ws`**, **`.rf.gd`**,
**`.infinityfreeapp.com`**… Kết quả cuối cùng: website chạy tại địa chỉ dạng
`http://icandoit.ct.ws`, **có đủ trang quản trị nội dung và form đăng ký tư vấn**.

> Tên các nút trong bảng điều khiển có thể hơi khác theo từng thời điểm. Nếu không
> thấy đúng chữ như dưới đây, tìm mục có ý nghĩa tương đương.

Thời gian làm: khoảng 45–60 phút, trong đó có 5–15 phút chờ kích hoạt tên miền.

---

## Phần 1 · Tạo tài khoản và lấy tên miền rf.gd

1. Mở **https://infinityfree.com** → bấm **Sign Up**, nhập email và mật khẩu.
2. Mở hộp thư, bấm liên kết xác thực email.
3. Đăng nhập, bấm **Create Account** (hoặc **New Hosting Account**).
4. Chọn mục **Free Subdomain**, gõ tên muốn dùng rồi chọn một đuôi có sẵn
   (**`.ct.ws`**, `.rf.gd`, `.infinityfreeapp.com`…):
   - Gõ **một từ duy nhất**, không dấu, không khoảng trắng, không dấu chấm.
   - Ví dụ: `icandoit` → địa chỉ sẽ là `icandoit.ct.ws`.
   - Nếu báo đã có người dùng, thử `icandoitenglish`, `icandoit-hn`…
5. Đặt **mật khẩu cho tài khoản hosting** (khác mật khẩu đăng nhập ở bước 1) rồi
   bấm **Create Account**.
6. Chờ 5–15 phút cho tên miền kích hoạt. Mở thử `http://icandoit.ct.ws`, thấy
   trang mặc định của InfinityFree là đã xong.
7. Vào **Account Details** và ghi lại (sẽ dùng ở các bước sau):
   - FTP hostname, FTP username, FTP password
   - MySQL hostname (dạng `sqlxxx.infinityfree.com`)

## Phần 2 · Tạo cơ sở dữ liệu

8. Bấm **Control Panel** để mở bảng điều khiển (VistaPanel).
9. Vào **MySQL Databases** → ô *Create Database* gõ `icandoit` → **Create Database**.
10. Ghi lại tên database và tên người dùng đầy đủ mà hệ thống sinh ra, dạng
    `if0_12345678_icandoit`. Mật khẩu database chính là **mật khẩu tài khoản
    hosting** ở bước 5.

## Phần 3 · Chuẩn bị ở máy mình

Mở cửa sổ dòng lệnh trong thư mục mã nguồn website:

```bash
# 1. Đóng gói mã nguồn thành file zip
bash scripts/build-release.sh          # tạo release/icandoit-website.zip

# 2. Sinh khoá bảo mật cho ứng dụng — ghi lại cả dòng base64:...
php artisan key:generate --show

# 3. Sinh câu lệnh SQL tạo tài khoản quản trị — ghi lại cả câu INSERT
php artisan icandoit:admin-sql quantri@icandoit.edu.vn "MatKhauRatManh2026"
```

## Phần 4 · Tải mã nguồn lên hosting

11. Trong Control Panel, mở **Online File Manager** (hoặc dùng FileZilla với
    thông tin FTP ở bước 7).
12. Vào thư mục **`htdocs`** và **xoá file `index2.html`** mặc định nếu có.
13. Tải `release/icandoit-website.zip` lên, rồi bấm chuột phải vào file →
    **Extract** để giải nén ngay trong `htdocs`. Xoá file zip sau khi giải nén.

    > File Manager trên trình duyệt thường **giới hạn dung lượng mỗi lần tải**
    > (khoảng 10 MB). Nếu file zip lớn hơn, dùng **FileZilla** kết nối FTP bằng
    > thông tin ở bước 7, kéo thả cả thư mục mã nguồn vào `htdocs` (chậm hơn
    > nhưng chắc chắn được).
14. Kiểm tra: trong `htdocs` phải thấy `.htaccess`, `artisan`, các thư mục `app`,
    `public`, `vendor`, `storage`…

    > File `.htaccess` ở thư mục gốc đã có sẵn trong mã nguồn, giúp hosting
    > chuyển mọi yêu cầu vào thư mục `public/` của Laravel. Nếu File Manager ẩn
    > các file bắt đầu bằng dấu chấm, bật **Show hidden files**.

## Phần 5 · Tạo file cấu hình `.env`

15. Trong `htdocs`, tạo file mới tên **`.env`** (cùng cấp với `artisan`) và dán
    nội dung sau, thay các giá trị in đậm bằng thông tin của mình:

```dotenv
APP_NAME="I CAN DO IT ENGLISH"
APP_ENV=production
APP_KEY=base64:...                     # dán chuỗi ở Phần 3 bước 2
APP_DEBUG=false
APP_URL=http://icandoit.ct.ws

APP_LOCALE=vi
APP_TIMEZONE=Asia/Ho_Chi_Minh

DB_CONNECTION=mysql
DB_HOST=sqlxxx.infinityfree.com        # MySQL hostname ở bước 7
DB_PORT=3306
DB_DATABASE=if0_12345678_icandoit
DB_USERNAME=if0_12345678
DB_PASSWORD=                           # mật khẩu tài khoản hosting ở bước 5

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack
LOG_LEVEL=error
```

16. Kiểm tra hai thư mục `storage` và `bootstrap/cache` có quyền ghi (nếu website
    báo lỗi ghi file, đặt quyền `755`, cần thiết thì `777`).

## Phần 6 · Nhập cấu trúc cơ sở dữ liệu

17. Control Panel → **phpMyAdmin** → chọn database vừa tạo.
18. Tab **Import** → **Choose File** → chọn file `database/schema/mysql-schema.sql`
    trong mã nguồn ở máy mình → bấm **Go/Import**.
    Sau khi xong sẽ thấy các bảng `users`, `consultations`, `site_contents`,
    `attendances`…
19. Tab **SQL** → dán **câu INSERT** sinh ở Phần 3 bước 3 → bấm **Go**.
    Đây là tài khoản quản trị để đăng nhập website.

## Phần 7 · Chạy thử và hoàn thiện

20. Mở `http://icandoit.ct.ws` — website hiện trang chủ.
21. Vào `http://icandoit.ct.ws/login`, đăng nhập bằng email và mật khẩu ở Phần 3
    bước 3.
22. Vào **Nội dung website** (`/admin/noi-dung`) điền thông tin thật: hotline,
    địa chỉ, học phí, giảng viên, lịch khai giảng.
23. Thử gửi form đăng ký tư vấn ở trang chủ, rồi kiểm tra ở `/admin/consultations`.
24. **Bật HTTPS miễn phí:** Control Panel → **SSL/TLS** (hoặc *Free SSL
    Certificates*) → tạo chứng chỉ cho `icandoit.ct.ws` → chờ cấp xong rồi sửa
    `APP_URL` trong `.env` thành `https://icandoit.ct.ws`.

---

## Khi gặp lỗi

| Hiện tượng | Cách xử lý |
|------------|------------|
| Lỗi 500 ngay trang chủ | Thiếu `APP_KEY` hoặc sai cú pháp `.env`. Tạm đặt `APP_DEBUG=true` để xem thông báo, sửa xong nhớ đặt lại `false`. |
| Báo *No application encryption key* | Chưa dán `APP_KEY=base64:...` vào `.env`. |
| Báo lỗi kết nối cơ sở dữ liệu | Sai `DB_HOST`/`DB_DATABASE`/`DB_USERNAME`; mật khẩu database là mật khẩu tài khoản hosting. |
| Trang trắng hoặc hiện mã PHP | Chưa giải nén đúng vào `htdocs`, hoặc thiếu file `.htaccess` ở thư mục gốc. |
| Báo lỗi ghi file trong `storage` | Đặt lại quyền thư mục `storage` và `bootstrap/cache`. |
| Vẫn hiện trang mặc định của InfinityFree | Chưa xoá `index2.html` trong `htdocs`, hoặc tên miền chưa kích hoạt xong. |

## Điều cần biết trước khi chọn rf.gd

- Tên miền phụ miễn phí (`.ct.ws`, `.rf.gd`…) **không chuyển được** sang nhà
  cung cấp khác.
- Tài khoản **bỏ không dùng lâu có thể bị xoá**; nên đăng nhập vài tháng một lần.
- **Không có SSH và cron**: mỗi lần cập nhật mã nguồn phải tải lại file qua FTP,
  và khi có thay đổi cấu trúc cơ sở dữ liệu thì nhập lại file `.sql` tương ứng.
- Có **giới hạn lượt truy cập mỗi ngày**; vượt quá sẽ bị tạm khoá vài giờ.
- Muốn địa chỉ chuyên nghiệp hơn (`icandoit.id.vn`), đăng ký tên miền `.id.vn`
  miễn phí rồi thêm vào hosting bằng mục **Addon Domains** và trỏ nameserver theo
  hướng dẫn của InfinityFree.

Các cách triển khai khác (máy sẵn có với Tailscale Funnel, VPS, bản tĩnh) xem
trong [HUONG-DAN-TRIEN-KHAI.md](HUONG-DAN-TRIEN-KHAI.md).
