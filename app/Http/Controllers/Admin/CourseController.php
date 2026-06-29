<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->withCount('enrollments')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.courses.index', [
            'courses' => $courses,
        ]);
    }

    public function create(): View
    {
        return view('admin.courses.form', [
            'course' => new Course(['is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Course::create($this->validateData($request));

        return redirect()->route('admin.courses.index')
            ->with('status', 'Đã thêm khóa học.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.form', [
            'course' => $course,
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $course->update($this->validateData($request, $course));

        return redirect()->route('admin.courses.index')
            ->with('status', 'Đã cập nhật khóa học '.$course->name.'.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $name = $course->name;
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('status', 'Đã xóa khóa học '.$name.'.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request, ?Course $course = null): array
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('courses', 'code')->ignore($course)],
            'name' => ['required', 'string', 'max:255'],
            'fee' => ['required', 'numeric', 'min:0'],
            'sessions' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ], [], [
            'code' => 'mã khóa học',
            'name' => 'tên khóa học',
            'fee' => 'học phí',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
