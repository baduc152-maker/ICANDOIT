<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Nghiệp vụ kế toán: sinh số phiếu, ghi nhận phiếu thu/chi và tổng hợp báo cáo.
 */
class AccountingService
{
    private function timezone(): string
    {
        return config('icandoit.timezone', 'Asia/Ho_Chi_Minh');
    }

    /**
     * Sinh số phiếu duy nhất theo ngày, ví dụ: PT-20260629-0001 (thu),
     * PC-20260629-0001 (chi). Khóa hàng để tránh trùng khi tạo đồng thời.
     */
    public function generateCode(string $type, ?Carbon $date = null): string
    {
        $date ??= now($this->timezone());
        $prefix = $type === Transaction::TYPE_INCOME ? 'PT' : 'PC';
        $likePrefix = $prefix.'-'.$date->format('Ymd').'-';

        $last = Transaction::query()
            ->where('type', $type)
            ->where('code', 'like', $likePrefix.'%')
            ->lockForUpdate()
            ->orderByDesc('code')
            ->value('code');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $likePrefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Tạo một phiếu thu / chi mới, tự sinh số phiếu nếu chưa có.
     *
     * @param  array<string, mixed>  $data
     */
    public function record(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $occurredOn = isset($data['occurred_on'])
                ? Carbon::parse($data['occurred_on'])
                : now($this->timezone());

            $data['occurred_on'] = $occurredOn->toDateString();
            $data['code'] ??= $this->generateCode($data['type'], $occurredOn);

            return Transaction::create($data);
        });
    }

    /**
     * Ghi nhận một khoản nộp học phí cho lần ghi danh: tạo phiếu thu gắn với
     * học viên và ghi danh tương ứng, dùng danh mục "Học phí" nếu có.
     *
     * @param  array<string, mixed>  $overrides
     */
    public function recordTuitionPayment(Enrollment $enrollment, float $amount, array $overrides = []): Transaction
    {
        $enrollment->loadMissing(['student', 'course']);

        $categoryId = TransactionCategory::query()
            ->income()
            ->where('name', 'Học phí')
            ->value('id');

        return $this->record(array_merge([
            'type' => Transaction::TYPE_INCOME,
            'category_id' => $categoryId,
            'amount' => $amount,
            'payment_method' => Transaction::METHOD_CASH,
            'description' => 'Thu học phí: '.$enrollment->student?->name.' — '.$enrollment->course?->name,
            'student_id' => $enrollment->student_id,
            'enrollment_id' => $enrollment->id,
        ], $overrides));
    }

    /**
     * Tổng hợp thu / chi / lợi nhuận trong khoảng ngày.
     *
     * @return array{income: float, expense: float, profit: float}
     */
    public function summary(string $from, string $to): array
    {
        $income = (float) Transaction::query()->income()->between($from, $to)->sum('amount');
        $expense = (float) Transaction::query()->expense()->between($from, $to)->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'profit' => round($income - $expense, 2),
        ];
    }

    /**
     * Tổng thu / chi gộp theo danh mục trong khoảng ngày.
     *
     * @return Collection<int, object{category: ?string, type: string, total: float}>
     */
    public function byCategory(string $from, string $to)
    {
        return Transaction::query()
            ->between($from, $to)
            ->leftJoin('transaction_categories', 'transactions.category_id', '=', 'transaction_categories.id')
            ->groupBy('transactions.type', 'transaction_categories.name')
            ->selectRaw('transactions.type as type, transaction_categories.name as category, SUM(transactions.amount) as total')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => (object) [
                'category' => $row->category,
                'type' => $row->type,
                'total' => (float) $row->total,
            ]);
    }

    /**
     * Doanh thu / chi phí theo từng tháng trong năm (12 phần tử).
     *
     * @return array<int, array{month: int, income: float, expense: float}>
     */
    public function monthlyTotals(int $year): array
    {
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = ['month' => $m, 'income' => 0.0, 'expense' => 0.0];
        }

        Transaction::query()
            ->whereYear('occurred_on', $year)
            ->get(['type', 'amount', 'occurred_on'])
            ->each(function (Transaction $t) use (&$months) {
                $key = $t->isIncome() ? 'income' : 'expense';
                $months[(int) $t->occurred_on->format('n')][$key] += (float) $t->amount;
            });

        return array_values($months);
    }
}
