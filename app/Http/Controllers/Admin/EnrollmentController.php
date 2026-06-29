<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Services\AccountingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function __construct(private readonly AccountingService $accounting) {}

    public function index(Request $request): View
    {
        $status = $request->input('status');

        $enrollments = Enrollment::query()
            ->with(['student', 'course', 'transactions'])
            ->when(in_array($status, [
                Enrollment::STATUS_ACTIVE,
                Enrollment::STATUS_COMPLETED,
                Enrollment::STATUS_CANCELLED,
            ], true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('enrolled_on')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.enrollments.index', [
            'enrollments' => $enrollments,
            'status' => $status,
        ]);
    }

    public function create(Request $request): View
    {
        $enrollment = new Enrollment([
            'enrolled_on' => now(config('icandoit.timezone'))->toDateString(),
            'status' => Enrollment::STATUS_ACTIVE,
            'discount' => 0,
            'student_id' => $request->input('student_id'),
        ]);

        return view('admin.enrollments.form', $this->formData($enrollment));
    }

    public function store(Request $request): RedirectResponse
    {
        $enrollment = Enrollment::create($this->validateData($request));

        return redirect()->route('admin.enrollments.show', $enrollment)
            ->with('status', 'Đã ghi danh học viên vào khóa học.');
    }

    public function show(Enrollment $enrollment): View
    {
        $enrollment->load(['student', 'course', 'transactions.category']);

        return view('admin.enrollments.show', [
            'enrollment' => $enrollment,
        ]);
    }

    public function edit(Enrollment $enrollment): View
    {
        return view('admin.enrollments.form', $this->formData($enrollment));
    }

    public function update(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $enrollment->update($this->validateData($request));

        return redirect()->route('admin.enrollments.show', $enrollment)
            ->with('status', 'Đã cập nhật ghi danh.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('status', 'Đã xóa ghi danh.');
    }

    /**
     * Ghi nhận một khoản nộp học phí cho lần ghi danh (tạo phiếu thu).
     */
    public function pay(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'occurred_on' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank,other'],
        ], [], [
            'amount' => 'số tiền',
            'occurred_on' => 'ngày thu',
            'payment_method' => 'hình thức thanh toán',
        ]);

        $transaction = $this->accounting->recordTuitionPayment(
            $enrollment,
            (float) $data['amount'],
            [
                'occurred_on' => $data['occurred_on'],
                'payment_method' => $data['payment_method'],
                'created_by' => $request->user()->id,
            ],
        );

        return redirect()->route('admin.enrollments.show', $enrollment)
            ->with('status', 'Đã thu học phí — phiếu '.$transaction->code.'.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Enrollment $enrollment): array
    {
        return [
            'enrollment' => $enrollment,
            'students' => Student::active()->orderBy('name')->get(),
            'courses' => Course::active()->orderBy('name')->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'enrolled_on' => ['required', 'date'],
            'tuition_amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,completed,cancelled'],
            'note' => ['nullable', 'string'],
        ], [], [
            'student_id' => 'học viên',
            'course_id' => 'khóa học',
            'enrolled_on' => 'ngày ghi danh',
            'tuition_amount' => 'học phí',
        ]);
    }
}
