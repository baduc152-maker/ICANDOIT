<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse as BaseStreamedResponse;

/**
 * Quản lý danh sách đăng ký tư vấn nhận từ website trung tâm.
 */
class ConsultationAdminController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status');

        $query = Consultation::query()->latest();

        if ($status && array_key_exists($status, Consultation::statuses())) {
            $query->where('status', $status);
        }

        return view('admin.consultations', [
            'records' => $query->paginate(30)->withQueryString(),
            'status' => $status,
            'summary' => Consultation::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }

    public function update(Request $request, Consultation $consultation): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Consultation::statuses()))],
            'staff_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $consultation->fill($data);

        if ($consultation->status !== Consultation::STATUS_NEW && ! $consultation->contacted_at) {
            $consultation->contacted_at = now();
        }

        $consultation->save();

        return back()->with('status', 'Đã cập nhật yêu cầu tư vấn #'.$consultation->id);
    }

    public function export(): BaseStreamedResponse
    {
        $records = Consultation::query()->latest()->get();

        return response()->streamDownload(function () use ($records) {
            $out = fopen('php://output', 'w');
            // BOM để Excel đọc đúng tiếng Việt UTF-8.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Thời gian', 'Họ tên', 'Điện thoại', 'Email', 'Khoá quan tâm', 'Ghi chú của khách', 'Trạng thái', 'Ghi chú nội bộ']);

            foreach ($records as $r) {
                fputcsv($out, [
                    $r->id,
                    $r->created_at?->format('d/m/Y H:i'),
                    $r->name,
                    $r->phone,
                    $r->email,
                    $r->course,
                    $r->note,
                    $r->statusLabel(),
                    $r->staff_note,
                ]);
            }

            fclose($out);
        }, 'dang-ky-tu-van-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
