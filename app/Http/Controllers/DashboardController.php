<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly AttendanceService $attendance)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $today = $this->attendance->todayRecord($user);

        $recent = $user->attendances()
            ->orderByDesc('work_date')
            ->limit(7)
            ->get();

        return view('dashboard', [
            'today' => $today,
            'recent' => $recent,
            'workStart' => config('icandoit.work_start_time', '08:30'),
        ]);
    }
}
