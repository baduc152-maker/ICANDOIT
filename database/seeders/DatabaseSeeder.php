<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Tạo tài khoản mẫu để dùng thử ngay.
     */
    public function run(): void
    {
        // Quản trị viên.
        User::updateOrCreate(
            ['email' => 'admin@icandoit.test'],
            [
                'name' => 'Quản trị viên',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'employee_code' => 'AD001',
                'department' => 'Ban giám đốc',
                'is_active' => true,
            ]
        );

        // Một vài nhân viên mẫu.
        $employees = [
            ['Nguyễn Văn An', 'an@icandoit.test', 'NV001', 'Kinh doanh'],
            ['Trần Thị Bình', 'binh@icandoit.test', 'NV002', 'Kế toán'],
            ['Lê Văn Cường', 'cuong@icandoit.test', 'NV003', 'Kỹ thuật'],
        ];

        foreach ($employees as [$name, $email, $code, $dept]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'employee',
                    'employee_code' => $code,
                    'department' => $dept,
                    'is_active' => true,
                ]
            );
        }

        // Dữ liệu kế toán mẫu (danh mục, khóa học, học viên, thu/chi).
        $this->call(AccountingSeeder::class);
    }
}
