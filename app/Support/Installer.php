<?php

namespace App\Support;

/**
 * Hỗ trợ trang cài đặt: tạo nội dung file .env, ghi file và đánh dấu đã cài.
 */
class Installer
{
    public function __construct(
        private readonly string $envPath,
        private readonly string $lockPath,
    ) {
    }

    public function isInstalled(): bool
    {
        return is_file($this->lockPath);
    }

    public function lock(): bool
    {
        return @file_put_contents($this->lockPath, 'Đã cài đặt lúc '.date('d/m/Y H:i:s')."\n") !== false;
    }

    public function envPath(): string
    {
        return $this->envPath;
    }

    public function canWriteEnv(): bool
    {
        return is_file($this->envPath)
            ? is_writable($this->envPath)
            : is_writable(dirname($this->envPath));
    }

    public function writeEnv(string $contents): bool
    {
        return @file_put_contents($this->envPath, $contents) !== false;
    }

    /**
     * Giữ lại khoá ứng dụng cũ nếu đã có, chưa có thì sinh khoá mới.
     */
    public function appKey(?string $current = null): string
    {
        if ($current && str_starts_with($current, 'base64:') && strlen($current) > 20) {
            return $current;
        }

        return 'base64:'.base64_encode(random_bytes(32));
    }

    /**
     * Dựng nội dung file .env từ thông tin người dùng nhập ở trang cài đặt.
     *
     * @param  array<string, mixed>  $data
     */
    public function buildEnv(array $data): string
    {
        $database = $data['db_connection'] === 'sqlite'
            ? implode("\n", [
                'DB_CONNECTION=sqlite',
                'DB_DATABASE='.$this->quote($data['db_database']),
            ])
            : implode("\n", [
                'DB_CONNECTION=mysql',
                'DB_HOST='.$this->quote($data['db_host']),
                'DB_PORT='.$this->quote((string) ($data['db_port'] ?? 3306)),
                'DB_DATABASE='.$this->quote($data['db_database']),
                'DB_USERNAME='.$this->quote($data['db_username']),
                'DB_PASSWORD='.$this->quote((string) ($data['db_password'] ?? '')),
            ]);

        return <<<ENV
            APP_NAME={$this->quote($data['app_name'])}
            APP_ENV=production
            APP_KEY={$data['app_key']}
            APP_DEBUG=false
            APP_URL={$this->quote($data['app_url'])}

            APP_LOCALE=vi
            APP_FALLBACK_LOCALE=en
            APP_TIMEZONE=Asia/Ho_Chi_Minh

            {$database}

            SESSION_DRIVER=file
            SESSION_LIFETIME=120
            CACHE_STORE=file
            QUEUE_CONNECTION=sync

            LOG_CHANNEL=stack
            LOG_LEVEL=error

            WORK_START_TIME=08:30
            ATTENDANCE_TIMEZONE=Asia/Ho_Chi_Minh
            ICANDOIT_ENABLED=false
            ICANDOIT_DRIVER=null

            ENV;
    }

    /**
     * Bọc giá trị trong nháy kép để không vỡ file khi có dấu cách hoặc ký tự lạ.
     */
    private function quote(string $value): string
    {
        return '"'.str_replace(['\\', '"'], ['\\\\', '\"'], $value).'"';
    }
}
