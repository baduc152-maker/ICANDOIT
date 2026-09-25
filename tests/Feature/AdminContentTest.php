<?php

namespace Tests\Feature;

use App\Models\SiteContent;
use App\Models\User;
use App\Services\SiteContentManager;
use App\Support\ContentField;
use App\Support\SiteContentSchema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    /**
     * Nội dung hiện tại của một danh sách, ở đúng định dạng mà biểu mẫu gửi lên.
     *
     * @return array<int, array<string, mixed>>
     */
    private function formRows(string $section): array
    {
        $schema = SiteContentSchema::section($section);
        $rows = [];

        foreach (app(SiteContentManager::class)->section($section) as $row) {
            $item = [];
            foreach ($schema['fields'] as $key => $field) {
                $item[$key] = ContentField::toInput($field, $row[$key] ?? null);
            }
            $rows[] = $item;
        }

        return $rows;
    }

    public function test_admin_sees_every_editable_section(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.content'))
            ->assertOk()
            ->assertSee('Khoá học')
            ->assertSee('Giảng viên')
            ->assertSee('Thông tin liên hệ');
    }

    public function test_employee_cannot_open_content_admin(): void
    {
        $employee = User::factory()->create(['role' => 'employee', 'is_active' => true]);

        $this->actingAs($employee)->get(route('admin.content'))->assertForbidden();
    }

    public function test_unknown_section_returns_404(): void
    {
        $this->actingAs($this->admin())->get(route('admin.content.edit', 'khong-ton-tai'))->assertNotFound();
    }

    public function test_edit_form_is_prefilled_with_current_content(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.content.edit', 'contact'))
            ->assertOk()
            ->assertSee(config('center.contact.hotline'));
    }

    public function test_admin_updates_contact_details_and_website_shows_them(): void
    {
        $payload = config('center.contact');
        $payload['hotline'] = '0987 111 222';
        $payload['address'] = 'Số 12, Đường Thật, Quận 1';

        $this->actingAs($this->admin())
            ->put(route('admin.content.update', 'contact'), ['fields' => $payload])
            ->assertRedirect(route('admin.content.edit', 'contact'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('site_contents', ['section' => 'contact']);

        $this->get(route('site.contact'))
            ->assertOk()
            ->assertSee('0987 111 222')
            ->assertSee('Số 12, Đường Thật, Quận 1');
    }

    public function test_admin_adds_a_new_course_visible_on_the_website(): void
    {
        $courses = $this->formRows('courses');
        $courses[] = [
            'name' => 'IELTS Speaking chuyên sâu',
            'slug' => 'ielts-speaking-chuyen-sau',
            'subtitle' => 'Luyện phản xạ và phát âm',
            'level' => 'IELTS 5.0+',
            'target' => 'Speaking 6.5+',
            'duration' => '2 tháng · 16 buổi',
            'sessions' => '2 buổi/tuần · 90 phút/buổi',
            'class_size' => '6 học viên',
            'tuition' => 'Liên hệ',
            'featured' => '1',
            'summary' => 'Khoá học tập trung vào kỹ năng nói.',
            'for_whom' => "Học viên yếu Speaking\nNgười đi làm cần giao tiếp",
            'outcomes' => "Phản xạ nhanh\nPhát âm chuẩn",
            'curriculum' => "Phần 1 :: Ngữ âm\nPhần 2 :: Luyện đề",
        ];

        $this->actingAs($this->admin())
            ->put(route('admin.content.update', 'courses'), ['items' => $courses])
            ->assertRedirect(route('admin.content.edit', 'courses'));

        $stored = SiteContent::where('section', 'courses')->firstOrFail()->payload;
        $new = end($stored);

        $this->assertSame(['Học viên yếu Speaking', 'Người đi làm cần giao tiếp'], $new['for_whom']);
        $this->assertSame([
            ['title' => 'Phần 1', 'text' => 'Ngữ âm'],
            ['title' => 'Phần 2', 'text' => 'Luyện đề'],
        ], $new['curriculum']);
        $this->assertTrue($new['featured']);

        $this->get(route('site.courses'))->assertOk()->assertSee('IELTS Speaking chuyên sâu');
        $this->get(route('site.course', 'ielts-speaking-chuyen-sau'))->assertOk()->assertSee('Phản xạ nhanh');
    }

    public function test_duplicated_slug_is_rejected(): void
    {
        $courses = $this->formRows('courses');
        $courses[1]['slug'] = $courses[0]['slug'];

        $this->actingAs($this->admin())
            ->from(route('admin.content.edit', 'courses'))
            ->put(route('admin.content.update', 'courses'), ['items' => $courses])
            ->assertSessionHasErrors('items');

        $this->assertDatabaseCount('site_contents', 0);
    }

    public function test_invalid_field_is_rejected(): void
    {
        $payload = config('center.contact');
        $payload['email'] = 'khong-phai-email';

        $this->actingAs($this->admin())
            ->from(route('admin.content.edit', 'contact'))
            ->put(route('admin.content.update', 'contact'), ['fields' => $payload])
            ->assertSessionHasErrors('fields.email');

        $this->assertDatabaseCount('site_contents', 0);
    }

    public function test_post_body_is_split_into_paragraphs(): void
    {
        $posts = $this->formRows('posts');
        $posts[0]['body'] = "Đoạn một.\n\nĐoạn hai.";

        $this->actingAs($this->admin())
            ->put(route('admin.content.update', 'posts'), ['items' => $posts])
            ->assertRedirect(route('admin.content.edit', 'posts'));

        $stored = SiteContent::where('section', 'posts')->firstOrFail()->payload;

        $this->assertSame(['Đoạn một.', 'Đoạn hai.'], $stored[0]['body']);
    }

    public function test_admin_can_restore_default_content(): void
    {
        $manager = app(SiteContentManager::class);
        $manager->save('stats', [['value' => '1', 'label' => 'Thử nghiệm']]);

        $this->get(route('site.home'))->assertSee('Thử nghiệm');

        $this->actingAs($this->admin())
            ->post(route('admin.content.reset', 'stats'))
            ->assertRedirect(route('admin.content.edit', 'stats'));

        $this->assertDatabaseCount('site_contents', 0);
        $this->get(route('site.home'))->assertSee($manager->defaults('stats')[0]['label']);
    }

    public function test_list_requires_at_least_one_item(): void
    {
        $this->actingAs($this->admin())
            ->from(route('admin.content.edit', 'teachers'))
            ->put(route('admin.content.update', 'teachers'), ['items' => []])
            ->assertSessionHasErrors('items');
    }
}
