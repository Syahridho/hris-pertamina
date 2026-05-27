@extends('layouts.manpower')
@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')

@section('content')
<style>
    .filter-section {
        margin-bottom: 16px;
    }
    .filter-row {
        display: flex;
        gap: 8px;
    }
    .filter-select {
        flex: 1;
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        color: var(--text);
        padding: 10px 12px;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
    }
    .filter-btn {
        background: var(--red);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0 16px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .history-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }
    .history-card {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px;
    }
    .card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .card-date {
        font-size: 14px;
        font-weight: 700;
    }
    .status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .badge-present { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .badge-late    { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .badge-absent  { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        background: var(--glass);
        padding: 10px;
        border-radius: 10px;
        text-align: center;
    }
    .grid-label {
        font-size: 10px;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 4px;
        font-weight: 600;
    }
    .grid-value {
        font-size: 13px;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        color: var(--text-muted);
    }
    .empty-state svg {
        width: 48px; height: 48px;
        margin-bottom: 12px;
        color: var(--slate);
    }

    .pagination-wrapper {
        margin-top: 16px;
    }
</style>

{{-- Filter Section --}}
<div class="filter-section">
    <form method="GET" action="{{ route('manpower.attendance.history') }}" class="filter-row">
        <select name="month" class="filter-select">
            <option value="">Semua Bulan</option>
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ request('month', \Carbon\Carbon::now()->month) == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                </option>
            @endfor
        </select>

        <select name="year" class="filter-select">
            <option value="">Semua Tahun</option>
            @for ($y = \Carbon\Carbon::now()->year; $y >= \Carbon\Carbon::now()->year - 2; $y--)
                <option value="{{ $y }}" {{ request('year', \Carbon\Carbon::now()->year) == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endfor
        </select>

        <button type="submit" class="filter-btn">
            Filter
        </button>
    </form>
</div>

{{-- History List --}}
<div class="history-list">
    @forelse($attendances as $att)
        <div class="history-card">
            <div class="card-top">
                <span class="card-date">{{ $att->date->isoFormat('dddd, D MMM Y') }}</span>
                @if($att->status === 'present')
                    <span class="status-badge badge-present">Hadir</span>
                @elseif($att->status === 'late')
                    <span class="status-badge badge-late">Terlambat</span>
                @else
                    <span class="status-badge badge-present">{{ ucfirst($att->status) }}</span>
                @endif
            </div>

            <div class="card-grid">
                <div>
                    <div class="grid-label">Masuk</div>
                    <div class="grid-value" style="color:#10b981">{{ $att->clock_in?->format('H:i') ?? '-' }}</div>
                </div>
                <div>
                    <div class="grid-label">Keluar</div>
                    <div class="grid-value" style="color:#f59e0b">{{ $att->clock_out?->format('H:i') ?? '-' }}</div>
                </div>
                <div>
                    <div class="grid-label">Durasi</div>
                    <div class="grid-value">
                        @if($att->clock_in && $att->clock_out)
                            {{ floor($att->clock_in->diffInMinutes($att->clock_out) / 60) }}j
                            {{ $att->clock_in->diffInMinutes($att->clock_out) % 60 }}m
                        @elseif($att->clock_in)
                            <span style="color:var(--text-muted);font-size:11px;font-style:italic;">Aktif</span>
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <p>Tidak ada riwayat absensi untuk periode ini.</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($attendances->hasPages())
    <div class="pagination-wrapper">
        {{ $attendances->withQueryString()->links() }}
    </div>
@endif

@endsection
