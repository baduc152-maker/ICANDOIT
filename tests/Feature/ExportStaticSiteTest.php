<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ExportStaticSiteTest extends TestCase
{
    use RefreshDatabase;

    private string $target = '';

    protected function tearDown(): void
    {
        if ($this->target !== '') {
            File::deleteDirectory($this->target);
        }

        parent::tearDown();
    }

    public function test_it_exports_every_public_page_with_the_published_address(): void
    {
        $this->target = storage_path('framework/testing/trang-tinh');

        $this->artisan('icandoit:export-static', [
            '--dir' => $this->target,
            '--base' => 'https://vi-du.github.io/ICANDOIT',
        ])->assertSuccessful();

        foreach ([
            'index.html',
            'gioi-thieu/index.html',
            'khoa-hoc/index.html',
            'khoa-hoc/ielts-foundation/index.html',
            'giang-vien/index.html',
            'lich-khai-giang/index.html',
            'cam-nang/index.html',
            'lien-he/index.html',
            'sitemap.xml',
            '404.html',
            'robots.txt',
            '.nojekyll',
            'css/site.css',
            'images/logo-mark.png',
        ] as $file) {
            $this->assertFileExists($this->target.'/'.$file);
        }

        $home = File::get($this->target.'/index.html');
        $this->assertStringContainsString('https://vi-du.github.io/ICANDOIT/khoa-hoc', $home);
        $this->assertStringContainsString('https://vi-du.github.io/ICANDOIT/css/site.css', $home);

        // Trang tĩnh không có trang đăng nhập: liên kết nội bộ phải được ẩn.
        $this->assertStringNotContainsString('Khu vực nhân sự', $home);

        $this->assertStringContainsString(
            'https://vi-du.github.io/ICANDOIT/sitemap.xml',
            File::get($this->target.'/robots.txt'),
        );
    }

    public function test_the_consultation_form_becomes_direct_contact_buttons(): void
    {
        $this->target = storage_path('framework/testing/trang-tinh-form');

        $this->artisan('icandoit:export-static', [
            '--dir' => $this->target,
            '--base' => 'https://vi-du.github.io/ICANDOIT',
        ])->assertSuccessful();

        $contact = File::get($this->target.'/lien-he/index.html');

        $this->assertStringNotContainsString('<form method="POST"', $contact);
        $this->assertStringContainsString('lead-card__direct', $contact);
        $this->assertStringContainsString(config('center.contact.hotline'), $contact);
    }

    public function test_it_refuses_an_address_without_a_scheme(): void
    {
        $this->artisan('icandoit:export-static', [
            '--dir' => storage_path('framework/testing/khong-dung'),
            '--base' => 'icandoit.ct.ws',
        ])->assertFailed();
    }
}
