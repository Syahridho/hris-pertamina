@extends('layouts.supervisor')
@section('title', 'Dashboard Supervisor')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:24px;}
    .stat-card{background:var(--navy-card);border:1px solid var(--border);border-radius:16px;padding:20px;}
    .stat-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
    .stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;}
    .stat-icon svg{width:20px;height:20px;}
    .stat-number{font-size:32px;font-weight:800;letter-spacing:-0.04em;}
    .stat-label{font-size:13px;color:var(--text-muted);margin-top:4px;}
    .stat-sub{font-size:11px;color:var(--slate);margin-top:4px;}
    .icon-green{background:rgba(16,185,129,0.15);color:#10b981;}
    .icon-yellow{background:rgba(245,158,11,0.15);color:#f59e0b;}
    .icon-red{background:rgba(239,68,68,0.15);color:#ef4444;}
    .icon-blue{background:rgba(96,165,250,0.15);color:#60a5fa;}

    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;}
    @media(max-width:900px){.grid-2{grid-template-columns:1fr;}}

    .panel-card{background:var(--navy-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
    .panel-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
    .panel-title{font-size:15px;font-weight:700;}
    .panel-link{font-size:12px;color:var(--red);text-decoration:none;font-weight:600;}
    .panel-body{padding:4px 0;}

    .manpower-row{padding:12px 20px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--border);}
    .manpower-row:last-child{border-bottom:none;}
    .mp-avatar{width:36px;height:36px;background:var(--glass);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--text-muted);flex-shrink:0;}
    .mp-name{font-size:14px;font-weight:600;}
    .mp-time{font-size:12px;color:var(--text-muted);}
    .status-dot{width:8px;height:8px;border-radius:50%;margin-left:auto;flex-shrink:0;}
    .dot-green{background:#10b981;}
    .dot-yellow{background:#f59e0b;}
    .dot-gray{background:#64748b;}

    .sub-row{padding:12px 20px;display:flex;align-items:center;gap:10px;border-bottom:1px solid var(--border);}
    .sub-row:last-child{border-bottom:none;}
    .sub-type-badge{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;}
    .sub-info{flex:1;}
    .sub-name{font-size:13px;font-weight:600;}
    .sub-meta{font-size:11px;color:var(--text-muted);}
    .approve-btn,.reject-btn{padding:6px 12px;border:none;border-radius:8px;font-size:12px;font-weight:600;font-family:inherit;cursor:pointer;text-decoration:none;display:inline-block;}
    .approve-btn{background:rgba(16,185,129,0.15);color:#10b981;}
    .reject-btn{background:rgba(239,68,68,0.1);color:#ef4444;}

    .placement-banner{background:rgba(232,25,44,0.06);border:1px solid rgba(232,25,44,0.15);border-radius:16px;padding:20px;margin-bottom:24px;display:flex;align-items:center;gap:16px;}
    .placement-banner svg{width:28px;height:28px;color:var(--red);flex-shrink:0;}
    .placement-banner h3{font-size:16px;font-weight:700;margin-bottom:2px;}
    .placement-banner p{font-size:13px;color:var(--text-muted);}
</style>

{{-- Placement Banner --}}
<div class="placement-banner">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
    </svg>
    <div>
        <h3>{{ auth('supervisor')->user()?->placement?->name ?? 'Lokasi belum ditentukan' }}</h3>
        <p>{{ auth('supervisor')->user()?->project?->name ?? '-' }} &bull; {{ $today->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <span style="font-size:12px;color:var(--text-muted);font-weight:600">HADIR</span>
            <div class="stat-icon icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
        </div>
        <div class="stat-number" style="color:#10b981">{{ $todayStats['present'] }}</div>
        <div class="stat-label">Tepat waktu</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <span style="font-size:12px;color:var(--text-muted);font-weight:600">TERLAMBAT</span>
            <div class="stat-icon icon-yellow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <div class="stat-number" style="color:#f59e0b">{{ $todayStats['late'] }}</div>
        <div class="stat-label">Karyawan</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <span style="font-size:12px;color:var(--text-muted);font-weight:600">TIDAK HADIR</span>
            <div class="stat-icon icon-red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
        </div>
        <div class="stat-number" style="color:#ef4444">{{ max(0, $todayStats['absent']) }}</div>
        <div class="stat-label">Dari {{ $todayStats['total'] }} total</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <span style="font-size:12px;color:var(--text-muted);font-weight:600">PENDING</span>
            <div class="stat-icon icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
            </div>
        </div>
        <div class="stat-number" style="color:#60a5fa">{{ $pendingCount }}</div>
        <div class="stat-label">Menunggu approve</div>
    </div>
</div>

{{-- 2 column grid --}}
<div class="grid-2">
    {{-- Manpower list --}}
    <div class="panel-card">
        <div class="panel-header">
            <span class="panel-title" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; color: var(--blue);">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
                Kehadiran Hari Ini
            </span>
            <a href="{{ route('supervisor.attendance.index') }}" class="panel-link">Lihat semua →</a>
        </div>
        <div class="panel-body">
            @forelse($manpowers as $mp)
            @php $att = $mp->attendances->first(); @endphp
            <div class="manpower-row">
                <div class="mp-avatar">{{ strtoupper(substr($mp->username, 0, 1)) }}</div>
                <div>
                    <div class="mp-name">{{ $mp->username }}</div>
                    <div class="mp-time">
                        @if($att?->clock_in)
                            In: {{ $att->clock_in->format('H:i') }}
                            @if($att->clock_out) | Out: {{ $att->clock_out->format('H:i') }} @endif
                        @else
                            Belum absen
                        @endif
                    </div>
                </div>
                <div class="status-dot {{ $att?->clock_in ? ($att->status === 'late' ? 'dot-yellow' : 'dot-green') : 'dot-gray' }}"></div>
            </div>
            @empty
            <div style="padding:24px;text-align:center;color:var(--text-muted);font-size:13px">Tidak ada manpower di placement ini</div>
            @endforelse
        </div>
    </div>

    {{-- Pending Submissions --}}
    <div class="panel-card">
        <div class="panel-header">
            <span class="panel-title" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; color: var(--blue);">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Pengajuan Pending
            </span>
            <a href="{{ route('supervisor.submissions.index') }}" class="panel-link">Lihat semua →</a>
        </div>
        <div class="panel-body">
            @forelse($pendingSubmissions as $sub)
            @php 
                $labels = ['cuti'=>'Cuti','sakit'=>'Sakit','izin'=>'Izin']; 
                $bgColors = ['cuti'=>'rgba(0,61,165,0.1)','sakit'=>'rgba(232,25,44,0.1)','izin'=>'rgba(100,116,139,0.1)'];
                $textColors = ['cuti'=>'var(--blue)','sakit'=>'var(--red)','izin'=>'var(--text-muted)'];
            @endphp
            <div class="sub-row">
                <div class="sub-type-badge" style="background:{{ $bgColors[$sub->type] ?? '#f1f5f9' }}; color: {{ $textColors[$sub->type] ?? '#475569' }}; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 4px; text-transform: uppercase; border: 1px solid {{ $sub->type === 'cuti' ? 'rgba(0,61,165,0.2)' : ($sub->type === 'sakit' ? 'rgba(232,25,44,0.2)' : 'rgba(100,116,139,0.2)') }};">
                    {{ $labels[$sub->type] ?? 'Izin' }}
                </div>
                <div class="sub-info">
                    <div class="sub-name">
                        <a href="{{ route('supervisor.submissions.show', $sub) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            {{ $sub->name }}
                        </a>
                    </div>
                    <div class="sub-meta">{{ $sub->manpower?->username ?? '-' }} &bull; {{ $sub->created_at->diffForHumans() }}</div>
                </div>
                <div style="display: flex; gap: 6px; align-items: center;">
                    @if($sub->file)
                        <a href="{{ Storage::url($sub->file) }}" target="_blank" class="approve-btn" style="background: rgba(0, 61, 165, 0.08); color: var(--blue); border: 1px solid rgba(0, 61, 165, 0.15);">Surat</a>
                    @endif
                    <a href="{{ route('supervisor.submissions.show', $sub) }}" class="approve-btn">Review</a>
                </div>
            </div>
            @empty
            <div style="padding:24px;text-align:center;color:var(--text-muted);font-size:13px">Tidak ada pengajuan pending</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
