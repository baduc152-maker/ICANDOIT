<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Services\AccountingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(private readonly AccountingService $accounting) {}

    public function index(Request $request): View
    {
        $tz = config('icandoit.timezone');
        $from = $request->input('from', now($tz)->startOfMonth()->toDateString());
        $to = $request->input('to', now($tz)->endOfMonth()->toDateString());
        $type = $request->input('type');

        $records = Transaction::query()
            ->with(['category', 'student'])
            ->between($from, $to)
            ->when(in_array($type, [Transaction::TYPE_INCOME, Transaction::TYPE_EXPENSE], true),
                fn ($q) => $q->where('type', $type))
            ->orderByDesc('occurred_on')
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.transactions.index', [
            'records' => $records,
            'from' => $from,
            'to' => $to,
            'type' => $type,
            'summary' => $this->accounting->summary($from, $to),
        ]);
    }

    public function create(Request $request): View
    {
        $transaction = new Transaction([
            'type' => $request->input('type', Transaction::TYPE_INCOME),
            'occurred_on' => now(config('icandoit.timezone'))->toDateString(),
            'payment_method' => Transaction::METHOD_CASH,
        ]);

        return view('admin.transactions.form', $this->formData($transaction));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['created_by'] = $request->user()->id;

        $transaction = $this->accounting->record($data);

        return redirect()->route('admin.transactions.index')
            ->with('status', 'Đã lập phiếu '.$transaction->code.'.');
    }

    public function edit(Transaction $transaction): View
    {
        return view('admin.transactions.form', $this->formData($transaction));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $transaction->update($this->validateData($request));

        return redirect()->route('admin.transactions.index')
            ->with('status', 'Đã cập nhật phiếu '.$transaction->code.'.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $code = $transaction->code;
        $transaction->delete();

        return back()->with('status', 'Đã xóa phiếu '.$code.'.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Transaction $transaction): array
    {
        return [
            'transaction' => $transaction,
            'categories' => TransactionCategory::active()->orderBy('type')->orderBy('name')->get(),
            'students' => Student::active()->orderBy('name')->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['nullable', 'exists:transaction_categories,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'occurred_on' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank,other'],
            'description' => ['nullable', 'string', 'max:255'],
            'student_id' => ['nullable', 'exists:students,id'],
            'enrollment_id' => ['nullable', 'exists:enrollments,id'],
        ], [], [
            'type' => 'loại phiếu',
            'amount' => 'số tiền',
            'occurred_on' => 'ngày',
            'payment_method' => 'hình thức thanh toán',
        ]);
    }
}
