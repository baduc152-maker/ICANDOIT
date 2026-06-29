<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AccountingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_accounting_get_pages_render(): void
    {
        $this->seed(AccountingSeeder::class);
        $admin = User::factory()->create(['role' => 'admin', 'employee_code' => 'AD1', 'is_active' => true]);

        $student = \App\Models\Student::first();
        $course = \App\Models\Course::first();
        $enrollment = \App\Models\Enrollment::first();
        $transaction = \App\Models\Transaction::first();

        $urls = [
            route('admin.accounting'),
            route('admin.accounting.report'),
            route('admin.transactions.index'),
            route('admin.transactions.create'),
            route('admin.transactions.edit', $transaction),
            route('admin.students.index'),
            route('admin.students.create'),
            route('admin.students.show', $student),
            route('admin.students.edit', $student),
            route('admin.courses.index'),
            route('admin.courses.create'),
            route('admin.courses.edit', $course),
            route('admin.enrollments.index'),
            route('admin.enrollments.create'),
            route('admin.enrollments.show', $enrollment),
            route('admin.enrollments.edit', $enrollment),
            route('admin.categories.index'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        // CSV export.
        $this->actingAs($admin)->get(route('admin.accounting.export'))->assertOk();
    }
}
