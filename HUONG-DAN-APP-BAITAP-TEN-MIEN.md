# Đưa app bài tập trên máy trung tâm về tên miền riêng

Mục tiêu: học viên vào **`https://baitap.icandoit.vn`** là mở được app giao bài
tập đang chạy trên máy ở trung tâm, thay cho địa chỉ dài `...ts.net/baitap/`.

## Phải mua thêm gì?

**Không mua gì thêm ngoài tên miền.** Cụ thể:

| Thứ thường bị nghĩ là phải mua | Thực tế |
| --- | --- |
| Hosting cho app bài tập | Không cần — app chạy ngay trên máy của trung tâm |
| IP tĩnh của nhà mạng | Không cần — Cloudflare Tunnel kết nối từ trong ra, không cần mở cổng |
| Chứng chỉ bảo mật HTTPS | Không cần — Cloudflare cấp miễn phí |
| Gói Cloudflare trả phí | Không cần — Tunnel nằm trong gói miễn phí |

Thứ duy nhất tốn thêm là **tiền điện và mạng cho máy chạy 24/7**: máy tắt thì
app bài tập cũng tắt theo.

## Vì sao không dùng tiếp Tailscale Funnel

Địa chỉ `...ts.net` hiện tại vẫn chạy tốt, nhưng **không gắn được tên miền riêng**:
chứng chỉ bảo mật của nó chỉ cấp cho `ts.net`, trỏ `baitap.icandoit.vn` sang đó
thì trình duyệt sẽ báo "không an toàn". Cloudflare Tunnel làm đúng việc đó và
cũng miễn phí, nên dùng nó thay thế.

## Bước 1 — Đưa tên miền về Cloudflare quản lý DNS

1. Tạo tài khoản miễn phí tại https://dash.cloudflare.com → **Add a domain** →
   nhập `icandoit.vn` → chọn gói **Free**.
2. Cloudflare đọc sẵn các bản ghi đang có và đưa cho **2 địa chỉ nameserver**
   (dạng `xxx.ns.cloudflare.com`).
3. Vào trang quản lý tên miền ở nhà đăng ký (Mắt Bão, PA, Nhân Hoà, iNET…),
   mục **Nameserver / DNS**, thay 2 nameserver mặc định bằng 2 cái Cloudflare đưa.
   Chờ 15 phút – vài giờ cho tới khi Cloudflare báo **Active**.

Từ lúc này mọi bản ghi DNS khai trong Cloudflare, không khai ở nhà đăng ký nữa.

## Bước 2 — Khai lại bản ghi cho website GitHub Pages

Trong Cloudflare → **DNS → Records**, bảo đảm có đủ:

| Loại | Tên | Nội dung | Proxy |
| --- | --- | --- | --- |
| A | `@` | `185.199.108.153` | **DNS only** (mây xám) |
| A | `@` | `185.199.109.153` | **DNS only** |
| A | `@` | `185.199.110.153` | **DNS only** |
| A | `@` | `185.199.111.153` | **DNS only** |
| CNAME | `www` | `baduc152-maker.github.io` | **DNS only** |

Phải để **mây xám (DNS only)** cho các bản ghi của GitHub Pages, nếu bật mây cam
thì GitHub không cấp được chứng chỉ HTTPS.

## Bước 3 — Cài Cloudflare Tunnel trên máy chạy app bài tập

Làm trên chính máy đang chạy app (cần biết app đang nghe ở cổng nào, ví dụ
`http://localhost:8080`).

**Cách dễ nhất — làm qua giao diện web:**

1. Cloudflare Dashboard → **Zero Trust** → **Networks → Tunnels** →
   **Create a tunnel** → chọn **Cloudflared** → đặt tên `icandoit`.
2. Cloudflare hiện sẵn câu lệnh cài đặt cho Windows / macOS / Linux kèm mã thông
   báo của tunnel. Chép đúng câu lệnh đó, dán vào cửa sổ dòng lệnh **của máy chạy
   app** (Windows: PowerShell chạy bằng quyền Administrator) rồi chạy.
