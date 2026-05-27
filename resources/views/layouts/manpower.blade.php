<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HRIS PMC">
    <meta name="description" content="HRIS PMC - Sistem Kehadiran Karyawan">
    <link rel="manifest" href="/manifest.json">
    <title>@yield('title', 'Dashboard') — HRIS PMC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --red:       #E8192C;
            --red-dark:  #c01020;
            --red-glow:  rgba(232,25,44,0.1);
            --blue:      #003DA5;
            --navy:      #f8fafc;
            --navy-mid:  #f1f5f9;
            --navy-card: #ffffff;
            --slate:     #64748b;
            --text:      #0f172a;
            --text-muted:#475569;
            --border:    #e2e8f0;
            --glass:     #f1f5f9;
            --success:   #10b981;
            --warning:   #f59e0b;
            --danger:    #ef4444;
            --safe-bottom: env(safe-area-inset-bottom, 0px);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy);
            color: var(--text);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            padding-bottom: calc(72px + var(--safe-bottom));
        }
        /* ── Top Header ── */
        .app-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-logo {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--red), var(--red-dark));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .header-logo svg { width: 18px; height: 18px; fill: white; }
        .header-title { font-size: 15px; font-weight: 700; }
        .header-subtitle { font-size: 11px; color: var(--text-muted); }
        .header-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700;
            color: var(--text-muted);
            border: 2px solid var(--border);
        }
        /* ── Page Content ── */
        .page-content {
            flex: 1;
            padding: 16px;
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
        }
        /* ── Bottom Nav ── */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0; right: 0;
            z-index: 100;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--border);
            display: flex;
            padding: 8px 0 calc(8px + var(--safe-bottom));
        }
        .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 6px 0;
            text-decoration: none;
            color: var(--slate);
            transition: color 0.2s;
            position: relative;
        }
        .nav-item.active { color: var(--red); }
        .nav-item svg { width: 22px; height: 22px; }
        .nav-item span { font-size: 10px; font-weight: 500; }
        .nav-badge {
            position: absolute;
            top: 2px;
            right: calc(50% - 18px);
            min-width: 16px; height: 16px;
            background: var(--red);
            color: white;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
        }
        /* ── Cards ── */
        .card {
            background: var(--navy-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 12px;
        }
        .card-sm { padding: 14px 16px; }
        /* ── Alert Flash ── */
        .flash-alert {
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 12px;
            display: flex; gap: 8px; align-items: center;
        }
        .flash-alert svg { width: 16px; height: 16px; flex-shrink: 0; }
        .flash-success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25); color: #6ee7b7; }
        .flash-error   { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #fca5a5; }
        .flash-warning { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.25); color: #fcd34d; }
    </style>
</head>
<body>
    {{-- Top Header --}}
    <header class="app-header">
        <div class="header-brand">
            <div class="header-logo">
                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            </div>
            <div>
                <div class="header-title">@yield('page-title', 'HRIS PMC')</div>
                <div class="header-subtitle">{{ auth('manpower')->user()?->placement?->name ?? 'Pertamina MC' }}</div>
            </div>
        </div>
        <div class="header-avatar">
            {{ strtoupper(substr(auth('manpower')->user()?->username ?? 'U', 0, 1)) }}
        </div>
    </header>

    {{-- Main Content --}}
    <main class="page-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flash-alert flash-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-alert flash-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="flash-alert flash-warning">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                {{ session('warning') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <nav class="bottom-nav">
        <a href="{{ route('manpower.dashboard') }}" class="nav-item {{ request()->routeIs('manpower.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="{{ request()->routeIs('manpower.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span>Beranda</span>
        </a>
        <a href="{{ route('manpower.clock') }}" class="nav-item {{ request()->routeIs('manpower.clock') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="{{ request()->routeIs('manpower.clock') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Absensi</span>
        </a>
        <a href="{{ route('manpower.submissions.index') }}" class="nav-item {{ request()->routeIs('manpower.submissions.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="{{ request()->routeIs('manpower.submissions.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            <span>Izin</span>
        </a>
        <a href="{{ route('manpower.attendance.history') }}" class="nav-item {{ request()->routeIs('manpower.attendance.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="{{ request()->routeIs('manpower.attendance.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span>Riwayat</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="flex:1">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;font-family:inherit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </nav>

    @stack('scripts')
    <script>
        // PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>
</body>
</html>
