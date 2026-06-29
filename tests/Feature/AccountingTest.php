<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use App\Services\AccountingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'employee_code' => 'AD999',
            'is_active' => true,
        ]);
    }

    private function employee(): User
    {
        return User::factory()->create([
            'role' => 'employee',
            'employee_code' => 'NV999',
            'is_active' => true,
        ]);
    }

    private function makeEnrollment(float $fee = 5_000_000, float $discount = 0): Enrollment
    {
        $student = Student::create(['code' => 'HV001', 'name' => 'Học viên Test', 'is_active' => true]);
        $course = Course::create(['code' => 'C01', 'name' => 'Khóa Test', 'fee' => $fee, 'is_active' => true]);

        return Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_on' => now()->toDateString(),
            'tuition_amount' => $fee,
            'discount' => $discount,
            'status' => Enrollment::STATUS_ACTIVE,
        ]);
    }

    public function test_employee_cannot_access_accounting(): void
    {
        $this->actingAs($this->employee())
            ->get(route('admin.accounting'))
            ->assertForbidden();
    }

    public function test_admin_can_view_accounting_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.accounting'))
            ->assertOk()
            ->assertSee('Tổng quan kế toán');
    }

    public function test_admin_can_create_income_transaction_with_generated_code(): void
    {
        $admin = $this->admin();
        $category = TransactionCategory::create(['name' => 'Học phí', 'type' => 'income']);

        $this->actingAs($admin)
            ->post(route('admin.transactions.store'), [
                'type' => 'income',
                'category_id' => $category->id,
                'amount' => 1_500_000,
                'occurred_on' => '2026-06-29',
                'payment_method' => 'cash',
                'description' => 'Thu học phí tháng 6',
            ])
            ->assertRedirect(route('admin.transactions.index'));

        $transaction = Transaction::firstOrFail();
        $this->assertSame('income', $transaction->type);
        $this->assertSame('PT-20260629-0001', $transaction->code);
        $this->assertSame($admin->id, $transaction->created_by);
    }

    public function test_transaction_codes_increment_per_day_and_type(): void
    {
        $service = app(AccountingService::class);

        $a = $service->record(['type' => 'income', 'amount' => 100, 'occurred_on' => '2026-06-29']);
        $b = $service->record(['type' => 'income', 'amount' => 200, 'occurred_on' => '2026-06-29']);
        $c = $service->record(['type' => 'expense', 'amount' => 300, 'occurred_on' => '2026-06-29']);

        $this->assertSame('PT-20260629-0001', $a->code);
        $this->assertSame('PT-20260629-0002', $b->code);
        $this->assertSame('PC-20260629-0001', $c->code);
    }

    public function test_recording_tuition_payment_updates_balance(): void
    {
        $enrollment = $this->makeEnrollment(fee: 5_000_000, discount: 500_000);
        $service = app(AccountingService::class);

        // Học phí thực thu = 4.500.000
        $this->assertSame(4_500_000.0, $enrollment->netTuition());

        $service->recordTuitionPayment($enrollment, 2_000_000);

        $enrollment->load('transactions');
        $this->assertSame(2_000_000.0, $enrollment->paidAmount());
        $this->assertSame(2_500_000.0, $enrollment->balance());
        $this->assertFalse($enrollment->isFullyPaid());

        $service->recordTuitionPayment($enrollment, 2_500_000);
        $enrollment->load('transactions');
        $this->assertSame(0.0, $enrollment->balance());
        $this->assertTrue($enrollment->fresh()->load('transactions')->isFullyPaid());
    }

    public function test_pay_endpoint_creates_linked_income_transaction(): void
    {
        $enrollment = $this->makeEnrollment();

        $this->actingAs($this->admin())
            ->post(route('admin.enrollments.pay', $enrollment), [
                'amount' => 1_000_000,
                'occurred_on' => '2026-06-29',
                'payment_method' => 'bank',
            ])
            ->assertRedirect(route('admin.enrollments.show', $enrollment));

        $transaction = Transaction::firstOrFail();
        $this->assertSame('income', $transaction->type);
        $this->assertSame($enrollment->id, $transaction->enrollment_id);
        $this->assertSame($enrollment->student_id, $transaction->student_id);
        $this->assertSame('1000000.00', $transaction->amount);
    }

    public function test_summary_computes_income_expense_profit(): void
    {
        $service = app(AccountingService::class);
        $service->record(['type' => 'income', 'amount' => 10_000_000, 'occurred_on' => '2026-06-10']);
        $service->record(['type' => 'expense', 'amount' => 4_000_000, 'occurred_on' => '2026-06-15']);
        // Ngoài khoảng — không được tính.
        $service->record(['type' => 'income', 'amount' => 9_999, 'occurred_on' => '2026-07-01']);

        $summary = $service->summary('2026-06-01', '2026-06-30');

        $this->assertSame(10_000_000.0, $summary['income']);
        $this->assertSame(4_000_000.0, $summary['expense']);
        $this->assertSame(6_000_000.0, $summary['profit']);
    }

    public function test_student_balance_aggregates_enrollments_and_payments(): void
    {
        $enrollment = $this->makeEnrollment(fee: 5_000_000);
        app(AccountingService::class)->recordTuitionPayment($enrollment, 3_000_000);

        $student = $enrollment->student->fresh()->load(['enrollments', 'transactions']);
        $this->assertSame(5_000_000.0, $student->totalTuition());
        $this->assertSame(3_000_000.0, $student->totalPaid());
        $this->assertSame(2_000_000.0, $student->balance());
    }

    public function test_admin_can_manage_students(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.students.store'), ['code' => 'HV100', 'name' => 'Nguyễn Văn A', 'is_active' => '1'])
            ->assertRedirect();

        $this->assertDatabaseHas('students', ['code' => 'HV100', 'name' => 'Nguyễn Văn A', 'is_active' => true]);
    }

    public function test_report_page_renders(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.accounting.report', ['from' => '2026-06-01', 'to' => '2026-06-30']))
            ->assertOk()
            ->assertSee('Báo cáo tài chính');
    }
}
