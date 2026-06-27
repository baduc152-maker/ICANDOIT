<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    private function employee(): User
    {
        return User::factory()->create([
            'role' => 'employee',
            'employee_code' => 'NV999',
            'is_active' => true,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_employee_can_check_in_and_check_out(): void
    {
        $user = $this->employee();

        $this->actingAs($user)
            ->post(route('attendance.checkin'))
            ->assertRedirect();

        $record = Attendance::where('user_id', $user->id)->firstOrFail();
        $this->assertNotNull($record->check_in_at);
        $this->assertNull($record->check_out_at);

        $this->actingAs($user)
            ->post(route('attendance.checkout'))
            ->assertRedirect();

        $this->assertNotNull($record->fresh()->check_out_at);
    }

    public function test_cannot_check_in_twice(): void
    {
        $user = $this->employee();

        $this->actingAs($user)->post(route('attendance.checkin'));
        $this->actingAs($user)
            ->post(route('attendance.checkin'))
            ->assertSessionHasErrors('attendance');

        $this->assertSame(1, Attendance::where('user_id', $user->id)->count());
    }

    public function test_cannot_check_out_without_check_in(): void
    {
        $user = $this->employee();

        $this->actingAs($user)
            ->post(route('attendance.checkout'))
            ->assertSessionHasErrors('attendance');
    }

    public function test_employee_cannot_access_admin_area(): void
    {
        $this->actingAs($this->employee())
            ->get(route('admin.attendance'))
            ->assertForbidden();
    }

    public function test_admin_can_view_attendance_board(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->get(route('admin.attendance'))
            ->assertOk()
            ->assertSee('Bảng điểm danh');
    }
}
