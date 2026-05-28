@extends('layouts.supervisor')
@section('title', 'Pengajuan Izin')
@section('page-title', 'Pengajuan Izin')

@section('content')
<style>
    .filter-bar{display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;}
    .filter-link{padding:7px 14px;border-radius:20px;font-size:13px;font-weight:600;text-decoration:none;color:var(--text-muted);background:var(--navy-card);border:1px solid var(--border);transition:all 0.15s;}
    .filter-link.active,.filter-link:hover{background:rgba(232,25,44,0.12);color:var(--red);border-color:rgba(232,25,44,0.25);}
    .stats-mini{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px;}
    .mini-stat{background:var(--navy-card);border:1px solid var(--border);border-radius:12px;padding:14px;text-align:center;}
    .mini-num{font-size:24px;font-weight:800;}
    .mini-lbl{font-size:11px;color:var(--text-muted);}
    .sub-table{background:var(--navy-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
    .sub-table-row{padding:16px 20px;display:flex;align-items:center;gap:14px;border-bottom:1px solid var(--border);transition:background 0.15s;}
    .sub-table-row:last-child{border-bottom:none;}
    .sub-table-row:hover{background:var(--glass);}
    .sub-icon-box{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
    .sub-info{flex:1;}
    .sub-name{font-size:14px;font-weight:600;}
    .sub-meta{font-size:12px;color:var(--text-muted);margin-top:2px;}
    .sub-actions{display:flex;gap:6px;flex-shrink:0;}
    .btn-sm{padding:6px 12px;border:none;border-radius:8px;font-size:12px;font-weight:600;font-family:inherit;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:4px;}
    .btn-approve{background:rgba(16,185,129,0.15);color:#10b981;}
    .btn-reject{background:rgba(239,68,68,0.1);color:#ef4444;}
    .btn-view{background:var(--glass);border:1px solid var(--border);color:var(--text-muted);}
    .status-chip{padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;flex-shrink:0;}
    .chip-pending{background:rgba(245,158,11,0.15);color:#f59e0b;}
    .chip-approved{background:rgba(16,185,129,0.15);color:#10b981;}
    .chip-rejected{background:rgba(239,68,68,0.1);color:#ef4444;}
</style>

{{-- Mini stats --}}
<div class="stats-mini">
    <div class="mini-stat">
        <div class="mini-num" style="color:#f59e0b">{{ $stats['pending'] }}</div>
        <div class="mini-lbl">Pending</div>
    </div>
    <div class="mini-stat">
        <div class="mini-num" style="color:#10b981">{{ $stats['approved'] }}</div>
        <div class="mini-lbl">Disetujui</div>
    </div>
    <div class="mini-stat">
        <div class="mini-num" style="color:#ef4444">{{ $stats['rejected'] }}</div>
        <div class="mini-lbl">Ditolak</div>
    </div>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <a href="{{ route('supervisor.submissions.index') }}" class="filter-link {{ !request('status') ? 'active' : '' }}">Semua</a>
    <a href="{{ route('supervisor.submissions.index', ['status' => 'pending']) }}" class="filter-link {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('supervisor.submissions.index', ['status' => 'approved']) }}" class="filter-link {{ request('status') === 'approved' ? 'active' : '' }}">Disetujui</a>
    <a href="{{ route('supervisor.submissions.index', ['status' => 'rejected']) }}" class="filter-link {{ request('status') === 'rejected' ? 'active' : '' }}">Ditolak</a>
</div>

{{-- List --}}
<div class="sub-table">
    @forelse($submissions as $sub)
    @php 
        $icons = [
            'cuti' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; color: var(--blue);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
            'sakit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; color: var(--red);"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
            'izin' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; color: var(--text-3);"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>'
        ]; 
        $colors = ['cuti'=>'rgba(0,61,165,0.1)','sakit'=>'rgba(232,25,44,0.1)','izin'=>'rgba(100,116,139,0.1)']; 
    @endphp
    <div class="sub-table-row">
        <div class="sub-icon-box" style="background:{{ $colors[$sub->type] ?? 'var(--glass)' }}">{!! $icons[$sub->type] ?? '' !!}</div>
        <div class="sub-info">
            <div class="sub-name">
                <a href="{{ route('supervisor.submissions.show', $sub) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                    {{ $sub->name }}
                </a>
            </div>
            <div class="sub-meta">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 12px; height: 12px; display: inline-block; vertical-align: middle; margin-right: 2px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                {{ $sub->manpower?->username ?? '-' }}
                &bull; {{ ucfirst($sub->type) }}
                &bull; {{ $sub->created_at->diffForHumans() }}
                @if($sub->start_date)
                    <span style="display: flex; align-items: center; gap: 4px; margin-top: 4px; font-weight: 600; color: var(--text-muted);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 12px; height: 12px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $sub->formattedDateRangeAttribute }}
                        <span style="background: rgba(0, 61, 165, 0.08); color: var(--blue); padding: 1px 6px; border-radius: 10px; font-size: 10px; font-weight: 700;">{{ $sub->total_days }} Hari</span>
                    </span>
                @endif
            </div>
        </div>
        <span class="status-chip chip-{{ $sub->status }}">{{ ucfirst($sub->status) }}</span>
        <div class="sub-actions">
            @if($sub->file)
                <a href="{{ Storage::url($sub->file) }}" target="_blank" class="btn-sm btn-view" style="color: var(--blue); border-color: rgba(0, 61, 165, 0.2); background: rgba(0, 61, 165, 0.05);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 12px; height: 12px;"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    Lihat Surat
                </a>
            @endif
            @if($sub->status === 'pending')
                <form class="approve-inline-form" method="POST" action="{{ route('supervisor.submissions.approve', $sub) }}" style="display:inline">
                    @csrf
                    <button class="btn-sm btn-approve">✓ Approve</button>
                </form>
                <a href="{{ route('supervisor.submissions.show', $sub) }}" class="btn-sm btn-reject">✕ Reject</a>
            @else
                <a href="{{ route('supervisor.submissions.show', $sub) }}" class="btn-sm btn-view">Detail</a>
            @endif
        </div>
    </div>
    @empty
    <div style="padding:40px;text-align:center;color:var(--text-muted)">
        <div style="color: var(--border-2); display: flex; justify-content: center; margin-bottom: 8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 48px; height: 48px;">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
            </svg>
        </div>
        <div>Tidak ada pengajuan</div>
    </div>
    @endforelse
</div>
<div style="margin-top:16px">{{ $submissions->withQueryString()->links() }}</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; width: 100%; max-width: 400px; padding: 24px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); text-align: left;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 8px;">Setujui Pengajuan</h3>
        <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">Apakah Anda yakin ingin menyetujui pengajuan izin ini?</p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button id="modalCancelBtn" type="button" style="padding: 8px 16px; background: #ffffff; border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer;">Batal</button>
            <button id="modalConfirmBtn" type="button" style="padding: 8px 16px; background: var(--blue); border: none; border-radius: 8px; font-size: 13px; font-weight: 600; color: #ffffff; cursor: pointer;">Ya, Setujui</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inlineForms = document.querySelectorAll('.approve-inline-form');
        const confirmModal = document.getElementById('confirmModal');
        const modalCancelBtn = document.getElementById('modalCancelBtn');
        const modalConfirmBtn = document.getElementById('modalConfirmBtn');
        
        let activeForm = null;

        inlineForms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                activeForm = form;
                confirmModal.style.display = 'flex';
            });
        });

        modalCancelBtn.addEventListener('click', () => {
            confirmModal.style.display = 'none';
            activeForm = null;
        });

        modalConfirmBtn.addEventListener('click', () => {
            if (activeForm) {
                activeForm.submit();
            }
        });
        
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && confirmModal.style.display === 'flex') {
                confirmModal.style.display = 'none';
                activeForm = null;
            }
        });
        
        confirmModal.addEventListener('click', (e) => {
            if (e.target === confirmModal) {
                confirmModal.style.display = 'none';
                activeForm = null;
            }
        });
    });
</script>
@endsection
