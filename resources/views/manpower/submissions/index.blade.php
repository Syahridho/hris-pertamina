@extends('layouts.manpower')
@section('title', 'Riwayat Pengajuan')
@section('page-title', 'Pengajuan Izin')

@section('content')
<style>
    .page-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .page-title { font-size: 18px; font-weight: 700; }
    .btn-create {
        background: var(--red);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-create svg { width: 14px; height: 14px; }
    .sub-card {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 10px;
        color: var(--text);
        display: block;
        transition: border-color 0.2s;
    }
    .sub-card:hover { border-color: rgba(0,0,0,0.15); }
    .sub-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
    .sub-name { font-size: 15px; font-weight: 600; }
    .sub-type { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
    .sub-date { font-size: 12px; color: var(--text-muted); }
    .status-pill {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        flex-shrink: 0;
    }
    .status-pill.status-pending  { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .status-pill.status-approved { background: rgba(16,185,129,0.15); color: #10b981; }
    .status-pill.status-rejected { background: rgba(239,68,68,0.15);  color: #ef4444; }
    .btn-delete {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border-radius: 8px;
        padding: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }
    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.16);
    }
    .empty-state {
        text-align: center;
        padding: 48px 0;
        color: var(--text-muted);
    }
    .empty-icon { font-size: 48px; margin-bottom: 12px; }
    .empty-text { font-size: 14px; }
</style>

<div class="page-actions">
    <div class="page-title">Pengajuan Saya</div>
    <a href="{{ route('manpower.submissions.create') }}" class="btn-create">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Buat Baru
    </a>
</div>

@forelse($submissions as $sub)
@php
    $icons = [
        'cuti' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; display: inline-block; vertical-align: text-bottom; margin-right: 4px; color: var(--blue);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        'sakit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; display: inline-block; vertical-align: text-bottom; margin-right: 4px; color: var(--red);"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
        'izin' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; display: inline-block; vertical-align: text-bottom; margin-right: 4px; color: var(--text-3);"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>'
    ];
@endphp
<div class="sub-card">
    <div class="sub-header">
        <div>
            <div class="sub-name">
                <a href="{{ route('manpower.submissions.show', $sub) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                    {!! $icons[$sub->type] ?? '' !!} {{ $sub->name }}
                </a>
            </div>
            <div class="sub-type">{{ $sub->typeLabelAttribute }}</div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <span class="status-pill status-{{ $sub->status }}">
                {{ ucfirst($sub->status) }}
            </span>
            @if($sub->status === 'pending')
                <form class="delete-form" method="POST" action="{{ route('manpower.submissions.destroy', $sub) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" title="Hapus Pengajuan">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </form>
            @endif
        </div>
    </div>
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 12px; gap: 12px; flex-wrap: wrap;">
        <div class="sub-date">Diajukan {{ $sub->created_at->diffForHumans() }}</div>
        @if($sub->start_date)
            <div class="sub-date" style="font-weight: 600; color: var(--text-muted); display: inline-flex; align-items: center; gap: 6px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 12px; height: 12px; color: var(--slate);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $sub->formattedDateRangeAttribute }}
                <span style="background: rgba(0, 61, 165, 0.08); color: var(--blue); padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 700;">{{ $sub->total_days }} Hari</span>
            </div>
        @endif
    </div>
</div>
@empty
<div class="empty-state">
    <div class="empty-icon" style="color: var(--border-2); display: flex; justify-content: center; margin-bottom: 8px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 48px; height: 48px;">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
        </svg>
    </div>
    <div class="empty-text">Belum ada pengajuan</div>
    <a href="{{ route('manpower.submissions.create') }}" class="btn-create" style="margin-top:16px;display:inline-flex">
        Buat Pengajuan Pertama
    </a>
</div>
@endforelse

{{ $submissions->links() }}

<!-- Custom Confirmation Modal for Delete -->
<div id="deleteModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; width: 100%; max-width: 400px; padding: 24px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); text-align: left;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 8px;">Hapus Pengajuan</h3>
        <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus pengajuan izin ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button id="deleteCancelBtn" type="button" style="padding: 8px 16px; background: #ffffff; border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer;">Batal</button>
            <button id="deleteConfirmBtn" type="button" style="padding: 8px 16px; background: var(--red); border: none; border-radius: 8px; font-size: 13px; font-weight: 600; color: #ffffff; cursor: pointer;">Ya, Hapus</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteForms = document.querySelectorAll('.delete-form');
        const deleteModal = document.getElementById('deleteModal');
        const deleteCancelBtn = document.getElementById('deleteCancelBtn');
        const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
        
        let activeForm = null;

        deleteForms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                activeForm = form;
                deleteModal.style.display = 'flex';
            });
        });

        deleteCancelBtn.addEventListener('click', () => {
            deleteModal.style.display = 'none';
            activeForm = null;
        });

        deleteConfirmBtn.addEventListener('click', () => {
            if (activeForm) {
                activeForm.submit();
            }
        });
        
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && deleteModal.style.display === 'flex') {
                deleteModal.style.display = 'none';
                activeForm = null;
            }
        });
        
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.style.display = 'none';
                activeForm = null;
            }
        });
    });
</script>
@endpush
@endsection
