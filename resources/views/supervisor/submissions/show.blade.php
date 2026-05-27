@extends('layouts.supervisor')
@section('title', 'Detail Pengajuan')
@section('page-title', 'Detail Pengajuan')

@section('content')
<style>
    .detail-card{background:var(--navy-card);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:16px;}
    .detail-header{display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border);}
    .detail-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;}
    .detail-title{font-size:20px;font-weight:700;margin-bottom:4px;}
    .detail-sub{font-size:13px;color:var(--text-muted);}
    .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;}
    @media(max-width:500px){.info-grid{grid-template-columns:1fr;}}
    .info-item{}
    .info-label{font-size:11px;font-weight:600;color:var(--slate);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;}
    .info-value{font-size:14px;font-weight:600;}
    .status-chip{display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;}
    .chip-pending{background:rgba(245,158,11,0.15);color:#f59e0b;}
    .chip-approved{background:rgba(16,185,129,0.15);color:#10b981;}
    .chip-rejected{background:rgba(239,68,68,0.1);color:#ef4444;}
    .desc-box{background:var(--glass);border:1px solid var(--border);border-radius:10px;padding:14px;font-size:14px;line-height:1.6;color:var(--text-muted);}
    .file-link{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;background:rgba(96,165,250,0.1);border:1px solid rgba(96,165,250,0.2);border-radius:10px;color:#60a5fa;text-decoration:none;font-size:14px;font-weight:600;margin-top:12px;}
    .file-link svg{width:16px;height:16px;}
    .action-section{display:flex;gap:10px;}
    .btn-approve-full{flex:1;padding:13px;background:linear-gradient(135deg,#10b981,#059669);color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;font-family:inherit;cursor:pointer;box-shadow:0 6px 20px rgba(16,185,129,0.25);}
    .reject-form{flex:1;}
    .reject-section{background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.15);border-radius:12px;padding:16px;}
    .reject-title{font-size:13px;font-weight:700;color:#ef4444;margin-bottom:10px;}
    .reject-input{width:100%;padding:10px 12px;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:8px;color:var(--text);font-family:inherit;font-size:13px;resize:none;outline:none;min-height:80px;}
    .reject-input:focus{border-color:#ef4444;}
    .btn-reject-full{width:100%;margin-top:10px;padding:11px;background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.25);color:#ef4444;border-radius:10px;font-size:14px;font-weight:700;font-family:inherit;cursor:pointer;}
    .back-link{display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-size:13px;margin-bottom:16px;}
    .back-link svg{width:14px;height:14px;}
    .back-link:hover{color:var(--text);}
</style>

<a href="{{ route('supervisor.submissions.index') }}" class="back-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Kembali ke daftar
</a>

@php 
    $icons = [
        'cuti' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px; color: var(--blue);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        'sakit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px; color: var(--red);"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
        'izin' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px; color: var(--text-3);"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>'
    ]; 
    $colors = ['cuti'=>'rgba(0,61,165,0.1)','sakit'=>'rgba(232,25,44,0.1)','izin'=>'rgba(100,116,139,0.1)']; 
@endphp

<div class="detail-card">
    <div class="detail-header">
        <div class="detail-icon" style="background:{{ $colors[$submission->type] ?? 'var(--glass)' }}">{!! $icons[$submission->type] ?? '' !!}</div>
        <div>
            <div class="detail-title">{{ $submission->name }}</div>
            <div class="detail-sub">{{ $submission->typeLabelAttribute }} &bull; {{ $submission->created_at->isoFormat('D MMM Y, HH:mm') }}</div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-item"><div class="info-label">Pengaju</div><div class="info-value">{{ $submission->manpower?->username ?? '-' }}</div></div>
        <div class="info-item"><div class="info-label">Jenis</div><div class="info-value">{{ $submission->typeLabelAttribute }}</div></div>
        <div class="info-item"><div class="info-label">Status</div><div class="info-value"><span class="status-chip chip-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span></div></div>
        <div class="info-item"><div class="info-label">Tanggal Ajukan</div><div class="info-value">{{ $submission->created_at->isoFormat('D MMMM Y') }}</div></div>
    </div>

    <div class="info-label" style="margin-bottom:8px">Keterangan</div>
    <div class="desc-box">{{ $submission->description }}</div>

    @if($submission->file)
        <a href="{{ Storage::url($submission->file) }}" target="_blank" class="file-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Lihat Lampiran
        </a>
    @endif
</div>

@if($submission->status === 'pending')
<div class="action-section" style="flex-direction:column;gap:12px">
    <form id="approveForm" method="POST" action="{{ route('supervisor.submissions.approve', $submission) }}">
        @csrf
        <button type="submit" class="btn-approve-full" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px;"><polyline points="20 6 9 17 4 12"/></svg>
            Setujui Pengajuan
        </button>
    </form>

    <div class="reject-section">
        <div class="reject-title" style="display: inline-flex; align-items: center; gap: 8px; color: var(--red); font-weight: 700; font-size: 14px; margin-bottom: 12px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Tolak Pengajuan
        </div>
        <form id="rejectForm" method="POST" action="{{ route('supervisor.submissions.reject', $submission) }}">
            @csrf
            <textarea name="reason" class="reject-input" placeholder="Tuliskan alasan penolakan..." required></textarea>
            @error('reason')<div style="font-size:12px;color:#f87171;margin-top:4px">{{ $message }}</div>@enderror
            <button type="submit" class="btn-reject-full">Kirim Penolakan</button>
        </form>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; width: 100%; max-width: 400px; padding: 24px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); text-align: left;">
        <h3 id="modalTitle" style="font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 8px;">Konfirmasi</h3>
        <p id="modalMessage" style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">Apakah Anda yakin dengan tindakan ini?</p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button id="modalCancelBtn" type="button" style="padding: 8px 16px; background: #ffffff; border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer;">Batal</button>
            <button id="modalConfirmBtn" type="button" style="padding: 8px 16px; background: var(--red); border: none; border-radius: 8px; font-size: 13px; font-weight: 600; color: #ffffff; cursor: pointer;">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const approveForm = document.getElementById('approveForm');
        const rejectForm = document.getElementById('rejectForm');
        const confirmModal = document.getElementById('confirmModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const modalCancelBtn = document.getElementById('modalCancelBtn');
        const modalConfirmBtn = document.getElementById('modalConfirmBtn');
        
        let activeForm = null;

        function showConfirmModal(form, title, message, isApprove = true) {
            activeForm = form;
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            if (isApprove) {
                modalConfirmBtn.style.backgroundColor = 'var(--blue)';
            } else {
                modalConfirmBtn.style.backgroundColor = 'var(--red)';
            }
            confirmModal.style.display = 'flex';
        }

        if (approveForm) {
            approveForm.addEventListener('submit', (e) => {
                e.preventDefault();
                showConfirmModal(
                    approveForm, 
                    'Setujui Pengajuan', 
                    'Apakah Anda yakin ingin menyetujui pengajuan izin ini?', 
                    true
                );
            });
        }

        if (rejectForm) {
            rejectForm.addEventListener('submit', (e) => {
                e.preventDefault();
                showConfirmModal(
                    rejectForm, 
                    'Tolak Pengajuan', 
                    'Apakah Anda yakin ingin menolak pengajuan izin ini?', 
                    false
                );
            });
        }

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
@endif
@endsection
