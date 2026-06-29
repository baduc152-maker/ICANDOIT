<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Transaction;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse as BaseStreamedResponse;

class AccountingController extends Controller
{
    public function __construct(private readonly AccountingService $accounting) {}

    /**
     * Bảng điều khiển kế toán: tổng quan thu/chi tháng hiện tại + biểu đồ năm.
     */
    public function dashboard(Request $request): View
    {
        $tz = config('icandoit.timezone');
        $today = now($tz);

        $monthFrom = $today->copy()->startOfMonth()->toDateString();
        $monthTo = $today->copy()->endOfMonth()->toDateString();

        $year = (int) $request->input('year', $today->year);

        $studentsWithDebt = Student::query()
            ->with(['enrollments', 'transactions'])
            ->get()
            ->filter(fn (Student $s) => $s->balance() > 0)
            ->sortByDesc(fn (Student $s) => $s->balance())
            ->values();

        return view('admin.accounting.dashboard', [
            'month' => $this->accounting->summary($monthFrom, $monthTo),
            'monthLabel' => $today->format('m/Y'),
            'year' => $year,
            'monthly' => $this->accounting->monthlyTotals($year),
            'recent' => Transaction::with(['category', 'student'])
                ->latest('occurred_on')->latest('id')->take(8)->get(),
            'studentsWithDebt' => $studentsWithDebt->take(10),
            'totalReceivable' => $studentsWithDebt->sum(fn (Student $s) => $s->balance()),
        ]);
    }

    /**
     * Báo cáo tài chính theo khoảng ngày, gộp theo danh mục.
     */
    public function report(Request $request): View
    {
        $tz = config('icandoit.timezone');
        $from = $request->input('from', now($tz)->startOfMonth()->toDateString());
        $to = $request->input('to', now($tz)->endOfMonth()->toDateString());

        $byCategory = $this->accounting->byCategory($from, $to);

        return view('admin.accounting.report', [
            'from' => $from,
            'to' => $to,
            'summary' => $this->accounting->summary($from, $to),
            'incomeRows' => $byCategory->where('type', Transaction::TYPE_INCOME)->values(),
            'expenseRows' => $byCategory->where('type', Transaction::TYPE_EXPENSE)->values(),
        ]);
    }

    /**
     * Xuất sổ quỹ trong khoảng ngày ra CSV (UTF-8, mở được bằng Excel).
     */
    public function export(Request $request): BaseStreamedResponse
    {
        $tz = config('icandoit.timezone');
        $from = $request->input('from', now($tz)->startOfMonth()->toDateString());
        $to = $request->input('to', now($tz)->endOfMonth()->toDateString());

        $records = Transaction::query()
            ->with(['category', 'student'])
            ->between($from, $to)
            ->orderBy('occurred_on')
            ->orderBy('id')
            ->get();

        $filename = "so-quy-{$from}_{$to}.csv";

        return response()->streamDownload(function () use ($records) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Số phiếu', 'Ngày', 'Loại', 'Danh mục', 'Học viên', 'Hình thức', 'Số tiền', 'Diễn giải']);

            foreach ($records as $r) {
                fputcsv($out, [
                    $r->code,
                    $r->occurred_on?->format('d/m/Y'),
                    $r->typeLabel(),
                    $r->category?->name,
                    $r->student?->name,
                    $r->paymentMethodLabel(),
                    (string) $r->amount,
                    $r->description,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
