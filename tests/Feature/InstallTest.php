<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\Installer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InstallTest extends TestCase
{
    use RefreshDatabase;

    private string $envPath;

    private string $lockPath;

    protected function setUp(): void
    {
        parent::setUp();

        // Dùng thư mục tạm để không đụng vào .env thật của dự án.
        $directory = storage_path('framework/testing/install-'.uniqid());
        File::ensureDirectoryExists($directory);

        $this->envPath = $directory.'/.env';
        $this->lockPath = $directory.'/installed.lock';

        $this->app->instance(Installer::class, new Installer($this->envPath, $this->lockPath));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(dirname($this->envPath));

        parent::tearDown();
    }

    /**
     * @return array<string, string>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'app_name' => 'I CAN DO IT ENGLISH',
            'app_url' => 'https://icandoit.ct.ws',
            'db_connection' => 'sqlite',
            'db_database' => '',
            'admin_name' => 'Quản trị viên',
            'admin_email' => 'quantri@icandoit.ct.ws',
            'admin_password' => 'MatKhauManh2026',
            'admin_password_confirmation' => 'MatKhauManh2026',
        ], $overrides);
    }

    public function test_install_page_opens_on_a_fresh_site(): void
    {
        $this->get(route('install'))
            ->assertOk()
            ->assertSee('Cài đặt website')
            ->assertSee('Tài khoản quản trị');
    }

    public function test_install_page_is_closed_when_an_admin_already_exists(): void
    {
        User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->get(route('install'))->assertNotFound();
        $this->post(route('install.store'), $this->payload())->assertNotFound();
    }

    public function test_install_page_is_closed_when_the_lock_file_exists(): void
    {
        file_put_contents($this->lockPath, 'done');

        $this->get(route('install'))->assertNotFound();
    }

    public function test_installation_requires_a_matching_password(): void
    {
        $this->from(route('install'))
            ->post(route('install.store'), $this->payload([
                'admin_password' => 'MatKhauManh2026',
                'admin_password_confirmation' => 'khac-han',
            ]))
            ->assertSessionHasErrors('admin_password');

        $this->assertFileDoesNotExist($this->envPath);
    }

    public function test_mysql_option_requires_connection_details(): void
    {
        $this->from(route('install'))
            ->post(route('install.store'), $this->payload(['db_connection' => 'mysql']))
            ->assertSessionHasErrors(['db_host', 'db_database', 'db_username']);
    }

}
