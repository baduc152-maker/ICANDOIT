# Đưa website lên GitHub Pages (miễn phí, không giới hạn tài nguyên)

InfinityFree báo *"This site/page has used all available resources"* vì bản miễn phí
chỉ cho mỗi website một lượng CPU rất nhỏ mỗi giờ, mà website Laravel thì mỗi lần
có người truy cập là phải chạy PHP. Hết định mức là website khoá lại cho tới giờ sau.

Cách chắc chắn nhất để hết hẳn chuyện đó: **không chạy PHP nữa** cho phần website
công khai. Phần này toàn trang giới thiệu, khoá học, giảng viên, lịch khai giảng,
cẩm nang và liên hệ — nội dung giống nhau với mọi khách, nên có thể xuất sẵn ra
HTML. GitHub Pages phát hành HTML miễn phí, **không tính CPU, không giới hạn số
người xem** (chỉ giới hạn 1 GB dung lượng và 100 GB lưu lượng/tháng — website này
nặng chưa tới 1 MB).

## Phần nào ở đâu

| Phần | Chạy ở đâu | Vì sao |
| --- | --- | --- |
| Website công khai (16 trang) | GitHub Pages | HTML tĩnh, không cần PHP, không bị khoá vì hết tài nguyên |
| Khu vực nội bộ: đăng nhập, điểm danh, lịch công việc, thời khoá biểu, trang quản trị nội dung | Bản Laravel đầy đủ (máy của trung tâm hoặc một máy chủ riêng) | Cần PHP và cơ sở dữ liệu |

Sau khi có địa chỉ khu vực nội bộ, vào trang quản trị → **Thông tin liên hệ** →
ô *Địa chỉ khu vực nội bộ*, dán địa chỉ đó vào. Website tĩnh sẽ hiện lại liên kết
"Khu vực nhân sự". Để trống thì liên kết tự ẩn, khách không bấm vào link chết.

## Bước 1 — Bật GitHub Pages (làm một lần, khoảng 1 phút)

1. Mở https://github.com/baduc152-maker/ICANDOIT/settings/pages
2. Mục **Build and deployment** → **Source**: chọn **Deploy from a branch**.
3. Mục **Branch**: chọn nhánh `claude/icandoit-language-center-website-asx6l9`,
   ô bên cạnh chọn **/docs**, rồi bấm **Save**.
4. Chờ 1–2 phút rồi tải lại trang. GitHub sẽ hiện dòng
   *"Your site is live at ..."* với địa chỉ:

   **https://baduc152-maker.github.io/ICANDOIT/**

Thư mục `docs/` trong nhánh này đã có sẵn website hoàn chỉnh, nên bấm Save là có
website ngay, không phải làm gì thêm.

## Bước 2 (tuỳ chọn) — Dùng tên miền icandoit.ct.ws

Tên miền miễn phí đã tạo ở InfinityFree vẫn dùng được, chỉ trỏ sang GitHub:

1. Trong GitHub: trang **Settings → Pages** ở trên, mục **Custom domain**, nhập
   `icandoit.ct.ws` rồi bấm **Save**.
2. Trong InfinityFree (hoặc trang quản lý tên miền ct.ws): thêm một bản ghi
   **CNAME**, tên `icandoit` (hoặc `@` nếu panel hỏi cả tên miền), trỏ tới
   `baduc152-maker.github.io.`
3. Chờ DNS cập nhật (thường 15–60 phút). Khi GitHub báo *DNS check successful*,
   tích thêm ô **Enforce HTTPS** để website có khoá bảo mật.

Sau khi đổi tên miền, chạy lại việc xuất website (Bước 3) để mọi đường dẫn trong
trang dùng tên miền mới. Lệnh xuất tự giữ lại file `docs/CNAME` do GitHub tạo, nên
tên miền không bị mất.

## Bước 2b — Dùng tên miền riêng mua ở Việt Nam (.edu.vn, .vn, .com.vn)

Quy trình giống hệt tên miền `.ct.ws`, chỉ khác bản ghi DNS vì `icandoit.edu.vn`
là tên miền gốc (apex) chứ không phải tên miền phụ.

Trong trang quản lý DNS của nhà đăng ký (Mắt Bão, PA Việt Nam, Nhân Hoà, iNET,
Tenten, VNPT...), xoá các bản ghi A/CNAME đang trỏ về trang mặc định rồi thêm:

| Loại | Tên (Host) | Giá trị |
| --- | --- | --- |
| A | `@` | `185.199.108.153` |
| A | `@` | `185.199.109.153` |
| A | `@` | `185.199.110.153` |
| A | `@` | `185.199.111.153` |
| AAAA | `@` | `2606:50c0:8000::153` |
| AAAA | `@` | `2606:50c0:8001::153` |
| AAAA | `@` | `2606:50c0:8002::153` |
| AAAA | `@` | `2606:50c0:8003::153` |
| CNAME | `www` | `baduc152-maker.github.io.` |