3. Vẫn trong trang đó, tab **Public Hostname** → **Add a public hostname**:
   - *Subdomain*: `baitap`
   - *Domain*: `icandoit.vn`
   - *Service → Type*: `HTTP`
   - *URL*: `localhost:8080` (thay bằng cổng thật của app bài tập)
4. Bấm **Save**. Khoảng một phút sau mở `https://baitap.icandoit.vn` là thấy app.

Lệnh cài ở bước 2 đã đăng ký tunnel thành dịch vụ chạy nền, nên máy khởi động lại
là tunnel tự bật, không phải mở tay.

## Bước 4 — Nối lại với website

Vào trang quản trị nội dung → **Liên kết ứng dụng** → mục *Bài tập về nhà*, sửa
đường dẫn thành `https://baitap.icandoit.vn`, lưu lại. Sau đó vào tab **Actions**
trên GitHub → **Cập nhật website tĩnh** → **Run workflow** để website công khai
hiện đường dẫn mới.

Làm tương tự với tên miền phụ `noibo.icandoit.vn` nếu muốn đưa cả khu vực nội bộ
(điểm danh, lịch công việc, quản trị nội dung) ra tên miền riêng: thêm một
*Public Hostname* nữa trong cùng tunnel, trỏ về cổng của bản Laravel.

## Nếu không muốn đặt server ở máy trung tâm

Khi đó app bài tập (và khu vực nội bộ) cần một chỗ chạy 24/7 thuê ngoài. Website
công khai vẫn ở GitHub Pages, miễn phí, không đổi gì.

| Phương án | Chi phí | Phù hợp khi |
| --- | --- | --- |
| **Hosting PHP trả phí** (AZDIGI, TinoHost, Vietnix, Hostinger…) | ~30.000–60.000đ/tháng, trả theo năm | App viết bằng PHP + MySQL. Dễ nhất: có cPanel, phpMyAdmin, hỗ trợ tiếng Việt, không phải quản trị máy chủ |
| **VPS giá rẻ** trong nước | ~79.000–150.000đ/tháng | App viết bằng Node.js, Python… hoặc cần cài phần mềm riêng |
| **Oracle Cloud Always Free** | 0đ vĩnh viễn | Máy ảo 4 nhân / 24 GB RAM, nhưng phải có thẻ Visa/Mastercard để xác minh và phải tự cài Linux |

Khuyến nghị: nếu app chạy bằng PHP thì mua **hosting PHP trả phí** — rẻ, chạy
24/7, có người hỗ trợ bằng tiếng Việt khi hỏng. Toàn bộ công cụ đã chuẩn bị trong
dự án này (gói nén `scripts/build-release.sh`, trang cài đặt `/cai-dat`, file
`database/schema/mysql-schema.sql`) dùng được thẳng trên hosting trả phí; lần
triển khai InfinityFree chỉ hỏng vì giới hạn tài nguyên của **bản miễn phí**,
không phải vì mã nguồn.

Với hosting hoặc VPS thuê ngoài thì **không cần Cloudflare Tunnel nữa**: chỉ cần
thêm bản ghi A của tên miền phụ trỏ về địa chỉ IP nhà cung cấp cấp cho, ví dụ
`baitap` → `203.0.113.10`, rồi bật SSL miễn phí (Let's Encrypt) trong cPanel.

## Giới hạn cần biết

- **Máy phải bật.** Máy tắt, mất điện hay mất mạng là học viên không nộp bài được.
  Nếu việc nộp bài quan trọng, cân nhắc thuê VPS khoảng 100.000–200.000đ/tháng và
  chuyển app sang đó — lúc đó vẫn dùng Cloudflare Tunnel hoặc trỏ thẳng bản ghi A
  về IP của VPS.
- **Dung lượng mỗi lần tải lên** qua Cloudflare gói miễn phí khoảng **100 MB**.
  Học viên nộp ảnh, file Word, PDF thì thoải mái; nộp video dài thì nên cho nộp
  bằng link Google Drive hoặc YouTube.
- Website công khai (GitHub Pages) **không phụ thuộc** vào máy này: máy tắt thì
  trang giới thiệu, khoá học, liên hệ vẫn chạy bình thường.
