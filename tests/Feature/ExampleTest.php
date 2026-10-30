<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Trang chủ là website công khai của trung tâm, khách chưa đăng nhập vẫn xem được.
     */
    public function test_home_page_is_public(): void
    {
        $this->get('/')->assertOk();
    }

    /**
     * Khu vực nội bộ vẫn yêu cầu đăng nhập.
     */
    public function test_internal_area_requires_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
