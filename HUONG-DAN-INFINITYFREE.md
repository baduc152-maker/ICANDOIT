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

## Phần 3 · Chuẩn bị gói cài đặt

Tải gói `icandoit-website.zip` đã đóng sẵn (hoặc tự tạo bằng
`bash scripts/build-release.sh` nếu máy đã cài PHP và Composer).

Không cần chạy lệnh nào khác: khoá bảo mật, cấu hình cơ sở dữ liệu và tài khoản
quản trị sẽ được tạo ở **Phần 5** ngay trên trình duyệt.

## Phần 4 · Tải mã nguồn lên hosting

11. Trong Control Panel, mở **Online File Manager** (hoặc dùng FileZilla với
    thông tin FTP ở bước 7).
12. Vào thư mục **`htdocs`** và **xoá file `index2.html`** mặc định nếu có.
13. Tải `release/icandoit-website.zip` lên, rồi bấm chuột phải vào file →
    **Extract** để giải nén ngay trong `htdocs`. Xoá file zip sau khi giải nén.

    > File Manager trên trình duyệt thường **giới hạn dung lượng mỗi lần tải**
    > (khoảng 10 MB), trong khi gói mã nguồn khoảng 20 MB. Hai cách xử lý:
    >
    > **Cách 1 (nhanh, khuyến nghị):** dùng **FileZilla** tải *hai file* vào
    > `htdocs`: `icandoit-website.zip` và `scripts/giai-nen.php`. Mở file
    > `scripts/giai-nen.php` trước khi tải lên, đổi chuỗi `TOKEN` thành một chuỗi
    > ngẫu nhiên của mình. Sau đó mở trình duyệt vào
    > `http://icandoit.ct.ws/giai-nen.php?token=CHUOI-CUA-BAN` — trang tự giải nén
    > theo từng đợt, xong sẽ **tự xoá cả file nén lẫn chính nó**.
    >
    > **Cách 2:** dùng FileZilla kéo thả toàn bộ thư mục mã nguồn vào `htdocs`
    > (gần 13.000 tệp nên rất chậm, có thể vài giờ).
14. Kiểm tra: trong `htdocs` phải thấy `.htaccess`, `artisan`, các thư mục `app`,
    `public`, `vendor`, `storage`…

    > File `.htaccess` ở thư mục gốc đã có sẵn trong mã nguồn, giúp hosting
    > chuyển mọi yêu cầu vào thư mục `public/` của Laravel. Nếu File Manager ẩn
    > các file bắt đầu bằng dấu chấm, bật **Show hidden files**.

## Phần 5 · Chạy trang cài đặt

15. Mở trình duyệt vào **`http://icandoit.ct.ws/cai-dat`**.
16. Điền ba nhóm thông tin trên một trang duy nhất:
    - **Cơ sở dữ liệu** — dán máy chủ, tên database, tên đăng nhập và mật khẩu
      lấy ở Phần 2 (mật khẩu database chính là mật khẩu tài khoản hosting).
    - **Tài khoản quản trị** — email và mật khẩu tự chọn để đăng nhập website.
    - **Thông tin website** — tên trung tâm và địa chỉ (đã điền sẵn).
17. Bấm **Cài đặt website**. Hệ thống tự ghi file cấu hình, tạo toàn bộ bảng dữ
    liệu và tạo tài khoản quản trị, mất khoảng 10–30 giây.
18. Cài xong, trang này **tự khoá lại** (mở lại sẽ báo không tìm thấy) để không
    ai chạy lại được.

> **Cách thủ công (chỉ dùng khi trang cài đặt báo lỗi):** tạo file `.env` theo mẫu
> trong `.env.example`, nhập `database/schema/mysql-schema.sql` bằng phpMyAdmin,
> rồi tạo tài khoản quản trị bằng câu lệnh sinh từ
> `php artisan icandoit:admin-sql email@cua-ban "MatKhau"`.

## Phần 7 · Chạy thử và hoàn thiện

19. Mở `http://icandoit.ct.ws` — website hiện trang chủ.
20. Vào `http://icandoit.ct.ws/login`, đăng nhập bằng email và mật khẩu vừa đặt
    ở Phần 5.
21. Vào **Nội dung** (`/admin/noi-dung`) điền thông tin thật: hotline, địa chỉ,
    học phí, giảng viên, lịch khai giảng.
22. Thử gửi form đăng ký tư vấn ở trang chủ, rồi kiểm tra ở `/admin/consultations`.
23. **Bật HTTPS miễn phí:** Control Panel → **SSL/TLS** (hoặc *Free SSL
    Certificates*) → tạo chứng chỉ cho `icandoit.ct.ws` → chờ cấp xong rồi sửa
    `APP_URL` trong `.env` thành `https://icandoit.ct.ws`.

---

## Khi gặp lỗi

| Hiện tượng | Cách xử lý |
|------------|------------|
| Mở `/cai-dat` báo *Not Found* | Website đã cài xong rồi (trang tự khoá). Vào thẳng `/login`. |
| Trang cài đặt báo không kết nối được cơ sở dữ liệu | Sai máy chủ, tên database hoặc tên đăng nhập; mật khẩu database là mật khẩu tài khoản hosting. |
| Trang cài đặt báo không ghi được file `.env` | Đặt quyền ghi (chmod 755) cho thư mục gốc chứa website rồi tải lại trang. |
| Lỗi 500 ngay trang chủ | Thiếu file `.env`. Chạy lại trang `/cai-dat`. |
| Trang trắng hoặc hiện mã PHP | Chưa giải nén đúng vào `htdocs`, hoặc thiếu file `.htaccess` ở thư mục gốc. |
| Báo lỗi ghi file trong `storage` | Đặt lại quyền thư mục `storage` và `bootstrap/cache`. |
| Vẫn hiện trang mặc định của InfinityFree | Chưa xoá `index2.html` trong `htdocs`, hoặc tên miền chưa kích hoạt xong. |

## Dùng tên miền đã mua ở nơi khác (Weebly/Square, Tenten, iNET…)

Website chạy trên InfinityFree vẫn gắn được tên miền riêng mua ở bất kỳ đâu:

1. Trong tài khoản InfinityFree, mở hosting account → **Addon Domains** (hoặc
   *Parked Domains*) → nhập tên miền, ví dụ `icandoit.com` → **Add Domain**.
2. Màn hình sẽ hiện **hai dòng nameserver** của chính tài khoản đó. Chép đúng
   hai dòng đang hiển thị (đừng dùng giá trị chép ở tài liệu khác, vì mỗi nhóm
   máy chủ một khác).
3. Đăng nhập nơi quản lý tên miền (Weebly/Square, Tenten, iNET…) → mục
   **Nameservers / DNS** → chọn *Custom nameservers* → dán hai dòng ở bước 2 →
   lưu lại.
4. Chờ DNS cập nhật (thường 15 phút – vài giờ, tối đa 48 giờ), rồi quay lại
   InfinityFree bật **SSL/TLS** cho tên miền mới.
5. Sửa `APP_URL` trong file `.env` thành địa chỉ mới, ví dụ
   `https://icandoit.com`.

> Tên miền phụ miễn phí do nhà cung cấp cấp (`*.weebly.com`, `*.ct.ws`) **không**
> trỏ đi nơi khác được — chỉ tên miền tự mua mới làm được bước này.

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