Bốn bản ghi A là bắt buộc (GitHub chạy nhiều máy chủ); bốn bản ghi AAAA dành cho
IPv6, thiếu cũng không sao nhưng nên có. Sau đó vào **Settings → Pages →
Custom domain**, nhập `icandoit.edu.vn` → **Save**. GitHub tự tạo file `CNAME`
trong thư mục phát hành (`docs/`); nếu thấy file đó nằm ở gốc repo thì chuyển vào
`docs/`. Chờ GitHub báo *DNS check successful* rồi tích **Enforce HTTPS**.

Cuối cùng vào tab **Actions → Cập nhật website tĩnh → Run workflow**: workflow tự
đọc `docs/CNAME` và xuất lại toàn bộ trang theo tên miền mới, không phải sửa tay
chỗ nào.

### Khu vực nội bộ dùng tên miền phụ

Nên tách: `icandoit.edu.vn` cho website công khai (GitHub Pages),
`noibo.icandoit.edu.vn` cho bản Laravel đầy đủ. Thêm bản ghi A của tên miền phụ
`noibo` trỏ về địa chỉ IP máy chủ chạy Laravel, rồi dán
`https://noibo.icandoit.edu.vn` vào trang quản trị → *Thông tin liên hệ* →
*Địa chỉ khu vực nội bộ*.

Lưu ý: địa chỉ Tailscale Funnel (`...ts.net`) không gắn được tên miền riêng vì
chứng chỉ bảo mật của nó chỉ cấp cho `ts.net`. Muốn máy ở trung tâm chạy dưới tên
miền riêng thì dùng **Cloudflare Tunnel** (miễn phí, có cấp chứng chỉ cho tên miền
của bạn) thay cho Funnel.

## Bước 3 — Cập nhật website về sau

Mỗi khi mã nguồn hoặc nội dung mặc định thay đổi và được đẩy lên nhánh này,
GitHub Actions tự chạy lại việc xuất website (`.github/workflows/trang-tinh.yml`)
và ghi kết quả vào `docs/`. Muốn chạy tay: vào tab **Actions** →
**Cập nhật website tĩnh** → **Run workflow**.

Làm trên máy mình (nếu đã cài PHP):

```bash
php artisan icandoit:export-static --dir=docs --base=https://baduc152-maker.github.io/ICANDOIT
git add docs && git commit -m "Cập nhật website tĩnh" && git push
```

Nội dung sửa trong trang quản trị được lưu ở cơ sở dữ liệu của bản Laravel, nên
lệnh xuất chỉ lấy được bản sửa đó khi chạy trên đúng máy có cơ sở dữ liệu ấy.
Chạy trên GitHub Actions thì lấy nội dung mặc định trong `config/center.php`.

## Bước 4 (tuỳ chọn) — Cho form "Đăng ký tư vấn" hoạt động lại

Trang tĩnh không có PHP nên không tự nhận form. Hiện tại ô đăng ký ở trang Liên hệ
được thay bằng các nút **Gọi hotline / Zalo / Messenger / Email** — vẫn dùng được
ngay. Muốn có lại form điền thông tin:

1. Vào https://web3forms.com → nhập email của trung tâm → nhận **Access Key**
   (miễn phí 250 lượt gửi/tháng, không cần tạo tài khoản).
2. Vào trang quản trị → **Thông tin liên hệ**:
   - *Dịch vụ nhận form*: `https://api.web3forms.com/submit`
   - *Mã truy cập của dịch vụ nhận form*: dán Access Key vừa nhận.
3. Chạy lại Bước 3. Từ đó mỗi lượt đăng ký sẽ gửi thẳng vào email của trung tâm.

## Các nền tảng miễn phí khác cho cùng thư mục `docs/`

Thư mục `docs/` là HTML thuần nên kéo thả vào đâu cũng chạy:

- **Cloudflare Pages** — miễn phí, không giới hạn lưu lượng, cho tên miền riêng,
  kết nối thẳng với repo GitHub này (chọn thư mục `docs`).
- **Netlify** — kéo thả thư mục `docs` vào https://app.netlify.com/drop là có
  website trong 10 giây.

Cả hai đều không tính CPU như hosting PHP nên không bị khoá giữa giờ.

## Gặp lỗi thì xem ở đây

- **Tab Actions báo lỗi ở bước "Lưu kết quả vào nhánh"**: GitHub đang đặt quyền
  chỉ-đọc cho Actions. Vào **Settings → Actions → General → Workflow permissions**,
  chọn **Read and write permissions** → **Save**, rồi chạy lại workflow.
- **Mở địa chỉ github.io thấy 404**: Pages chưa lưu đúng nhánh/thư mục. Kiểm tra
  lại Bước 1 (phải là nhánh `claude/icandoit-language-center-website-asx6l9` và
  thư mục `/docs`), chờ thêm 2 phút rồi tải lại.
- **Website hiện ra nhưng mất giao diện (chữ trơn, không màu)**: địa chỉ xuất bản
  khác với địa chỉ đang mở. Chạy lại Bước 3 với `--base` đúng bằng địa chỉ thật.
