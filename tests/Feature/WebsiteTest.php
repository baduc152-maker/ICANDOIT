<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_reachable(): void
    {
        $urls = [
            route('site.home'),
            route('site.about'),
            route('site.courses'),
            route('site.teachers'),
            route('site.schedule'),
            route('site.posts'),
            route('site.contact'),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_home_page_shows_brand_and_featured_courses(): void
    {
        $response = $this->get(route('site.home'));

        $response->assertOk()
            ->assertSee('I CAN DO IT ENGLISH')
            ->assertSee('Tiếng Anh Học Thuật')
            ->assertSee(config('center.courses.0.name'));
    }

    public function test_course_detail_page_renders(): void
    {
        $course = config('center.courses.0');

        $this->get(route('site.course', $course['slug']))
            ->assertOk()
            ->assertSee($course['name'])
            ->assertSee($course['outcomes'][0]);
    }

    public function test_unknown_course_returns_404(): void
    {
        $this->get(route('site.course', 'khong-ton-tai'))->assertNotFound();
    }

    public function test_post_detail_page_renders(): void
    {
        $post = config('center.posts.0');

        $this->get(route('site.post', $post['slug']))
            ->assertOk()
            ->assertSee($post['title']);
    }

    public function test_sitemap_lists_public_pages(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('site.courses'))
            ->assertSee(route('site.course', config('center.courses.0.slug')));
    }

    public function test_visitor_can_submit_consultation_request(): void
    {
        $response = $this->from(route('site.contact'))->post(route('site.consult'), [
            'name' => 'Nguyễn Văn A',
            'phone' => '0912345678',
            'email' => 'a@example.com',
            'course' => 'IELTS Foundation',
            'note' => 'Đang ở mức 5.0, cần 6.5.',
        ]);

        $response->assertRedirect(route('site.contact').'#dang-ky');
        $response->assertSessionHas('consult_success');

        $this->assertDatabaseHas('consultations', [
            'name' => 'Nguyễn Văn A',
            'phone' => '0912345678',
            'course' => 'IELTS Foundation',
            'status' => Consultation::STATUS_NEW,
        ]);
    }

    public function test_consultation_request_requires_name_and_phone(): void
    {
        $this->from(route('site.contact'))
            ->post(route('site.consult'), ['name' => '', 'phone' => ''])
            ->assertSessionHasErrors(['name', 'phone']);

        $this->assertDatabaseCount('consultations', 0);
    }

    public function test_honeypot_field_blocks_spam_submission(): void
    {
        $this->from(route('site.contact'))
            ->post(route('site.consult'), [
                'name' => 'Bot',
                'phone' => '0912345678',
                'website' => 'https://spam.example',
            ])
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('consultations', 0);
    }

    public function test_admin_can_list_and_update_consultations(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $consultation = Consultation::create([
            'name' => 'Trần Thị B',
            'phone' => '0987654321',
            'course' => 'IELTS 5.5 – 6.5',
            'status' => Consultation::STATUS_NEW,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.consultations'))
            ->assertOk()
            ->assertSee('Trần Thị B');

        $this->actingAs($admin)
            ->post(route('admin.consultations.update', $consultation), [
                'status' => Consultation::STATUS_CONTACTED,
                'staff_note' => 'Đã gọi, hẹn test đầu vào thứ 7.',
            ])
            ->assertRedirect();

        $consultation->refresh();
        $this->assertSame(Consultation::STATUS_CONTACTED, $consultation->status);
        $this->assertNotNull($consultation->contacted_at);
    }

    public function test_employee_cannot_access_consultation_admin(): void
    {
        $employee = User::factory()->create(['role' => 'employee', 'is_active' => true]);

        $this->actingAs($employee)
            ->get(route('admin.consultations'))
            ->assertForbidden();
    }
}
