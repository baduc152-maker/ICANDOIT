@extends('layouts.app')

@section('title', 'Điểm danh')

@php
    $tz = config('icandoit.timezone');
    $hasCheckIn = $today && $today->check_in_at;
    $hasCheckOut = $today && $today->check_out_at;
@endphp

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    {{-- Thẻ điểm danh chính --}}
    <div class="lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold">Xin chào, {{ auth()->user()->name }}</h2>
                    <p class="text-sm text-slate-500">
                        Hôm nay {{ now($tz)->format('d/m/Y') }} · giờ vào chuẩn {{ $workStart }}
                    </p>
                </div>
                <div class="text-right">
                    <div id="clock" class="font-mono text-3xl font-bold text-indigo-600">--:--:--</div>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                {{-- Check-in --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="text-sm font-medium text-slate-500">Giờ vào</p>
                    <p class="mt-1 text-2xl font-bold">
                        {{ $hasCheckIn ? $today->check_in_at->timezone($tz)->format('H:i') : '—' }}
                    </p>
                    @if($hasCheckIn && $today->isLate())
                        <span class="mt-1 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">Đi muộn</span>
                    @elseif($hasCheckIn)
                        <span class="mt-1 inline-block rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">Đúng giờ</span>
                    @endif
                </div>
                {{-- Check-out --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="text-sm font-medium text-slate-500">Giờ ra</p>
                    <p class="mt-1 text-2xl font-bold">
                        {{ $hasCheckOut ? $today->check_out_at->timezone($tz)->format('H:i') : '—' }}
                    </p>
                    @if($today && $today->workedHours() !== null)
                        <span class="mt-1 inline-block text-xs text-slate-500">Đã làm {{ $today->workedHours() }} giờ</span>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                @if(!$hasCheckIn)
                    <form method="POST" action="{{ route('attendance.checkin') }}" class="flex-1">
                        @csrf
                        <button class="w-full rounded-xl bg-indigo-600 px-4 py-4 text-lg font-semibold text-white transition hover:bg-indigo-700">
                            🕘 Check-in
                        </button>
                    </form>
                @elseif(!$hasCheckOut)
                    <form method="POST" action="{{ route('attendance.checkout') }}" class="flex-1">
                        @csrf
                        <button class="w-full rounded-xl bg-rose-600 px-4 py-4 text-lg font-semibold text-white transition hover:bg-rose-700">
                            🏁 Check-out
                        </button>
                    </form>
                @else
                    <div class="flex-1 rounded-xl bg-emerald-50 px-4 py-4 text-center font-semibold text-emerald-700">
                        ✅ Bạn đã hoàn tất điểm danh hôm nay
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Lịch sử gần đây --}}
    <div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-semibold">7 ngày gần nhất</h3>
            <ul class="space-y-3">
                @forelse($recent as $r)
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">{{ $r->work_date->format('d/m') }}</span>
                        <span class="font-medium">
                            {{ $r->check_in_at?->timezone($tz)->format('H:i') ?? '—' }}
                            →
                            {{ $r->check_out_at?->timezone($tz)->format('H:i') ?? '—' }}
                        </span>
                        @if($r->isLate())
                            <span class="rounded-full bg-amber-100 px-2 text-xs text-amber-700">muộn</span>
                        @else
                            <span class="rounded-full bg-emerald-100 px-2 text-xs text-emerald-700">ok</span>
                        @endif
                    </li>
                @empty
                    <li class="text-sm text-slate-400">Chưa có dữ liệu.</li>
                @endforelse
            </ul>
            <a href="{{ route('attendance.history') }}" class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:underline">Xem tất cả →</a>
        </div>
    </div>
</div>

<script>
    // Đồng hồ thời gian thực (theo giờ trình duyệt).
    const clock = document.getElementById('clock');
    function tick() {
        const d = new Date();
        clock.textContent = d.toLocaleTimeString('vi-VN', { hour12: false });
    }
    tick();
    setInterval(tick, 1000);
</script>
@endsection
