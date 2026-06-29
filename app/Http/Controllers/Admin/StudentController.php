<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');

        $students = Student::query()
            ->with(['enrollments', 'transactions'])
            ->when($search, fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.students.form', [
            'student' => new Student(['is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $student = Student::create($this->validateData($request));

        return redirect()->route('admin.students.show', $student)
            ->with('status', 'Đã thêm học viên '.$student->name.'.');
    }

    public function show(Student $student): View
    {
        $student->load(['enrollments.course', 'enrollments.transactions', 'transactions.category']);

        return view('admin.students.show', [
            'student' => $student,
        ]);
    }

    public function edit(Student $student): View
    {
        return view('admin.students.form', [
            'student' => $student,
        ]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $student->update($this->validateData($request, $student));

        return redirect()->route('admin.students.show', $student)
            ->with('status', 'Đã cập nhật học viên '.$student->name.'.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $name = $student->name;
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('status', 'Đã xóa học viên '.$name.'.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request, ?Student $student = null): array
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('students', 'code')->ignore($student)],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
        ], [], [
            'code' => 'mã học viên',
            'name' => 'họ tên',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
