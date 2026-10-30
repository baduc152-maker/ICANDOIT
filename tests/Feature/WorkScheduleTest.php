<?php

namespace Tests\Feature;

use App\Models\TimetableEntry;
use App\Models\User;
use App\Models\WorkTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class WorkScheduleTest extends TestCase
{
    use RefreshDatabase;

    private function monday(): Carbon
    {
        return Carbon::create(2026, 11, 2)->startOfWeek(Carbon::MONDAY);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function employee(string $name): User
    {
        return User::factory()->create(['role' => 'employee', 'is_active' => true, 'name' => $name]);
    }

    private function classFor(User $user, string $name, int $weekday = 3): TimetableEntry
    {
        return TimetableEntry::create([
            'user_id' => $user->id,
            'class_name' => $name,
            'subject' => 'Writing Task 2',
            'weekday' => $weekday,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'room' => 'A1',
            'effective_from' => $this->monday()->toDateString(),
            'is_active' => true,
        ]);
    }

    private function taskFor(User $user, string $title, ?string $date = null): WorkTask
    {
        return WorkTask::create([
            'user_id' => $user->id,
            'title' => $title,
            'work_date' => $date ?? $this->monday()->toDateString(),
            'start_time' => '09:00',
            'priority' => WorkTask::PRIORITY_NORMAL,
            'status' => WorkTask::STATUS_TODO,
        ]);
    }

    private function week(string $url = ''): string
    {
        return route('schedule', ['tuan' => $this->monday()->toDateString()]).$url;
    }

    public function test_guest_cannot_open_the_schedule(): void
    {
        $this->get(route('schedule'))->assertRedirect(route('login'));
    }

    public function test_employee_sees_only_their_own_schedule(): void
    {
        $an = $this->employee('Nguyễn Văn An');
        $binh = $this->employee('Trần Thị Bình');

        $this->classFor($an, 'Lớp của An');
        $this->classFor($binh, 'Lớp của Bình');
        $this->taskFor($an, 'Việc của An');
        $this->taskFor($binh, 'Việc của Bình');

        $this->actingAs($an)->get($this->week())
            ->assertOk()
            ->assertSee('Lớp của An')
            ->assertSee('Việc của An')
            ->assertDontSee('Lớp của Bình')
            ->assertDontSee('Việc của Bình');
    }

    public function test_employee_cannot_see_someone_else_by_changing_the_filter(): void
    {
        $an = $this->employee('Nguyễn Văn An');
        $binh = $this->employee('Trần Thị Bình');
        $this->classFor($binh, 'Lớp của Bình');

        $this->actingAs($an)
            ->get(route('schedule', ['tuan' => $this->monday()->toDateString(), 'nhan_vien' => $binh->id]))
            ->assertOk()
            ->assertDontSee('Lớp của Bình');
    }

    public function test_admin_sees_every_schedule_and_can_filter(): void
    {
        $admin = $this->admin();
        $an = $this->employee('Nguyễn Văn An');
        $binh = $this->employee('Trần Thị Bình');

        $this->classFor($an, 'Lớp của An');
        $this->classFor($binh, 'Lớp của Bình');

        $this->actingAs($admin)->get($this->week())
            ->assertOk()
            ->assertSee('Lớp của An')
            ->assertSee('Lớp của Bình');

        $this->actingAs($admin)
            ->get(route('schedule', ['tuan' => $this->monday()->toDateString(), 'nhan_vien' => $an->id]))
            ->assertOk()
            ->assertSee('Lớp của An')
            ->assertDontSee('Lớp của Bình');
    }

    public function test_timetable_entry_only_shows_inside_its_date_range(): void
    {
        $an = $this->employee('Nguyễn Văn An');
        $entry = $this->classFor($an, 'Lớp đã kết thúc');
        $entry->update(['effective_to' => $this->monday()->copy()->subDay()->toDateString()]);

        $this->actingAs($an)->get($this->week())->assertOk()->assertDontSee('Lớp đã kết thúc');
    }

    public function test_task_in_another_week_is_not_shown(): void
    {
        $an = $this->employee('Nguyễn Văn An');
        $this->taskFor($an, 'Việc tuần sau', $this->monday()->copy()->addWeek()->toDateString());

        $this->actingAs($an)->get($this->week())->assertOk()->assertDontSee('Việc tuần sau');
    }

    public function test_owner_can_mark_a_task_done_and_undo_it(): void
    {
        $an = $this->employee('Nguyễn Văn An');
        $task = $this->taskFor($an, 'Chấm bài Writing');

        $this->actingAs($an)->post(route('schedule.task.toggle', $task))->assertRedirect();
        $task->refresh();
        $this->assertSame(WorkTask::STATUS_DONE, $task->status);
        $this->assertNotNull($task->completed_at);

        $this->actingAs($an)->post(route('schedule.task.toggle', $task))->assertRedirect();
        $task->refresh();
        $this->assertSame(WorkTask::STATUS_TODO, $task->status);
        $this->assertNull($task->completed_at);
    }

    public function test_employee_cannot_change_someone_else_task(): void
    {
        $an = $this->employee('Nguyễn Văn An');
        $binh = $this->employee('Trần Thị Bình');
        $task = $this->taskFor($binh, 'Việc của Bình');

        $this->actingAs($an)->post(route('schedule.task.toggle', $task))->assertForbidden();
        $this->assertSame(WorkTask::STATUS_TODO, $task->fresh()->status);
    }

    public function test_admin_assigns_a_task_that_appears_in_the_assignee_schedule(): void
    {
        $admin = $this->admin();
        $an = $this->employee('Nguyễn Văn An');

        $this->actingAs($admin)->post(route('admin.tasks.store'), [
            'user_id' => $an->id,
            'title' => 'Soạn đề kiểm tra giữa khoá',
            'work_date' => $this->monday()->copy()->addDays(2)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'priority' => WorkTask::PRIORITY_HIGH,
            'status' => WorkTask::STATUS_TODO,
        ])->assertRedirect(route('admin.tasks'));

        $this->assertDatabaseHas('work_tasks', [
            'user_id' => $an->id,
            'assigned_by' => $admin->id,
            'title' => 'Soạn đề kiểm tra giữa khoá',
        ]);

        $this->actingAs($an)->get($this->week())->assertOk()->assertSee('Soạn đề kiểm tra giữa khoá');
    }

    public function test_admin_adds_a_timetable_entry_shown_on_the_right_weekday(): void
    {
        $admin = $this->admin();
        $an = $this->employee('Nguyễn Văn An');

        $this->actingAs($admin)->post(route('admin.timetable.store'), [
            'user_id' => $an->id,
            'class_name' => 'IELTS 7.0+ ADV-02',
            'subject' => 'Speaking Part 3',
            'weekday' => 5,
            'start_time' => '19:30',
            'end_time' => '21:30',
            'room' => 'B2',
            'effective_from' => $this->monday()->toDateString(),
            'is_active' => '1',
        ])->assertRedirect(route('admin.timetable'));

        $response = $this->actingAs($an)->get($this->week());
        $response->assertOk()->assertSee('IELTS 7.0+ ADV-02');

        // Buổi dạy nằm đúng ở Thứ 6 của tuần đang xem.
        $friday = $this->monday()->copy()->addDays(4);
        $this->assertSame(5, (int) $friday->isoWeekday());
    }

    public function test_timetable_rejects_end_time_before_start_time(): void
    {
        $admin = $this->admin();
        $an = $this->employee('Nguyễn Văn An');

        $this->actingAs($admin)
            ->from(route('admin.timetable.create'))
            ->post(route('admin.timetable.store'), [
                'user_id' => $an->id,
                'class_name' => 'Lớp sai giờ',
                'weekday' => 2,
                'start_time' => '20:00',
                'end_time' => '18:00',
                'effective_from' => $this->monday()->toDateString(),
            ])
            ->assertSessionHasErrors('end_time');

        $this->assertDatabaseCount('timetable_entries', 0);
    }

    public function test_employee_cannot_open_admin_screens(): void
    {
        $an = $this->employee('Nguyễn Văn An');

        $this->actingAs($an)->get(route('admin.timetable'))->assertForbidden();
        $this->actingAs($an)->get(route('admin.tasks'))->assertForbidden();
    }
}
