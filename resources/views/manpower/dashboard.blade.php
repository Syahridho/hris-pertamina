@extends('layouts.manpower')
@section('title', 'Dashboard')
@section('page-title', 'Beranda')

@section('content')
@php
    $now = \Carbon\Carbon::now();
    $greeting = $now->hour < 12 ? 'Selamat Pagi' : ($now->hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
@endphp

<style>
    .greeting-card {
        background: #E8192C;
        color: #ffffff;
        border-radius: 20px;
        padding: 24px 20px;
        margin-bottom: 16px;
        position: relative;
        overflow: hidden;
    }
    .greeting-card::before {
        content: '';
        position: absolute;
        right: -20px; top: -20px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .greeting-card::after {
        content: '';
        position: absolute;
        right: 20px; top: 40px;
        width: 70px; height: 70px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .greeting-text { font-size: 13px; opacity: 0.85; margin-bottom: 4px; }
    .greeting-name { font-size: 22px; font-weight: 800; letter-spacing: -0.03em; }
    .greeting-meta { font-size: 12px; opacity: 0.7; margin-top: 8px; }

    .today-status {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 16px;
    }
    .status-box {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
        text-align: center;
    }
    .status-box .status-label { font-size: 11px; color: var(--text-muted); margin-bottom: 6px; }
    .status-box .status-time { font-size: 20px; font-weight: 700; letter-spacing: -0.03em; }
    .status-box .status-badge {
        display: inline-block;
        margin-top: 4px;
        padding: 2px 8px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-present { background: rgba(16,185,129,0.15); color: #10b981; }
    .badge-late    { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .badge-none    { background: var(--glass); color: var(--slate); }

    .clock-btn {
        width: 100%;
        padding: 16px;
        border-radius: 16px;
        border: none;
        font-family: inherit;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        transition: transform 0.15s, box-shadow 0.15s;
        margin-bottom: 16px;
    }
    .clock-btn:active { transform: scale(0.98); }
    .clock-btn-in {
        background: #10b981;
        color: white;
        box-shadow: 0 8px 24px rgba(16,185,129,0.15);
    }
    .clock-btn-out {
        background: #f59e0b;
        color: white;
        box-shadow: 0 8px 24px rgba(245,158,11,0.15);
    }
    .clock-btn-done {
        background: var(--navy-card);
        border: 1px solid var(--border);
        color: var(--text-muted);
        cursor: default;
    }
    .clock-btn svg { width: 20px; height: 20px; }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 10px;
    }

    .stat-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 16px;
    }
    .stat-box {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 10px;
        text-align: center;
    }
    .stat-num { font-size: 24px; font-weight: 800; }
    .stat-lbl { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
    .stat-present .stat-num { color: #10b981; }
    .stat-late    .stat-num { color: #f59e0b; }
    .stat-absent  .stat-num { color: #ef4444; }

    .submission-item {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: var(--text);
        transition: border-color 0.2s;
    }
    .submission-item:hover { border-color: rgba(0,0,0,0.15); }
    .sub-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }
    .sub-name { font-size: 14px; font-weight: 600; }
    .sub-meta { font-size: 12px; color: var(--text-muted); }
    .sub-status {
        margin-left: auto;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        flex-shrink: 0;
    }
    .sub-pending  { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .sub-approved { background: rgba(16,185,129,0.15); color: #10b981; }
    .sub-rejected { background: rgba(239,68,68,0.15);  color: #ef4444; }
</style>

{{-- Greeting Card --}}
<div class="greeting-card">
    <div class="greeting-text">{{ $greeting }},</div>
    <div class="greeting-name">{{ $manpower->username }}</div>
    <div class="greeting-meta">
        {{ $today->isoFormat('dddd, D MMMM Y') }} &bull; {{ $manpower->placement?->name ?? '-' }}
    </div>
</div>

{{-- Today Clock Status --}}
<div class="today-status">
    <div class="status-box">
        <div class="status-label">Clock In</div>
        <div class="status-time">
            {{ $todayAttendance?->clock_in?->format('H:i') ?? '--:--' }}
        </div>
        @if($todayAttendance?->clock_in)
            <span class="status-badge {{ $todayAttendance->status === 'late' ? 'badge-late' : 'badge-present' }}">
                {{ $todayAttendance->status === 'late' ? 'Terlambat' : 'Tepat Waktu' }}
            </span>
        @else
            <span class="status-badge badge-none">Belum Absen</span>
        @endif
    </div>
    <div class="status-box">
        <div class="status-label">Clock Out</div>
        <div class="status-time">
            {{ $todayAttendance?->clock_out?->format('H:i') ?? '--:--' }}
        </div>
        @if($todayAttendance?->clock_out)
            <span class="status-badge badge-present">Selesai</span>
        @else
            <span class="status-badge badge-none">Belum Pulang</span>
        @endif
    </div>
</div>

{{-- Clock In/Out CTA Button --}}
@if(!$todayAttendance?->clock_in)
    <a href="{{ route('manpower.clock') }}" class="clock-btn clock-btn-in">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
        Mulai Absen — Clock In
    </a>
@elseif(!$todayAttendance?->clock_out)
    <a href="{{ route('manpower.clock') }}" class="clock-btn clock-btn-out">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
        Selesai Kerja — Clock Out
    </a>
@else
    <div class="clock-btn clock-btn-done">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><polyline points="20 6 9 17 4 12"/>
        </svg>
        Absensi Selesai Hari Ini ✓
    </div>
@endif

{{-- Monthly Stats --}}
<p class="section-title">Rekap Bulan Ini</p>
<div class="stat-row">
    <div class="stat-box stat-present">
        <div class="stat-num">{{ $monthStats?->present ?? 0 }}</div>
        <div class="stat-lbl">Hadir</div>
    </div>
    <div class="stat-box stat-late">
        <div class="stat-num">{{ $monthStats?->late ?? 0 }}</div>
        <div class="stat-lbl">Terlambat</div>
    </div>
    <div class="stat-box stat-absent">
        <div class="stat-num">{{ $monthStats?->absent ?? 0 }}</div>
        <div class="stat-lbl">Tidak Hadir</div>
    </div>
</div>

{{-- Recent Submissions --}}
@if($recentSubmissions->count())
<p class="section-title">Pengajuan Terakhir</p>
@foreach($recentSubmissions as $sub)
@php
    $icons = [
        'cuti' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        'sakit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
        'izin' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>'
    ];
    $colors = ['cuti' => 'rgba(0,61,165,0.1)', 'sakit' => 'rgba(232,25,44,0.1)', 'izin' => 'rgba(100,116,139,0.1)'];
    $textColors = ['cuti' => 'var(--blue)', 'sakit' => 'var(--red)', 'izin' => 'var(--text-3)'];
@endphp
<a href="{{ route('manpower.submissions.show', $sub) }}" class="submission-item">
    <div class="sub-icon" style="background: {{ $colors[$sub->type] ?? 'var(--glass)' }}; color: {{ $textColors[$sub->type] ?? 'inherit' }}">
        {!! $icons[$sub->type] ?? '' !!}
    </div>
    <div>
        <div class="sub-name">{{ $sub->name }}</div>
        <div class="sub-meta">{{ $sub->typeLabelAttribute }} &bull; {{ $sub->created_at->diffForHumans() }}</div>
    </div>
    <span class="sub-status sub-{{ $sub->status }}">
        {{ ucfirst($sub->status) }}
    </span>
</a>
@endforeach
@endif
@endsection
