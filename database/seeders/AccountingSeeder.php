<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Services\AccountingService;
use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    /**
     * Dữ liệu kế toán mẫu cho trung tâm: danh mục, khóa học, học viên,
     * ghi danh và một số phiếu thu/chi để xem báo cáo ngay.
     */
    public function run(): void
    {
        $accounting = app(AccountingService::class);

        // Danh mục thu / chi.
        $incomeCategories = ['Học phí', 'Bán giáo trình', 'Thu khác'];
        $expenseCategories = ['Lương giáo viên', 'Tiền thuê mặt bằng', 'Điện nước', 'Marketing', 'Văn phòng phẩm', 'Chi khác'];

        foreach ($incomeCategories as $name) {
            TransactionCategory::updateOrCreate(['name' => $name, 'type' => TransactionCategory::TYPE_INCOME], ['is_active' => true]);
        }
        foreach ($expenseCategories as $name) {
            TransactionCategory::updateOrCreate(['name' => $name, 'type' => TransactionCategory::TYPE_EXPENSE], ['is_active' => true]);
        }

        // Khóa học.
        $courses = [
            ['IELTS-60', 'Luyện thi IELTS 6.0', 6_000_000, 36],
            ['GIAOTIEP-01', 'Tiếng Anh giao tiếp cơ bản', 3_500_000, 24],
            ['TREEM-01', 'Tiếng Anh thiếu nhi', 2_800_000, 20],
            ['TOEIC-01', 'Luyện thi TOEIC 600+', 4_500_000, 30],
        ];
        $courseModels = [];
        foreach ($courses as [$code, $name, $fee, $sessions]) {
            $courseModels[$code] = Course::updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'fee' => $fee, 'sessions' => $sessions, 'is_active' => true],
            );
        }

        // Học viên.
        $students = [
            ['HV001', 'Phạm Minh Khoa', '0901000001'],
            ['HV002', 'Nguyễn Thùy Linh', '0901000002'],
            ['HV003', 'Trần Gia Bảo', '0901000003'],
            ['HV004', 'Lê Khánh Vy', '0901000004'],
            ['HV005', 'Đỗ Quốc Hưng', '0901000005'],
        ];
        $studentModels = [];
        foreach ($students as [$code, $name, $phone]) {
            $studentModels[$code] = Student::updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'phone' => $phone, 'is_active' => true],
            );
        }

        $tz = config('icandoit.timezone');
        $today = now($tz);

        // Ghi danh + thu học phí (mỗi học viên một khóa, nộp một phần hoặc đủ).
        $plan = [
            ['HV001', 'IELTS-60', 0, 6_000_000],        // nộp đủ
            ['HV002', 'GIAOTIEP-01', 0, 2_000_000],     // nộp một phần
            ['HV003', 'TREEM-01', 300_000, 2_500_000],  // có giảm giá, nộp đủ
            ['HV004', 'TOEIC-01', 0, 2_000_000],        // nộp một phần
            ['HV005', 'IELTS-60', 500_000, 0],          // chưa nộp
        ];

        foreach ($plan as [$sCode, $cCode, $discount, $paid]) {
            $course = $courseModels[$cCode];
            $enrollment = Enrollment::updateOrCreate(
                ['student_id' => $studentModels[$sCode]->id, 'course_id' => $course->id],
                [
                    'enrolled_on' => $today->copy()->subDays(20)->toDateString(),
                    'tuition_amount' => $course->fee,
                    'discount' => $discount,
                    'status' => Enrollment::STATUS_ACTIVE,
                ],
            );

            if ($paid > 0 && $enrollment->transactions()->doesntExist()) {
                $accounting->recordTuitionPayment($enrollment, (float) $paid, [
                    'occurred_on' => $today->copy()->subDays(15)->toDateString(),
                ]);
            }
        }

        // Một vài phiếu chi vận hành trong tháng.
        $expenses = [
            ['Lương giáo viên', 18_000_000, 5],
            ['Tiền thuê mặt bằng', 12_000_000, 3],
            ['Điện nước', 2_200_000, 6],
            ['Marketing', 5_000_000, 10],
        ];

        foreach ($expenses as [$catName, $amount, $daysAgo]) {
            $category = TransactionCategory::expense()->where('name', $catName)->first();
            $occurredOn = $today->copy()->subDays($daysAgo);

            // Tránh tạo trùng khi seed lại.
            $exists = Transaction::expense()
                ->where('category_id', $category?->id)
                ->whereDate('occurred_on', $occurredOn->toDateString())
                ->where('amount', $amount)
                ->exists();

            if (! $exists) {
                $accounting->record([
                    'type' => Transaction::TYPE_EXPENSE,
                    'category_id' => $category?->id,
                    'amount' => $amount,
                    'occurred_on' => $occurredOn->toDateString(),
                    'payment_method' => Transaction::METHOD_BANK,
                    'description' => $catName.' tháng '.$today->format('m/Y'),
                ]);
            }
        }
    }
}
