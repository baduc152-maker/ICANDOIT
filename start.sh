#!/usr/bin/env bash
#
# Khởi động ICANDOIT trên macOS / Linux.
# Lần đầu sẽ tự tạo cấu hình, cơ sở dữ liệu SQLite và dữ liệu mẫu.
#
set -e
cd "$(dirname "$0")"

if ! command -v php >/dev/null 2>&1; then
    echo "❌ Chưa cài PHP. Cần PHP 8.3 trở lên — xem HUONG-DAN-CHAY.md."
    exit 1
fi

# 1) Cấu hình môi trường.
if [ ! -f .env ]; then
    echo "→ Tạo file cấu hình .env"
    cp .env.example .env
fi

# 2) Khóa ứng dụng.
if ! grep -q '^APP_KEY=base64:' .env; then
    echo "→ Sinh khóa ứng dụng"
    php artisan key:generate --force
fi

# 3) Cơ sở dữ liệu SQLite + dữ liệu mẫu (chỉ lần đầu).
FIRST_RUN=0
if [ ! -f database/database.sqlite ]; then
    echo "→ Tạo cơ sở dữ liệu SQLite"
    touch database/database.sqlite
    FIRST_RUN=1
fi

php artisan migrate --force
if [ "$FIRST_RUN" = "1" ]; then
    echo "→ Nạp dữ liệu mẫu"
    php artisan db:seed --force
fi

echo ""
echo "============================================================"
echo "  ICANDOIT đang chạy tại:  http://127.0.0.1:8000"
echo "  Đăng nhập quản trị:      admin@icandoit.test / password"
echo "  Nhấn Ctrl + C để dừng."
echo "============================================================"
echo ""

php artisan serve
