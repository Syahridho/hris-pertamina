@extends('layouts.supervisor')
@section('title', 'Kehadiran Hari Ini')
@section('page-title', 'Kehadiran Hari Ini')

@section('content')
<style>
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .stat-icon.total    { background: rgba(96,165,250,0.1); color: var(--info); }
    .stat-icon.present  { background: rgba(16,185,129,0.1); color: var(--success); }
    .stat-icon.late     { background: rgba(245,158,11,0.1); color: var(--warning); }
    .stat-icon.absent   { background: rgba(239,68,68,0.1); color: var(--danger); }
    .stat-number {
        font-size: 24px;
        font-weight: 700;
        line-height: 1.1;
    }
    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .section-card {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 12px;
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .badge-count {
        font-size: 11px;
        background: var(--glass);
        border: 1px solid var(--border);
        color: var(--text-muted);
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
    }

    /* Table & List design */
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
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        letter-spacing: 0.05em;
    }
    .attendance-table td {
        padding: 16px;
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
    .manpower-email {
        font-size: 12px;
        color: var(--text-muted);
    }

    .status-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-present { background: rgba(16,185,129,0.12); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
    .status-late    { background: rgba(245,158,11,0.12); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
    .status-leave   { background: rgba(96,165,250,0.12); color: #60a5fa; border: 1px solid rgba(96,165,250,0.2); }

    .not-present-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px;
    }
    .not-present-card {
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .not-present-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: rgba(239,68,68,0.1);
        border: 1px solid rgba(239,68,68,0.2);
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }
    .not-present-name {
        font-weight: 600;
        font-size: 13px;
    }
    .not-present-role {
        font-size: 11px;
        color: var(--text-muted);
    }

    .empty-state {
        text-align: center;
        padding: 32px 16px;
        color: var(--text-muted);
        font-size: 14px;
    }
    .empty-state svg {
        width: 48px; height: 48px;
        color: var(--slate);
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .attendance-table th:nth-child(3),
        .attendance-table td:nth-child(3) {
            display: none;
        }
    }
</style>

{{-- Stats Grid --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon total">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ ($placement?->manpowers()->count() ?? 0) }}</div>
            <div class="stat-label">Total Manpower</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon present">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 20px; height: 20px;">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $attendances->where('status', 'present')->count() }}</div>
            <div class="stat-label">Hadir Tepat Waktu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon late">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $attendances->where('status', 'late')->count() }}</div>
            <div class="stat-label">Terlambat</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon absent">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $notPresent->count() }}</div>
            <div class="stat-label">Belum Absen</div>
        </div>
    </div>
</div>

{{-- Present List --}}
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <span>Sudah Hadir</span>
            <span class="badge-count">{{ $attendances->count() }} orang</span>
        </h2>
        <span style="font-size:12px;color:var(--text-muted)">Penempatan: <strong>{{ $placement?->name ?? 'Belum ada' }}</strong></span>
    </div>

    @if($attendances->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="10"/><path d="M8 12h8"/>
            </svg>
            <p>Belum ada manpower yang melakukan absensi hari ini.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="attendance-table">
                <thead>
                    <tr>
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
                            <td>
                                <div class="manpower-info">
                                    <div class="manpower-avatar">
                                        {{ strtoupper(substr($att->manpower->name ?? $att->manpower->username ?? 'M', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="manpower-name">{{ $att->manpower->name ?? $att->manpower->username }}</div>
                                        <div class="manpower-email">{{ $att->manpower->email }}</div>
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
                                    <span class="status-pill status-leave">{{ ucfirst($att->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Absent List --}}
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <span>Belum Absen / Tidak Hadir</span>
            <span class="badge-count" style="background:rgba(239,68,68,0.1);color:#ef4444;border-color:rgba(239,68,68,0.2)">{{ $notPresent->count() }} orang</span>
        </h2>
    </div>

    @if($notPresent->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <p>Semua manpower di penempatan ini sudah melakukan absensi hari ini.</p>
        </div>
    @else
        <div class="not-present-grid">
            @foreach($notPresent as $mp)
                <div class="not-present-card">
                    <div class="not-present-avatar">
                        {{ strtoupper(substr($mp->name ?? $mp->username ?? 'M', 0, 1)) }}
                    </div>
                    <div>
                        <div class="not-present-name">{{ $mp->name ?? $mp->username }}</div>
                        <div class="not-present-role">{{ $mp->email }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
