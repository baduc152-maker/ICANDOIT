<?php

namespace App\Console\Commands;

use App\Services\SiteContentManager;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

/**
 * Xuất phần website công khai ra HTML tĩnh.
 *
 * Trang tĩnh không cần PHP nên chạy được trên các nền tảng miễn phí không giới
 * hạn tài nguyên (GitHub Pages, Cloudflare Pages, Netlify...). Khu vực nội bộ
 * (đăng nhập, điểm danh, lịch công việc, quản trị nội dung) vẫn cần bản Laravel
 * đầy đủ; địa chỉ của bản đó khai báo ở center.contact.internal_url.
 */
class ExportStaticSite extends Command
{
    protected $signature = 'icandoit:export-static
        {--dir=dist : Thư mục nhận kết quả, tính từ thư mục gốc của dự án}
        {--base= : Địa chỉ website sau khi phát hành, ví dụ https://tentoi.github.io/icandoit (mặc định lấy APP_URL)}';

    protected $description = 'Xuất website công khai ra HTML tĩnh để đưa lên GitHub Pages, Cloudflare Pages...';

    public function handle(HttpKernel $kernel): int
    {
        $base = rtrim((string) ($this->option('base') ?: config('app.url')), '/');

        if (! str_starts_with($base, 'http://') && ! str_starts_with($base, 'https://')) {
            $this->components->error('Địa chỉ website phải bắt đầu bằng http:// hoặc https://, đang nhận: '.$base);

            return self::FAILURE;
        }

        $target = $this->targetDirectory();

        $this->prepareApplication($base);

        // GitHub Pages lưu tên miền riêng trong file CNAME ngay thư mục này,
        // nên giữ lại để lần xuất mới không làm mất tên miền.
        $cname = File::exists($target.'/CNAME') ? File::get($target.'/CNAME') : null;

        File::deleteDirectory($target);
        File::ensureDirectoryExists($target);

        if ($cname !== null) {
            $this->write($target.'/CNAME', $cname);
        }

        foreach ($this->pages() as $path => $file) {
            $content = $this->render($kernel, $path);

            if ($content === null) {
                return self::FAILURE;
            }

            $this->write($target.'/'.$file, $content);
            $this->components->twoColumnDetail($path === '/' ? '/ (trang chủ)' : $path, $file);
        }

        // Trang báo không tìm thấy: GitHub Pages và Cloudflare Pages đều dùng file 404.html.
        $this->write($target.'/404.html', view('errors.404')->render());

        $this->copyAssets($target);

        $this->write($target.'/robots.txt', $this->robots($base));

        // Báo GitHub Pages đừng xử lý thư mục qua Jekyll (nếu không, file bắt đầu
        // bằng dấu gạch dưới sẽ bị bỏ qua).
        $this->write($target.'/.nojekyll', '');

        $this->newLine();
        $this->components->info('Đã xuất website tĩnh vào '.$target.' với địa chỉ '.$base);

        return self::SUCCESS;
    }

    private function targetDirectory(): string
    {
        $dir = (string) $this->option('dir');

        return str_starts_with($dir, '/') ? rtrim($dir, '/') : rtrim(base_path($dir), '/');
    }

    /**
     * Chuyển ứng dụng sang chế độ xuất trang tĩnh: không dùng CSDL cho phiên làm
     * việc hay bộ nhớ đệm, và sinh mọi đường dẫn theo địa chỉ sẽ phát hành.
     */
    private function prepareApplication(string $base): void
    {
        config([
            'center.static_build' => true,
            'app.url' => $base,
            'session.driver' => 'array',
            'cache.default' => 'array',
            'queue.default' => 'sync',
        ]);

        URL::forceRootUrl($base);

        if (str_starts_with($base, 'https://')) {
            URL::forceScheme('https');
        }

        // Nội dung đã sửa trong trang quản trị (nếu chạy lệnh trên máy có CSDL).
        app(SiteContentManager::class)->applyToConfig();
    }

    /**
     * Danh sách trang cần xuất: đường dẫn => file kết quả.
     *
     * @return array<string, string>
     */
    private function pages(): array
    {
        $pages = [
            '/' => 'index.html',
            '/gioi-thieu' => 'gioi-thieu/index.html',
            '/khoa-hoc' => 'khoa-hoc/index.html',
            '/giang-vien' => 'giang-vien/index.html',
            '/lich-khai-giang' => 'lich-khai-giang/index.html',
            '/cam-nang' => 'cam-nang/index.html',
            '/lien-he' => 'lien-he/index.html',
            '/sitemap.xml' => 'sitemap.xml',
        ];

        foreach (config('center.courses', []) as $course) {
            $pages['/khoa-hoc/'.$course['slug']] = 'khoa-hoc/'.$course['slug'].'/index.html';
        }

        foreach (config('center.posts', []) as $post) {
            $pages['/cam-nang/'.$post['slug']] = 'cam-nang/'.$post['slug'].'/index.html';
        }

        return $pages;
    }

    private function render(HttpKernel $kernel, string $path): ?string
    {
        $response = $kernel->handle(Request::create('http://localhost'.$path, 'GET'));

        if ($response->getStatusCode() !== 200) {
            $this->components->error('Không render được '.$path.' (mã '.$response->getStatusCode().').');

            return null;
        }

        return (string) $response->getContent();
    }

    private function copyAssets(string $target): void
    {
        foreach (['css', 'js', 'images'] as $folder) {
            if (File::isDirectory(public_path($folder))) {
                File::copyDirectory(public_path($folder), $target.'/'.$folder);
            }
        }

        if (File::exists(public_path('favicon.ico'))) {
            File::copy(public_path('favicon.ico'), $target.'/favicon.ico');
        }
    }

    private function robots(string $base): string
    {
        return "User-agent: *\nAllow: /\n\nSitemap: ".$base."/sitemap.xml\n";
    }

    private function write(string $path, string $content): void
    {
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }
}
