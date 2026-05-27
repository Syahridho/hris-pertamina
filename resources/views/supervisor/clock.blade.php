@extends('layouts.supervisor')
@section('title', 'Absensi GPS')
@section('page-title', 'Absensi Saya')

@section('content')
<style>
    .gps-container {
        max-width: 500px;
        margin: 0 auto;
    }
    .gps-card {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px 24px;
        text-align: center;
        margin-bottom: 20px;
    }
    .location-ring {
        width: 120px; height: 120px;
        margin: 0 auto 24px;
        position: relative;
    }
    .ring-outer {
        width: 120px; height: 120px;
        border-radius: 50%;
        background: var(--glass);
        border: 2px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        animation: pulse-ring 2s ease-in-out infinite;
    }
    .ring-outer.valid   { border-color: #10b981; background: rgba(16,185,129,0.1); }
    .ring-outer.invalid { border-color: #ef4444; background: rgba(239,68,68,0.1); }
    .ring-outer.loading { border-color: #f59e0b; }
    @keyframes pulse-ring {
        0%, 100% { transform: scale(1); }
        50%       { transform: scale(1.04); }
    }
    .ring-icon { font-size: 40px; }

    .gps-status-text {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .gps-sub-text {
        font-size: 13px;
        color: var(--text-muted);
    }
    .distance-badge {
        display: inline-block;
        margin-top: 14px;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .distance-ok  { background: rgba(16,185,129,0.15); color: #10b981; }
    .distance-far { background: rgba(239,68,68,0.15);  color: #ef4444; }

    .placement-info {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 14px;
    }
    .placement-info svg { width: 24px; height: 24px; color: var(--red); flex-shrink: 0; }

    .action-btn {
        width: 100%;
        padding: 16px;
        border-radius: 16px;
        border: none;
        font-family: inherit;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 12px;
        transition: transform 0.15s, opacity 0.15s;
    }
    .action-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .action-btn:not(:disabled):active { transform: scale(0.98); }
    .btn-clock-in  { background: #10b981; color: white; }
    .btn-clock-out { background: #f59e0b; color: white; }
    .btn-refresh   { background: var(--glass); border: 1px solid var(--border); color: var(--text-muted); padding: 12px 16px; }
    .action-btn svg { width: 20px; height: 20px; }

    /* Swipe button styles */
    .swipe-container {
        position: relative;
        width: 100%;
        height: 60px;
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 30px;
        overflow: hidden;
        margin-bottom: 16px;
        user-select: none;
        -webkit-user-select: none;
    }
    .swipe-bg {
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 0;
        pointer-events: none;
    }
    .swipe-container.swipe-in .swipe-bg {
        background: rgba(16, 185, 129, 0.1);
    }
    .swipe-container.swipe-out .swipe-bg {
        background: rgba(245, 158, 11, 0.1);
    }
    .swipe-text {
        position: absolute;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        pointer-events: none;
        z-index: 1;
        transition: opacity 0.2s;
    }
    .swipe-handle {
        position: absolute;
        left: 4px; top: 4px;
        width: 52px; height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: grab;
        z-index: 2;
        color: white;
        transition: transform 0.1s ease-out;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .swipe-handle:active {
        cursor: grabbing;
    }
    .swipe-handle svg {
        width: 20px; height: 20px;
    }
    .swipe-container.disabled {
        opacity: 0.5;
        pointer-events: none;
    }
    .swipe-container.disabled .swipe-handle {
        cursor: not-allowed;
    }
    .action-btn-done {
        width: 100%;
        padding: 16px;
        border-radius: 16px;
        background: rgba(16,185,129,0.1);
        border: 1px solid rgba(16,185,129,0.3);
        color: #10b981;
        font-weight: 700;
        text-align: center;
        font-size: 15px;
        margin-bottom: 12px;
    }
    @keyframes spin {
        100% { transform: rotate(360deg); }
    }

    .attendance-record {
        background: var(--navy-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
    }
    .record-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 12px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 8px;
    }
    .record-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
    }
    .record-row:last-child { border-bottom: none; }
    .record-label { color: var(--text-muted); }
    .record-value { font-weight: 600; }

    .result-toast {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: var(--navy-mid);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px 20px;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        max-width: 350px;
        width: 90%;
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
        z-index: 200;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }
    .result-toast.show { transform: translateX(-50%) translateY(0); }
    .result-toast.success { border-color: rgba(16,185,129,0.4); }
    .result-toast.error   { border-color: rgba(239,68,68,0.4); }
</style>

<div class="gps-container">
    {{-- Placement Info --}}
    <div class="placement-info">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
        </svg>
        <div>
            <div style="font-weight:600; font-size: 15px;">{{ $placement?->name ?? 'Lokasi belum dikonfigurasi' }}</div>
            <div style="font-size:12px;color:var(--text-muted)">Maksimal Radius: {{ $placement?->radius ?? 0 }} meter</div>
        </div>
    </div>

    {{-- GPS Status Card --}}
    <div class="gps-card">
        <div class="location-ring">
            <div class="ring-outer" id="gpsRing">
                <span class="ring-icon" id="gpsIcon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px;">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/>
                        <circle cx="12" cy="12" r="2" fill="currentColor"/>
                    </svg>
                </span>
            </div>
        </div>
        <div class="gps-status-text" id="gpsStatusText">Mendeteksi lokasi Anda...</div>
        <div class="gps-sub-text" id="gpsSubText">Pastikan GPS diaktifkan di perangkat Anda</div>
        <div id="distanceBadge" style="display:none" class="distance-badge"></div>
    </div>

    {{-- Actions --}}
    @if(!$todayAttendance?->clock_in)
        <div class="swipe-container swipe-in disabled" id="swipeContainer">
            <div class="swipe-bg" id="swipeBg"></div>
            <div class="swipe-text" id="swipeText">Geser untuk Absen Masuk</div>
            <div class="swipe-handle btn-clock-in" id="swipeHandle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </div>
        </div>
    @elseif(!$todayAttendance?->clock_out)
        <div class="swipe-container swipe-out disabled" id="swipeContainer">
            <div class="swipe-bg" id="swipeBg"></div>
            <div class="swipe-text" id="swipeText">Geser untuk Absen Keluar</div>
            <div class="swipe-handle btn-clock-out" id="swipeHandle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </div>
        </div>
    @else
        <div class="action-btn-done" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px;">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            Absensi Hari Ini Selesai
        </div>
    @endif

    <button class="action-btn btn-refresh" onclick="detectGPS()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/>
        </svg>
        Refresh Lokasi
    </button>

    {{-- Today's Record --}}
    @if($todayAttendance)
    <div class="attendance-record">
        <div class="record-title">Catatan Hari Ini</div>
        <div class="record-row">
            <span class="record-label">Tanggal</span>
            <span class="record-value">{{ $today->isoFormat('D MMMM Y') }}</span>
        </div>
        <div class="record-row">
            <span class="record-label">Clock In</span>
            <span class="record-value" style="color:#10b981">{{ $todayAttendance->clock_in?->format('H:i') ?? '-' }}</span>
        </div>
        @if($todayAttendance->clock_out)
        <div class="record-row">
            <span class="record-label">Clock Out</span>
            <span class="record-value" style="color:#f59e0b">{{ $todayAttendance->clock_out->format('H:i') }}</span>
        </div>
        <div class="record-row">
            <span class="record-label">Durasi Kerja</span>
            <span class="record-value">
                {{ floor($todayAttendance->clock_in->diffInMinutes($todayAttendance->clock_out) / 60) }}j
                {{ $todayAttendance->clock_in->diffInMinutes($todayAttendance->clock_out) % 60 }}m
            </span>
        </div>
        @endif
        <div class="record-row">
            <span class="record-label">Status</span>
            <span class="record-value" style="color:#10b981">
                Hadir
            </span>
        </div>
    </div>
    @endif
</div>

{{-- Toast --}}
<div class="result-toast" id="toast"></div>

@endsection

@push('scripts')
<script>
    const PLACEMENT_COORD = '{{ $placement?->coordinate ?? "" }}';
    const PLACEMENT_RADIUS = {{ $placement?->radius ?? 0 }};

    const SVG_RADAR = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px;"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/><circle cx="12" cy="12" r="2" fill="currentColor"/></svg>`;
    const SVG_CROSS = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 32px; height: 32px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;
    const SVG_WARNING = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 32px; height: 32px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`;
    const SVG_CHECK = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 32px; height: 32px;"><polyline points="20 6 9 17 4 12"/></svg>`;
    const SVG_MARKER = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>`;

    let userLat = null, userLng = null, gpsValid = false;

    function detectGPS() {
        const ring = document.getElementById('gpsRing');
        const icon = document.getElementById('gpsIcon');
        const statusText = document.getElementById('gpsStatusText');
        const subText = document.getElementById('gpsSubText');
        const badge = document.getElementById('distanceBadge');

        ring.className = 'ring-outer loading';
        icon.innerHTML = SVG_RADAR;
        statusText.textContent = 'Mendeteksi lokasi Anda...';
        subText.textContent = 'Mohon tunggu sebentar';
        badge.style.display = 'none';

        if (!navigator.geolocation) {
            ring.className = 'ring-outer invalid';
            icon.innerHTML = SVG_CROSS;
            statusText.textContent = 'GPS Tidak Didukung';
            subText.textContent = 'Browser Anda tidak mendukung geolocation.';
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                userLat = pos.coords.latitude;
                userLng = pos.coords.longitude;

                // Hitung jarak client-side untuk preview
                const distance = calcDistance(userLat, userLng, PLACEMENT_COORD);

                if (distance === null) {
                    ring.className = 'ring-outer invalid';
                    icon.innerHTML = SVG_WARNING;
                    statusText.textContent = 'Koordinat lokasi tidak valid';
                    subText.textContent = 'Hubungi admin untuk konfigurasi penempatan.';
                    return;
                }

                if (distance <= PLACEMENT_RADIUS) {
                    gpsValid = true;
                    ring.className = 'ring-outer valid';
                    icon.innerHTML = SVG_CHECK;
                    statusText.textContent = 'Anda berada di area kerja!';
                    subText.textContent = 'Silakan tekan tombol absensi.';
                    badge.className = 'distance-badge distance-ok';
                    badge.textContent = `${Math.round(distance)}m dari titik pusat`;
                    badge.style.display = 'inline-block';
                    enableClockBtn(true);
                } else {
                    gpsValid = false;
                    ring.className = 'ring-outer invalid';
                    icon.innerHTML = SVG_MARKER;
                    statusText.textContent = 'Di luar area kerja';
                    subText.textContent = `Posisikan diri Anda lebih dekat ke lokasi kerja.`;
                    badge.className = 'distance-badge distance-far';
                    badge.textContent = `${Math.round(distance)}m — Batas Maks: ${PLACEMENT_RADIUS}m`;
                    badge.style.display = 'inline-block';
                    enableClockBtn(false);
                }
            },
            (err) => {
                ring.className = 'ring-outer invalid';
                icon.innerHTML = SVG_CROSS;
                statusText.textContent = 'Akses lokasi ditolak';
                subText.textContent = 'Pastikan browser diizinkan untuk mengakses lokasi.';
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }

    function calcDistance(lat1, lng1, coord2Str) {
        if (!coord2Str) return null;
        const parts = coord2Str.split(',');
        if (parts.length !== 2) return null;
        const lat2 = parseFloat(parts[0]);
        const lng2 = parseFloat(parts[1]);
        const R = 6371000; // Radius bumi dalam meter
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLng/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function enableClockBtn(enabled) {
        const container = document.getElementById('swipeContainer');
        if (container) {
            if (enabled) {
                container.classList.remove('disabled');
            } else {
                container.classList.add('disabled');
            }
        }
    }

    let isDragging = false;
    let startX = 0;
    let currentX = 0;

    function initSwipe() {
        const container = document.getElementById('swipeContainer');
        const handle = document.getElementById('swipeHandle');
        const bg = document.getElementById('swipeBg');
        const text = document.getElementById('swipeText');
        
        if (!container || !handle) return;

        const getMaxSlide = () => container.clientWidth - handle.clientWidth - 8;

        const onStart = (e) => {
            if (container.classList.contains('disabled') || container.classList.contains('processing')) return;
            isDragging = true;
            startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
            handle.style.transition = 'none';
        };

        const onMove = (e) => {
            if (!isDragging) return;
            currentX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
            let dx = currentX - startX;
            const maxSlide = getMaxSlide();

            if (dx < 0) dx = 0;
            if (dx > maxSlide) dx = maxSlide;

            handle.style.transform = `translateX(${dx}px)`;
            if (bg) bg.style.width = `${dx + 26}px`;
            
            const opacity = 1 - (dx / maxSlide);
            if (text) text.style.opacity = opacity;
        };

        const onEnd = () => {
            if (!isDragging) return;
            isDragging = false;
            const maxSlide = getMaxSlide();
            const currentTranslateX = parseFloat(handle.style.transform.replace('translateX(', '').replace('px)', '')) || 0;

            handle.style.transition = 'transform 0.25s ease-out';
            if (bg) bg.style.transition = 'width 0.25s ease-out';

            if (currentTranslateX >= maxSlide * 0.90) {
                handle.style.transform = `translateX(${maxSlide}px)`;
                if (bg) bg.style.width = '100%';
                if (text) text.style.opacity = 0;
                
                container.classList.add('disabled', 'processing');
                const isClockIn = container.classList.contains('swipe-in');
                doClock(isClockIn ? 'in' : 'out');
            } else {
                handle.style.transform = 'translateX(0px)';
                if (bg) bg.style.width = '0px';
                if (text) text.style.opacity = 1;
            }
        };

        // Touch support
        handle.addEventListener('touchstart', onStart, { passive: true });
        window.addEventListener('touchmove', onMove, { passive: false });
        window.addEventListener('touchend', onEnd);

        // Mouse support
        handle.addEventListener('mousedown', onStart);
        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', onEnd);
    }

    function resetSwipe() {
        const container = document.getElementById('swipeContainer');
        const handle = document.getElementById('swipeHandle');
        const bg = document.getElementById('swipeBg');
        const text = document.getElementById('swipeText');
        
        if (container) container.classList.remove('disabled', 'processing');
        if (handle) {
            handle.style.transform = 'translateX(0px)';
            handle.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>`;
        }
        if (bg) bg.style.width = '0px';
        if (text) {
            const isClockIn = container.classList.contains('swipe-in');
            text.textContent = isClockIn ? 'Geser untuk Absen Masuk' : 'Geser untuk Absen Keluar';
            text.style.opacity = 1;
        }
    }

    async function doClock(type) {
        if (!gpsValid || !userLat || !userLng) {
            resetSwipe();
            return;
        }
        
        const container = document.getElementById('swipeContainer');
        const handle = document.getElementById('swipeHandle');
        const text = document.getElementById('swipeText');
        
        if (handle) {
            handle.innerHTML = `<svg class="spinner" viewBox="0 0 50 50" style="width:20px;height:20px;animation:spin 1s linear infinite;"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5" stroke-linecap="round" style="stroke-dasharray:90,150;stroke-dashoffset:0;"></circle></svg>`;
        }
        if (text) {
            text.textContent = 'Memproses...';
            text.style.opacity = 1;
        }

        const url = type === 'in' ? '{{ route("supervisor.clock.in") }}' : '{{ route("supervisor.clock.out") }}';

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ latitude: userLat, longitude: userLng })
            });
            const data = await res.json();
            showToast(data.message, data.success ? 'success' : 'error');
            if (data.success) {
                if (handle) handle.innerHTML = '✓';
                setTimeout(() => location.reload(), 1500);
            } else {
                resetSwipe();
            }
        } catch (e) {
            showToast('Koneksi terganggu. Silakan coba lagi.', 'error');
            resetSwipe();
        }
    }

    function showToast(msg, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = msg;
        toast.className = `result-toast ${type} show`;
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // Auto detect and initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        initSwipe();
        detectGPS();
    });
</script>
@endpush
