@extends('layouts.manpower')
@section('title', 'Detail Pengajuan')
@section('page-title', 'Detail Pengajuan')

@section('content')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
        margin-bottom: 16px;
        font-weight: 500;
        transition: color 0.15s;
    }
    .back-link:hover {
        color: var(--text);
    }
    .back-link svg {
        width: 16px; height: 16px;
    }

    .detail-card {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .detail-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }
    .detail-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .detail-title-wrapper {
        flex: 1;
    }
    .detail-title {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.2;
    }
    .detail-sub {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
    }
    .info-label {
        color: var(--text-muted);
        font-weight: 500;
    }
    .info-value {
        font-weight: 600;
    }

    .status-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .status-pending  { background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .status-approved { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

    .desc-section {
        margin-bottom: 20px;
    }
    .section-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
    }
    .desc-box {
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px;
        font-size: 13px;
        line-height: 1.6;
        color: var(--text);
        white-space: pre-line;
    }

    .file-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        background: rgba(96, 165, 250, 0.1);
        border: 1px solid rgba(96, 165, 250, 0.2);
        border-radius: 12px;
        color: #60a5fa;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: background 0.15s;
    }
    .file-btn:active {
        background: rgba(96, 165, 250, 0.18);
    }
    .file-btn svg {
        width: 16px; height: 16px;
    }
</style>

<a href="{{ route('manpower.submissions.index') }}" class="back-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Kembali ke Daftar
</a>

@php
    $icons = [
        'cuti' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px; color: var(--blue);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        'sakit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px; color: var(--red);"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
        'izin' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px; color: var(--text-3);"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>'
    ];
    $colors = ['cuti' => 'rgba(0,61,165,0.1)', 'sakit' => 'rgba(232,25,44,0.1)', 'izin' => 'rgba(100,116,139,0.1)'];
@endphp

<div class="detail-card">
    <div class="detail-header">
        <div class="detail-icon" style="background:{{ $colors[$submission->type] ?? 'var(--glass)' }}">
            {!! $icons[$submission->type] ?? '' !!}
        </div>
        <div class="detail-title-wrapper">
            <div class="detail-title">{{ $submission->name }}</div>
            <div class="detail-sub">{{ $submission->typeLabelAttribute }}</div>
        </div>
    </div>

    <div class="info-list">
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="status-pill status-{{ $submission->status }}">{{ $submission->status }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Pengajuan</span>
            <span class="info-value">{{ $submission->created_at->isoFormat('D MMMM Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Supervisor Penyetuju</span>
            <span class="info-value">{{ $submission->supervisor->name ?? $submission->supervisor->username ?? 'Tidak Diketahui' }}</span>
        </div>
    </div>

    <div class="desc-section">
        <div class="section-label">Deskripsi & Catatan</div>
        <div class="desc-box">{{ $submission->description }}</div>
    </div>

    @if($submission->file)
        <a href="{{ Storage::url($submission->file) }}" target="_blank" class="file-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
            </svg>
            Lihat File Lampiran
        </a>
    @endif
</div>
@endsection
