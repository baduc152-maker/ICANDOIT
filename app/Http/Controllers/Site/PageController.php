<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Các trang tĩnh của website trung tâm. Nội dung lấy từ config/center.php để
 * người quản trị cập nhật được mà không phải sửa giao diện.
 */
class PageController extends Controller
{
    public function home(): View
    {
        return view('site.home', [
            'featuredCourses' => $this->courseList()->where('featured', true)->take(3)->values(),
        ]);
    }

    public function about(): View
    {
        return view('site.about');
    }

    public function courses(): View
    {
        return view('site.courses', [
            'courses' => $this->courseList(),
        ]);
    }

    public function course(string $slug): View
    {
        $courses = $this->courseList();
        $course = $courses->firstWhere('slug', $slug);

        if (! $course) {
            throw new NotFoundHttpException('Không tìm thấy khoá học.');
        }

        return view('site.course', [
            'course' => $course,
            'related' => $courses->where('slug', '!=', $slug)->take(3)->values(),
        ]);
    }

    public function teachers(): View
    {
        return view('site.teachers', [
            'teachers' => config('center.teachers'),
        ]);
    }

    public function schedule(): View
    {
        return view('site.schedule', [
            'schedule' => config('center.schedule'),
        ]);
    }

    public function posts(): View
    {
        return view('site.posts', [
            'posts' => config('center.posts'),
        ]);
    }

    public function post(string $slug): View
    {
        $posts = collect(config('center.posts'));
        $post = $posts->firstWhere('slug', $slug);

        if (! $post) {
            throw new NotFoundHttpException('Không tìm thấy bài viết.');
        }

        return view('site.post', [
            'post' => $post,
            'related' => $posts->where('slug', '!=', $slug)->take(3)->values(),
        ]);
    }

    public function contact(): View
    {
        return view('site.contact');
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function courseList(): Collection
    {
        return collect(config('center.courses'));
    }
}
