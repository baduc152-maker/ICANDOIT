<?php

namespace App\Services\Icandoit;

use App\Models\Attendance;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Driver gọi REST API của ICANDOIT.
 *
 * Phần này là khung sẵn sàng cho ngày ICANDOIT công bố endpoint thật. Chỉ cần
 * điền ICANDOIT_BASE_URL / ICANDOIT_API_KEY trong .env, đặt ICANDOIT_DRIVER=http
 * và đối chiếu lại định dạng request/response với tài liệu API thực tế.
 */
class HttpIcandoitConnector implements IcandoitConnector
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(private readonly array $config)
    {
    }

    public function pushAttendance(Attendance $attendance): SyncResult
    {
        $baseUrl = $this->config['base_url'] ?? null;

        if (! $baseUrl) {
            return SyncResult::failure('Chưa cấu hình ICANDOIT_BASE_URL.');
        }

        $payload = (new AttendancePayload)->forAttendance($attendance);

        try {
            $response = Http::baseUrl($baseUrl)
                ->timeout((int) ($this->config['timeout'] ?? 15))
                ->withToken((string) ($this->config['api_key'] ?? ''))
                ->acceptJson()
                ->post($this->config['attendance_endpoint'] ?? '/api/attendance', $payload);

            if ($response->successful()) {
                return SyncResult::success(
                    reference: $response->json('id') ?? $response->json('reference'),
                    message: 'Đồng bộ ICANDOIT thành công.',
                );
            }

            return SyncResult::failure(
                "ICANDOIT trả về HTTP {$response->status()}: ".$response->body()
            );
        } catch (Throwable $e) {
            Log::error('[ICANDOIT] Lỗi gọi API điểm danh.', ['exception' => $e->getMessage()]);

            return SyncResult::failure('Không kết nối được ICANDOIT: '.$e->getMessage());
        }
    }
}
