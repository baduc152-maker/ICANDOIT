<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Tiếp nhận yêu cầu tư vấn gửi từ các biểu mẫu trên website.
 */
class ConsultationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\s().-]{8,20}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'course' => ['nullable', 'string', 'max:150'],
            'note' => ['nullable', 'string', 'max:1000'],
            // Bẫy spam: bot thường điền mọi ô, người dùng thật để trống.
            'website' => ['prohibited'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.min' => 'Họ và tên quá ngắn.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'email.email' => 'Email không hợp lệ.',
            'note.max' => 'Nội dung ghi chú tối đa 1000 ký tự.',
            'website.prohibited' => 'Yêu cầu không hợp lệ.',
        ]);

        $consultation = Consultation::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'course' => $data['course'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => Consultation::STATUS_NEW,
            'source' => $request->headers->get('referer'),
            'ip' => $request->ip(),
        ]);

        Log::info('Nhận yêu cầu tư vấn mới từ website', [
            'consultation_id' => $consultation->id,
            'course' => $consultation->course,
        ]);

        return back()
            ->with('consult_success', 'Cảm ơn bạn! Trung tâm đã nhận thông tin và sẽ liên hệ trong vòng 24 giờ làm việc.')
            ->withFragment('dang-ky');
    }
}
