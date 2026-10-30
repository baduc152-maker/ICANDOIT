<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Installer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Trang cài đặt một lần: nhập thông tin cơ sở dữ liệu và tài khoản quản trị,
 * hệ thống tự ghi file .env, tạo bảng và tạo tài khoản. Sau khi cài xong trang
 * này tự khoá lại.
 */
class InstallController extends Controller
{
    public function __construct(private readonly Installer $installer)
    {
    }

    public function show(Request $request): View
    {
        $this->abortIfInstalled();

        return view('install.form', [
            'defaults' => [
                'app_name' => 'I CAN DO IT ENGLISH',
                'app_url' => rtrim($request->getSchemeAndHttpHost(), '/'),
                'db_connection' => 'mysql',
                'db_port' => 3306,
            ],
            'canWriteEnv' => $this->installer->canWriteEnv(),
        ]);
    }

    public function store(Request $request): View|RedirectResponse
    {
        $this->abortIfInstalled();

        $data = $request->validate([
            'app_name' => ['required', 'string', 'max:120'],
            'app_url' => ['required', 'url', 'max:200'],
            'db_connection' => ['required', 'in:mysql,sqlite'],
            'db_host' => ['required_if:db_connection,mysql', 'nullable', 'string', 'max:191'],
            'db_port' => ['nullable', 'integer', 'between:1,65535'],
            'db_database' => ['required_if:db_connection,mysql', 'nullable', 'string', 'max:191'],
            'db_username' => ['required_if:db_connection,mysql', 'nullable', 'string', 'max:191'],
            'db_password' => ['nullable', 'string', 'max:191'],
            'admin_name' => ['required', 'string', 'max:120'],
            'admin_email' => ['required', 'email', 'max:150'],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'admin_password.confirmed' => 'Hai ô mật khẩu chưa giống nhau.',
            'admin_password.min' => 'Mật khẩu cần ít nhất 8 ký tự.',
        ], [
            'app_name' => 'tên website',
            'app_url' => 'địa chỉ website',
            'db_host' => 'máy chủ cơ sở dữ liệu',
            'db_database' => 'tên cơ sở dữ liệu',
            'db_username' => 'tên đăng nhập cơ sở dữ liệu',
            'admin_name' => 'tên quản trị viên',
            'admin_email' => 'email đăng nhập',
            'admin_password' => 'mật khẩu',
        ]);

        if ($data['db_connection'] === 'sqlite') {
            $data['db_database'] = $data['db_database']
                ?: config('database.connections.sqlite.database', database_path('database.sqlite'));
            $this->ensureSqliteFile($data['db_database']);
        }

        $settings = $this->connectionSettings($data);

        $this->assertDatabaseReachable($settings);

        if (! $this->installer->canWriteEnv()) {
            throw ValidationException::withMessages([
                'db_host' => 'Không ghi được file .env tại '.$this->installer->envPath()
                    .'. Hãy đặt quyền ghi cho thư mục gốc rồi thử lại.',
            ]);
        }

        $data['app_key'] = $this->installer->appKey(config('app.key'));
        $this->installer->writeEnv($this->installer->buildEnv($data));

        // Nạp cấu hình mới ngay trong lượt chạy này để tạo bảng và tài khoản.
        config([
            'app.key' => $data['app_key'],
            'database.default' => $data['db_connection'],
            'database.connections.'.$data['db_connection'] => $settings,
        ]);
        DB::purge($data['db_connection']);

        Artisan::call('migrate', ['--force' => true]);

        $admin = User::query()->updateOrCreate(
            ['email' => $data['admin_email']],
            [
                'name' => $data['admin_name'],
                'password' => $data['admin_password'],
                'role' => 'admin',
                'is_active' => true,
            ],
        );

        $this->warmCaches();

        $locked = $this->installer->lock();

        return view('install.done', [
            'admin' => $admin,
            'locked' => $locked,
            'appUrl' => $data['app_url'],
        ]);
    }

    /**
     * Gom tuyến đường và giao diện vào bộ nhớ đệm. Mỗi lượt truy cập sau đó đọc
     * ít file hơn hẳn — rất quan trọng trên hosting giới hạn tài nguyên.
     *
     * Không gom cấu hình (config:cache): khi đã có file cấu hình đệm, mọi thay
     * đổi trong .env sẽ bị bỏ qua, mà trên hosting dùng chung thì không có dòng
     * lệnh để xoá đệm đi.
     */
    private function warmCaches(): void
    {
        $app = app();

        foreach (['route:cache', 'view:cache'] as $command) {
            try {
                Artisan::call($command);
            } catch (\Throwable) {
                // Không ghi được bộ nhớ đệm thì website vẫn chạy, chỉ chậm hơn.
            }
        }

        // Hai lệnh trên dựng một ứng dụng tạm để đọc lại cấu hình, khiến facade
        // trỏ sang ứng dụng tạm đó. Trả facade về ứng dụng đang phục vụ yêu cầu
        // này, nếu không màn hình kết quả sẽ lỗi vì mất dữ liệu dùng chung.
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
    }

    private function abortIfInstalled(): void
    {
        abort_if($this->installer->isInstalled() || $this->hasAdminAccount(), 404);
    }

    /**
     * Đã có tài khoản quản trị nghĩa là website cài xong rồi, kể cả khi thiếu
     * file đánh dấu.
     */
    private function hasAdminAccount(): bool
    {
        try {
            return User::query()->where('role', 'admin')->exists();
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function connectionSettings(array $data): array
    {
        if ($data['db_connection'] === 'sqlite') {
            return [
                'driver' => 'sqlite',
                'database' => $data['db_database'],
                'prefix' => '',
                'foreign_key_constraints' => true,
            ];
        }

        return [
            'driver' => 'mysql',
            'host' => $data['db_host'],
            'port' => $data['db_port'] ?? 3306,
            'database' => $data['db_database'],
            'username' => $data['db_username'],
            'password' => $data['db_password'] ?? '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => 'InnoDB',
        ];
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    private function assertDatabaseReachable(array $settings): void
    {
        config(['database.connections.icandoit_install' => $settings]);
        DB::purge('icandoit_install');

        try {
            DB::connection('icandoit_install')->getPdo();
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                'db_host' => 'Không kết nối được cơ sở dữ liệu. Kiểm tra lại thông tin. Chi tiết: '
                    .str($e->getMessage())->limit(180),
            ]);
        }
    }

    private function ensureSqliteFile(string $path): void
    {
        if ($path !== ':memory:' && ! is_file($path)) {
            @mkdir(dirname($path), 0755, true);
            @touch($path);
        }
    }
}
