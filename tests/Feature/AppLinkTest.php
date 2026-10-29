<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SiteContentManager;
use App\Support\AppLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppLinkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<int, array<string, string>>
     */
    private function links(): array
    {
        return [
            [
                'key' => 'homework',
                'label' => 'Bài tập về nhà',
                'url' => 'https://baitap.icandoit.edu.vn',
                'audience' => AppLink::PUBLIC,
                'description' => 'Ứng dụng giao và nộp bài tập về nhà.',
            ],
            [
                'key' => 'management',
                'label' => 'Quản trị trung tâm',
                'url' => 'https://giaoviec.icandoit.edu.vn',
                'audience' => AppLink::INTERNAL,
                'description' => 'Ứng dụng giao việc nội bộ.',
            ],
        ];
    }

    public function test_links_are_hidden_until_an_address_is_set(): void
    {
        $this->get(route('site.home'))
            ->assertOk()
            ->assertDontSee('Bài tập về nhà')
            ->assertDontSee('Quản trị trung tâm');
    }

    public function test_student_link_appears_on_the_website(): void
    {
        app(SiteContentManager::class)->save('apps', $this->links());

        $this->get(route('site.home'))
            ->assertOk()
            ->assertSee('Bài tập về nhà')
            ->assertSee('https://baitap.icandoit.edu.vn')
            ->assertSee('Quản trị trung tâm')
            ->assertSee('https://giaoviec.icandoit.edu.vn');
    }

    public function test_staff_link_appears_in_the_internal_area(): void
    {
        app(SiteContentManager::class)->save('apps', $this->links());
        $staff = User::factory()->create(['role' => 'employee', 'is_active' => true]);

        $this->actingAs($staff)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Quản trị trung tâm')
            ->assertSee('https://giaoviec.icandoit.edu.vn');
    }

    public function test_admin_can_set_the_addresses_from_the_content_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->put(route('admin.content.update', 'apps'), ['items' => $this->links()])
            ->assertRedirect(route('admin.content.edit', 'apps'));

        $this->assertDatabaseHas('site_contents', ['section' => 'apps']);

        $this->get(route('site.contact'))->assertOk()->assertSee('https://baitap.icandoit.edu.vn');
    }

    public function test_address_must_be_a_valid_url(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $links = $this->links();
        $links[0]['url'] = 'khong-phai-dia-chi';

        $this->actingAs($admin)
            ->from(route('admin.content.edit', 'apps'))
            ->put(route('admin.content.update', 'apps'), ['items' => $links])
            ->assertSessionHasErrors('items.0.url');

        $this->assertDatabaseCount('site_contents', 0);
    }

    public function test_helper_filters_by_audience(): void
    {
        config(['center.apps' => $this->links()]);

        $this->assertSame('homework', AppLink::for(AppLink::PUBLIC)->first()['key']);
        $this->assertSame('management', AppLink::for(AppLink::INTERNAL)->first()['key']);
        $this->assertNull(AppLink::find('khong-co'));
    }
}
