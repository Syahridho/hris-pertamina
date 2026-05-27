@extends('layouts.supervisor')
@section('title', 'Riwayat Kehadiran')
@section('page-title', 'Riwayat Kehadiran')

@section('content')
<style>
    .filter-card {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
    }
    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        align-items: flex-end;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .form-control {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text);
        padding: 10px 14px;
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: border-color 0.15s;
    }
    .form-control:focus {
        border-color: var(--red);
    }
    .filter-actions {
        display: flex;
        gap: 8px;
    }
    .btn {
        padding: 10px 18px;
        border-radius: 10px;
        border: none;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: opacity 0.15s;
        text-decoration: none;
    }
    .btn:active {
        opacity: 0.85;
    }
    .btn-submit {
        background: var(--red);
        color: white;
        flex: 1;
    }
    .btn-reset {
        background: var(--glass);
        color: var(--text-muted);
        border: 1px solid var(--border);
    }

    .table-card {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }
    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .attendance-table th {
        font-size: 12px;
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 600;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        letter-spacing: 0.05em;
    }
    .attendance-table td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
        vertical-align: middle;
    }
    .attendance-table tr:last-child td {
        border-bottom: none;
    }

    .manpower-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .manpower-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: var(--glass);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
    }
    .manpower-name {
        font-weight: 600;
    }

    .status-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .status-present { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .status-late    { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .status-absent  { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: var(--text-muted);
    }
    .empty-state svg {
        width: 48px; height: 48px;
        color: var(--slate);
        margin-bottom: 12px;
    }

    /* Laravel Tailwind Pagination styling reset/overrides if any */
    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--border);
    }
</style>

{{-- Filter Card --}}
<div class="filter-card">
    <form method="GET" action="{{ route('supervisor.attendance.history') }}" class="filter-form">
        <div class="form-group">
            <label for="manpower_id">Manpower</label>
            <select name="manpower_id" id="manpower_id" class="form-control">
                <option value="">Semua Manpower</option>
                @foreach($manpowers as $mp)
                    <option value="{{ $mp->id }}" {{ request('manpower_id') == $mp->id ? 'selected' : '' }}>
                        {{ $mp->name ?? $mp->username }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date_from">Dari Tanggal</label>
            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="date_to">Sampai Tanggal</label>
            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Tepat Waktu</option>
                <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-submit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Cari
            </button>
            <a href="{{ route('supervisor.attendance.history') }}" class="btn btn-reset">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- Data Table Card --}}
<div class="table-card">
    @if($attendances->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <p>Tidak ditemukan data riwayat kehadiran yang sesuai dengan filter.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Manpower</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Durasi Kerja</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $att)
                        <tr>
                            <td style="font-weight: 500;">
                                {{ $att->date->isoFormat('D MMM Y') }}
                            </td>
                            <td>
                                <div class="manpower-info">
                                    <div class="manpower-avatar">
                                        {{ strtoupper(substr($att->manpower->name ?? $att->manpower->username ?? 'M', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="manpower-name">{{ $att->manpower->name ?? $att->manpower->username }}</div>
                                        <div style="font-size:11px;color:var(--text-muted)">{{ $att->manpower->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="color:#10b981;font-weight:600;">
                                {{ $att->clock_in?->format('H:i') ?? '-' }}
                            </td>
                            <td style="color:#f59e0b;font-weight:600;">
                                {{ $att->clock_out?->format('H:i') ?? '-' }}
                            </td>
                            <td>
                                @if($att->clock_in && $att->clock_out)
                                    {{ floor($att->clock_in->diffInMinutes($att->clock_out) / 60) }}j
                                    {{ $att->clock_in->diffInMinutes($att->clock_out) % 60 }}m
                                @elseif($att->clock_in)
                                    <span style="color:var(--text-muted);font-style:italic;">Sedang Bekerja</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($att->status === 'present')
                                    <span class="status-pill status-present">Tepat Waktu</span>
                                @elseif($att->status === 'late')
                                    <span class="status-pill status-late">Terlambat</span>
                                @else
                                    <span class="status-pill status-present">{{ ucfirst($att->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($attendances->hasPages())
            <div class="pagination-wrapper">
                {{ $attendances->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
