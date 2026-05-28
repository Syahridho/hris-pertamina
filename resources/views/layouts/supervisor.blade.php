<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'Dashboard') — Supervisor HRIS PMC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --red:#E8192C;--red-dark:#c01020;--red-glow:rgba(232,25,44,0.1);
            --blue:#003DA5;
            --navy:#f8fafc;--navy-mid:#f1f5f9;--navy-card:#ffffff;
            --slate:#64748b;--text:#0f172a;--text-muted:#475569;
            --border:#e2e8f0;--glass:#f1f5f9;
            --success:#10b981;--warning:#f59e0b;--danger:#ef4444;--info:#003DA5;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Inter',sans-serif;background:var(--navy);color:var(--text);min-height:100vh;display:flex;}

        /* Sidebar */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: #ffffff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 200;
            transition: transform 0.3s;
        }
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%);}
            .sidebar.open{transform:translateX(0);}
            .main-content{margin-left:0 !important;}
        }
        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-icon{width:40px;height:40px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .brand-icon img{width:40px;height:40px;object-fit:contain;}
        .brand-name{font-size:14px;font-weight:700;}
        .brand-role{font-size:11px;color:var(--text-muted);}
        .sidebar-nav{flex:1;padding:12px 8px;overflow-y:auto;}
        .nav-section{font-size:10px;font-weight:700;color:var(--slate);letter-spacing:0.1em;text-transform:uppercase;padding:8px 10px 4px;}
        .nav-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;color:var(--text-muted);font-size:14px;font-weight:500;transition:background 0.15s,color 0.15s;margin-bottom:2px;}
        .nav-link:hover{background:var(--glass);color:var(--text);}
        .nav-link.active{background:rgba(232,25,44,0.08);color:var(--red);}
        .nav-link svg{width:18px;height:18px;flex-shrink:0;}
        .sidebar-footer{padding:16px;border-top:1px solid var(--border);}
        .user-info{display:flex;align-items:center;gap:10px;margin-bottom:12px;}
        .user-avatar{width:36px;height:36px;background:linear-gradient(135deg,#e2e8f0,#cbd5e1);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:var(--text-muted);border:2px solid var(--border);flex-shrink:0;}
        .user-name{font-size:13px;font-weight:600;}
        .user-role{font-size:11px;color:var(--text-muted);}
        .btn-logout{width:100%;padding:9px;background:var(--glass);border:1px solid var(--border);border-radius:8px;color:var(--text-muted);font-size:13px;font-family:inherit;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:background 0.15s,color 0.15s;}
        .btn-logout:hover{background:rgba(239,68,68,0.1);color:#ef4444;border-color:rgba(239,68,68,0.2);}
        .btn-logout svg{width:14px;height:14px;}

        /* Main */
        .main-content{margin-left:240px;flex:1;display:flex;flex-direction:column;min-height:100vh;}
        .top-bar{background:rgba(255,255,255,0.95);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);padding:14px 24px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;}
        .hamburger{display:none;background:none;border:none;color:var(--text);cursor:pointer;padding:4px;}
        @media(max-width:768px){.hamburger{display:flex;}}
        .hamburger svg{width:22px;height:22px;}
        .top-bar-title{font-size:16px;font-weight:700;}
        .top-bar-right{display:flex;align-items:center;gap:12px;}
        .page-body{padding:24px;flex:1;}
        @media(max-width:768px){.page-body{padding:16px;}}

        /* Flash */
        .flash{padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px;display:flex;gap:8px;align-items:center;}
        .flash svg{width:16px;height:16px;flex-shrink:0;}
        .flash-success{background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);color:#6ee7b7;}
        .flash-error{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#fca5a5;}

        /* Overlay for mobile */
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:150;}
        .sidebar-overlay.show{display:block;}
    </style>
</head>
<body>

<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <img src="{{ asset('logo.png') }}" alt="Logo Pertamina" />
        </div>
        <div>
            <div class="brand-name">HRIS PMC</div>
            <div class="brand-role">Panel Supervisor</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Utama</div>
        <a href="{{ route('supervisor.dashboard') }}" class="nav-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Dashboard
        </a>
        <a href="{{ route('supervisor.clock') }}" class="nav-link {{ request()->routeIs('supervisor.clock') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Absensi Saya
        </a>

        <div class="nav-section">Monitoring</div>
        <a href="{{ route('supervisor.attendance.index') }}" class="nav-link {{ request()->routeIs('supervisor.attendance.index') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Kehadiran Hari Ini
        </a>
        <a href="{{ route('supervisor.attendance.history') }}" class="nav-link {{ request()->routeIs('supervisor.attendance.history') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Riwayat Kehadiran
        </a>

        <div class="nav-section">Persetujuan</div>
        <a href="{{ route('supervisor.submissions.index') }}" class="nav-link {{ request()->routeIs('supervisor.submissions.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Pengajuan Izin
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth('supervisor')->user()?->username ?? 'S', 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth('supervisor')->user()?->username }}</div>
                <div class="user-role">{{ auth('supervisor')->user()?->placement?->name ?? 'Supervisor' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="main-content">
    <div class="top-bar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="hamburger" onclick="toggleSidebar()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <span class="top-bar-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="top-bar-right">
            <span style="font-size:12px;color:var(--text-muted)">{{ \Carbon\Carbon::now()->isoFormat('D MMM Y') }}</span>
        </div>
    </div>

    <div class="page-body">
        @if(session('success'))
            <div class="flash flash-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}
</script>
</body>
</html>
