#!/usr/bin/env bash
#
# Đóng gói mã nguồn thành một file zip sẵn sàng tải lên hosting.
# Cách dùng:  bash scripts/build-release.sh
#
set -euo pipefail

cd "$(dirname "$0")/.."
PROJECT_DIR="$(pwd)"
RELEASE_DIR="$PROJECT_DIR/release"
ZIP_FILE="$RELEASE_DIR/icandoit-website.zip"
# Tạo file nén ở ngoài thư mục dự án để không tự nén chính nó.
TMP_ZIP="$(mktemp -u "${TMPDIR:-/tmp}/icandoit-release-XXXXXX").zip"

echo "==> Cài thư viện PHP cho môi trường thật (bỏ gói dành cho lập trình)"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Dọn cache cũ"
php artisan optimize:clear >/dev/null 2>&1 || true

echo "==> Tạo file nén"
zip -rq "$TMP_ZIP" . \
    -x './.git/*' './.github/*' './node_modules/*' './release/*' './tests/*' \
       './.env' './database/database.sqlite' './storage/logs/*' \
       './storage/framework/cache/data/*' './storage/framework/sessions/*' \
       './storage/framework/views/*' './storage/app/preview/*'

mkdir -p "$RELEASE_DIR"
mv -f "$TMP_ZIP" "$ZIP_FILE"

echo
echo "Xong: release/icandoit-website.zip ($(du -h "$ZIP_FILE" | cut -f1))"
echo "Tải file này lên hosting rồi giải nén vào thư mục gốc (htdocs / public_html)."
echo "Sau đó tạo file .env theo hướng dẫn trong HUONG-DAN-TRIEN-KHAI.md"
echo
echo "Nhớ chạy lại 'composer install' nếu muốn tiếp tục phát triển và chạy test."
