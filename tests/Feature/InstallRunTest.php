<?php

namespace Tests\Feature;

use App\Support\Installer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Chạy trọn quy trình cài đặt trên một cơ sở dữ liệu SQLite tạm.
 * Lớp này cố ý không dùng RefreshDatabase vì trang cài đặt tự cấu hình lại
 * kết nối cơ sở dữ liệu và chạy migrate.
 */
class InstallRunTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = storage_path('framework/testing/install-run-'.uniqid());
        File::ensureDirectoryExists($this->directory);
        touch($this->directory.'/database.sqlite');

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => $this->directory.'/database.sqlite',
        ]);
        DB::purge('sqlite');

        $this->app->instance(Installer::class, new Installer(
            $this->directory.'/.env',
            $this->directory.'/installed.lock',
        ));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->directory);

        parent::tearDown();
    }

    public function test_installation_writes_config_creates_tables_and_admin_then_locks(): void
    {
        $response = $this->post(route('install.store'), [
            'app_name' => 'I CAN DO IT ENGLISH',
            'app_url' => 'https://icandoit.ct.ws',
            'db_connection' => 'sqlite',
            'db_database' => $this->directory.'/database.sqlite',
            'admin_name' => 'Quản trị viên',
            'admin_email' => 'quantri@icandoit.ct.ws',
            'admin_password' => 'MatKhauManh2026',
            'admin_password_confirmation' => 'MatKhauManh2026',
        ]);

        $response->assertOk()->assertSee('Cài đặt xong');

        // 1. File cấu hình được ghi đúng.
        $env = file_get_contents($this->directory.'/.env');
        $this->assertStringContainsString('APP_URL="https://icandoit.ct.ws"', $env);
        $this->assertStringContainsString('APP_DEBUG=false', $env);
        $this->assertStringContainsString('APP_KEY=base64:', $env);
        $this->assertStringContainsString('DB_CONNECTION=sqlite', $env);

        // 2. Các bảng đã được tạo.
        foreach (['users', 'consultations', 'site_contents', 'timetable_entries', 'work_tasks'] as $table) {
            $this->assertTrue(DB::connection('sqlite')->getSchemaBuilder()->hasTable($table), "Thiếu bảng {$table}");
        }

        // 3. Tài khoản quản trị đăng nhập được.
        $admin = DB::connection('sqlite')->table('users')->where('email', 'quantri@icandoit.ct.ws')->first();
        $this->assertNotNull($admin);
        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('MatKhauManh2026', $admin->password));

        // 4. Trang cài đặt tự khoá lại.
        $this->assertFileExists($this->directory.'/installed.lock');
        $this->get(route('install'))->assertNotFound();
    }
}
