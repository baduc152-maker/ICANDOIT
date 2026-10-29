<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * In ra câu lệnh SQL tạo tài khoản quản trị, dùng cho hosting không có SSH:
 * chạy lệnh này ở máy mình rồi dán kết quả vào phpMyAdmin của hosting.
 */
class MakeAdminSql extends Command
{
    protected $signature = 'icandoit:admin-sql
                            {email : Email đăng nhập}
                            {password : Mật khẩu}
                            {--name=Quản trị viên : Tên hiển thị}';

    protected $description = 'In câu lệnh SQL tạo tài khoản quản trị để dán vào phpMyAdmin';

    public function handle(): int
    {
        $email = trim($this->argument('email'));
        $password = $this->argument('password');
        $name = $this->option('name');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email không hợp lệ.');

            return self::FAILURE;
        }

        if (mb_strlen($password) < 8) {
            $this->error('Mật khẩu cần ít nhất 8 ký tự.');

            return self::FAILURE;
        }

        $sql = sprintf(
            "INSERT INTO `users` (`name`, `email`, `role`, `is_active`, `password`, `created_at`, `updated_at`)\n"
            ."VALUES ('%s', '%s', 'admin', 1, '%s', NOW(), NOW());",
            addslashes($name),
            addslashes($email),
            Hash::make($password),
        );

        $this->newLine();
        $this->line($sql);
        $this->newLine();
        $this->comment('Dán câu lệnh trên vào tab SQL của phpMyAdmin trên hosting, rồi bấm Thực hiện.');
        $this->comment('Đổi mật khẩu sau này: chạy lại lệnh với mật khẩu mới và dùng câu UPDATE:');
        $this->comment("UPDATE `users` SET `password` = '<chuỗi mã hoá mới>' WHERE `email` = '{$email}';");

        return self::SUCCESS;
    }
}
