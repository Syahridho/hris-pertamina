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
        text-decoration: none;
        color: var(--text);
        display: block;
        transition: border-color 0.2s;
    }
    .sub-card:hover { border-color: rgba(255,255,255,0.15); }
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
    .status-pending  { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .status-approved { background: rgba(16,185,129,0.15); color: #10b981; }
    .status-rejected { background: rgba(239,68,68,0.15);  color: #ef4444; }
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
<a href="{{ route('manpower.submissions.show', $sub) }}" class="sub-card">
    <div class="sub-header">
        <div>
            <div class="sub-name">{!! $icons[$sub->type] ?? '' !!} {{ $sub->name }}</div>
            <div class="sub-type">{{ $sub->typeLabelAttribute }}</div>
        </div>
        <span class="status-pill status-{{ $sub->status }}">
            {{ ucfirst($sub->status) }}
        </span>
    </div>
    <div class="sub-date">Diajukan {{ $sub->created_at->diffForHumans() }}</div>
</a>
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
@endsection
