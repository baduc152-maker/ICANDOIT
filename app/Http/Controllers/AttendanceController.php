<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendance)
    {
    }

    public function checkIn(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $this->attendance->checkIn($request->user(), $request->ip(), $data['note'] ?? null);

        return back()->with('status', 'Check-in thành công lúc '.now(config('icandoit.timezone'))->format('H:i'));
    }

    public function checkOut(Request $request): RedirectResponse
    {
        $this->attendance->checkOut($request->user(), $request->ip());

        return back()->with('status', 'Check-out thành công lúc '.now(config('icandoit.timezone'))->format('H:i'));
    }

    public function history(Request $request): View
    {
        $records = $request->user()->attendances()
            ->orderByDesc('work_date')
            ->paginate(20);

        return view('attendance.history', [
            'records' => $records,
        ]);
    }
}
