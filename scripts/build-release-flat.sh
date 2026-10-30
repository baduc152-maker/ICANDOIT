#!/usr/bin/env bash
#
# Đóng gói bản dành cho hosting chia sẻ khó tính: nội dung thư mục public/ nằm
# ngay thư mục gốc, phần mã nguồn nằm trong thư mục con laravel/ và bị chặn truy
# cập. Nhờ vậy không cần .htaccess chuyển hướng vào thư mục con.
#
# Cách dùng:  bash scripts/build-release-flat.sh
#
set -euo pipefail

cd "$(dirname "$0")/.."
PROJECT_DIR="$(pwd)"
STAGE="$(mktemp -d "${TMPDIR:-/tmp}/icandoit-flat-XXXXXX")"
RELEASE_DIR="$PROJECT_DIR/release"
ZIP_FILE="$RELEASE_DIR/icandoit-website-flat.zip"
TMP_ZIP="$(mktemp -u "${TMPDIR:-/tmp}/icandoit-flat-XXXXXX").zip"

echo "==> Cài thư viện PHP cho môi trường thật"
composer install --no-dev --optimize-autoloader --no-interaction
php artisan optimize:clear >/dev/null 2>&1 || true

echo "==> Dựng cấu trúc thư mục"
mkdir -p "$STAGE/laravel"
for item in app bootstrap config database resources routes scripts storage vendor \
            artisan composer.json composer.lock package.json phpunit.xml vite.config.js \
            .env.example .editorconfig .npmrc README.md; do
    [ -e "$item" ] && cp -a "$item" "$STAGE/laravel/"
done
[ -f .env ] && cp -a .env "$STAGE/laravel/"
cp -a public/. "$STAGE/"

# Thư mục không cần đưa lên máy chủ.
rm -rf "$STAGE/laravel/storage/app/preview" "$STAGE/laravel/database/database.sqlite"
find "$STAGE/laravel/storage/framework/views" -type f -name '*.php' -delete 2>/dev/null || true
find "$STAGE/laravel/vendor" -name '.git' -type d -prune -exec rm -rf {} + 2>/dev/null || true

echo "==> Sửa đường dẫn trong index.php"
sed -i "s|__DIR__\.'/\.\./|__DIR__.'/laravel/|g" "$STAGE/index.php"

echo "==> Ghi .htaccess"
cat > "$STAGE/.htaccess" <<'APACHE'
# Chuyển mọi yêu cầu vào index.php của Laravel.
# Không dùng chỉ thị Options để tránh lỗi 500 trên hosting hạn chế .htaccess.
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

<IfModule mod_rewrite.c>
    RewriteRule ^laravel/ - [F,L]
</IfModule>
APACHE

cat > "$STAGE/laravel/.htaccess" <<'APACHE'
# Không cho web truy cập trực tiếp vào thư mục mã nguồn.
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
    Order allow,deny
    Deny from all
</IfModule>
APACHE

echo "==> Tạo file nén"
(cd "$STAGE" && zip -rq -9 "$TMP_ZIP" .)
mkdir -p "$RELEASE_DIR"
mv -f "$TMP_ZIP" "$ZIP_FILE"
rm -rf "$STAGE"

echo
echo "Xong: release/icandoit-website-flat.zip ($(du -h "$ZIP_FILE" | cut -f1))"
echo "Giải nén toàn bộ vào thư mục gốc của website (htdocs), rồi mở /cai-dat."
