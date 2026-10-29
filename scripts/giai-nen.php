<?php
/**
 * Công cụ giải nén gói cài đặt website ICANDOIT ngay trên hosting.
 *
 * Cách dùng:
 *   1. Tải file này và icandoit-website.zip vào thư mục htdocs (qua FTP).
 *   2. Mở:  http://ten-mien-cua-ban/giai-nen.php?token=DOI-CHUOI-NAY-THANH-CHUOI-NGAU-NHIEN
 *   3. Trang tự chạy từng đợt cho tới khi báo xong, rồi tự xoá chính nó.
 *
 * Nếu trang dừng giữa chừng, chỉ cần mở lại đúng đường dẫn trên, nó chạy tiếp.
 */

const TOKEN = 'DOI-CHUOI-NAY-THANH-CHUOI-NGAU-NHIEN';
const ZIP_FILE = 'icandoit-website.zip';
const STATE_FILE = '.giai-nen-state';
const BATCH = 300;

if (($_GET['token'] ?? '') !== TOKEN) {
    http_response_code(404);
    exit('Not found');
}

header('Content-Type: text/html; charset=utf-8');
$dir = __DIR__;
$zipPath = $dir . '/' . ZIP_FILE;

if (! class_exists('ZipArchive')) {
    exit('Hosting không bật ZipArchive. Hãy tải thư mục mã nguồn lên bằng FTP thay vì dùng file nén.');
}

if (! is_file($zipPath)) {
    exit('Không tìm thấy ' . ZIP_FILE . ' trong thư mục này.');
}

$zip = new ZipArchive();
if ($zip->open($zipPath) !== true) {
    exit('Không mở được file nén.');
}

$total = $zip->numFiles;
$done = (int) @file_get_contents($dir . '/' . STATE_FILE);
$end = min($done + BATCH, $total);

for ($i = $done; $i < $end; $i++) {
    $zip->extractTo($dir, [$zip->getNameIndex($i)]);
}

$zip->close();
file_put_contents($dir . '/' . STATE_FILE, (string) $end);

$percent = $total > 0 ? round($end / $total * 100) : 100;

echo '<!doctype html><html lang="vi"><meta charset="utf-8">';
echo '<body style="font-family:system-ui;max-width:640px;margin:60px auto;line-height:1.6">';
echo '<h2>Đang cài đặt website ICANDOIT</h2>';

if ($end < $total) {
    echo "<p>Đã giải nén <strong>{$end}/{$total}</strong> tệp ({$percent}%). Trang sẽ tự chạy tiếp…</p>";
    echo '<meta http-equiv="refresh" content="1">';
} else {
    @unlink($dir . '/' . STATE_FILE);
    @unlink($zipPath);
    @unlink(__FILE__);
    echo "<p>✅ Đã giải nén xong <strong>{$total}</strong> tệp.</p>";
    echo '<p>File nén và công cụ này đã được xoá để bảo mật.</p>';
    echo '<p>Bước tiếp theo: sửa thông tin cơ sở dữ liệu trong file <code>.env</code>, ';
    echo 'nhập <code>database/schema/mysql-schema.sql</code> bằng phpMyAdmin, rồi mở trang chủ.</p>';
    echo '<p><a href="/">Mở website</a></p>';
}

echo '</body></html>';
