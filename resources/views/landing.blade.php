<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HRIS PMC — Sistem Kehadiran & Manajemen Izin Kerja berbasis PWA untuk PT. Pertamina Maintenance and Construction. Absensi GPS, pengajuan izin digital, dan monitoring real-time.">
    <meta name="theme-color" content="#ffffff">
    <title>HRIS PMC — Sistem Kehadiran Karyawan PT. Pertamina Maintenance & Construction</title>

    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ─── DESIGN TOKENS ─────────────────────────────── */
        :root {
            --red:        #E8192C;
            --blue:       #003DA5;
            --black:      #ffffff;
            --surface:    #f8fafc;
            --surface-2:  #f1f5f9;
            --border:     #e2e8f0;
            --border-2:   #cbd5e1;
            --text:       #0f172a;
            --text-2:     #475569;
            --text-3:     #64748b;
            --radius:     8px;
            --radius-lg:  12px;
        }

        /* ─── RESET ─────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; scroll-behavior: smooth; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--black);
            color: var(--text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }

        /* ─── LAYOUT UTILITIES ───────────────────────────── */
        .container {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0); }

        /* ─── NAVBAR ─────────────────────────────────────── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }
        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-mark {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .brand-mark img { width: 36px; height: 36px; object-fit: contain; }
        .brand-wordmark {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text);
        }
        .brand-wordmark span {
            font-weight: 400;
            color: var(--text-2);
        }
        .navbar-actions { display: flex; align-items: center; gap: 8px; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
            line-height: 1;
        }
        .btn svg { width: 14px; height: 14px; }
        .btn-ghost {
            background: transparent;
            border-color: var(--border);
            color: var(--text-2);
        }
        .btn-ghost:hover { background: var(--surface); color: var(--text); border-color: var(--border-2); }
        .btn-primary {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
            font-weight: 600;
        }
        .btn-primary:hover { background: #cf1525; border-color: #cf1525; }
        .btn-lg {
            padding: 11px 22px;
            font-size: 14px;
            font-weight: 600;
        }
        .btn-outline {
            background: transparent;
            border-color: var(--border-2);
            color: var(--text-2);
        }
        .btn-outline:hover { border-color: var(--text-2); color: var(--text); }

        /* ─── HERO ───────────────────────────────────────── */
        .hero {
            padding: 100px 0 80px;
            border-bottom: 1px solid var(--border);
        }
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px;
            border: 1px solid var(--border-2);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-2);
            margin-bottom: 28px;
            background: var(--surface);
        }
        .hero-eyebrow-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--red);
        }
        .hero h1 {
            font-size: clamp(36px, 6vw, 64px);
            font-weight: 800;
            line-height: 1.08;
            letter-spacing: -0.04em;
            max-width: 760px;
            margin-bottom: 24px;
        }
        .hero h1 .accent-red { color: var(--red); }
        .hero h1 .accent-blue { color: var(--blue); }
        .hero-desc {
            font-size: 17px;
            color: var(--text-2);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 40px;
        }
        .hero-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .hero-meta {
            margin-top: 64px;
            padding-top: 40px;
            border-top: 1px solid var(--border);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 32px;
        }
        .hero-stat-num {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: var(--text);
        }
        .hero-stat-label {
            font-size: 13px;
            color: var(--text-2);
            margin-top: 4px;
        }

        /* ─── SECTION BASE ───────────────────────────────── */
        .section { padding: 80px 0; border-bottom: 1px solid var(--border); }
        .section-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-3);
            margin-bottom: 16px;
        }
        .section-title {
            font-size: clamp(24px, 4vw, 36px);
            font-weight: 700;
            letter-spacing: -0.03em;
            margin-bottom: 14px;
        }
        .section-desc {
            font-size: 15px;
            color: var(--text-2);
            max-width: 480px;
            line-height: 1.7;
        }

        /* ─── FEATURE GRID ───────────────────────────────── */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            margin-top: 48px;
        }
        .feature-card {
            background: var(--black);
            padding: 32px 28px;
            transition: background 0.15s;
        }
        .feature-card:hover { background: var(--surface); }
        .feature-icon {
            width: 40px;
            height: 40px;
            border: 1px solid var(--border-2);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            background: var(--surface);
        }
        .feature-icon svg { width: 18px; height: 18px; }
        .feature-icon.red { border-color: rgba(232,25,44,0.3); background: rgba(232,25,44,0.08); }
        .feature-icon.red svg { color: var(--red); }
        .feature-icon.blue { border-color: rgba(0,61,165,0.4); background: rgba(0,61,165,0.1); }
        .feature-icon.blue svg { color: #60a5fa; }
        .feature-icon.zinc { border-color: var(--border-2); }
        .feature-icon.zinc svg { color: var(--text-2); }
        .feature-title { font-size: 15px; font-weight: 600; margin-bottom: 8px; }
        .feature-desc { font-size: 13px; color: var(--text-2); line-height: 1.7; }

        /* ─── ROLES SECTION ──────────────────────────────── */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px;
            margin-top: 48px;
        }
        .role-card {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 28px;
            background: var(--surface);
            position: relative;
            overflow: hidden;
        }
        .role-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .role-card.red::before  { background: var(--red); }
        .role-card.blue::before { background: var(--blue); }
        .role-card.zinc::before { background: #52525b; }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 16px;
        }
        .badge-red  { background: rgba(232,25,44,0.1); color: var(--red); border: 1px solid rgba(232,25,44,0.2); }
        .badge-blue { background: rgba(0,61,165,0.08); color: var(--blue); border: 1px solid rgba(0,61,165,0.15); }
        .badge-zinc { background: #f1f5f9; color: #475569; border: 1px solid var(--border); }
        .role-title { font-size: 18px; font-weight: 700; margin-bottom: 10px; }
        .role-desc { font-size: 13px; color: var(--text-2); line-height: 1.7; margin-bottom: 20px; }
        .role-perms { list-style: none; display: flex; flex-direction: column; gap: 7px; }
        .role-perms li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-2);
        }
        .perm-check {
            width: 16px; height: 16px;
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .perm-check.red  { background: rgba(232,25,44,0.12); }
        .perm-check.blue { background: rgba(0,61,165,0.1); }
        .perm-check.zinc { background: #e2e8f0; }
        .perm-check svg { width: 10px; height: 10px; }
        .perm-check.red svg  { color: var(--red); }
        .perm-check.blue svg { color: var(--blue); }
        .perm-check.zinc svg { color: #64748b; }

        /* ─── HOW IT WORKS ───────────────────────────────── */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0;
            margin-top: 48px;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .step {
            padding: 32px 24px;
            border-right: 1px solid var(--border);
            position: relative;
        }
        .step:last-child { border-right: none; }
        @media(max-width: 768px) {
            .steps-grid { grid-template-columns: 1fr; }
            .step { border-right: none; border-bottom: 1px solid var(--border); }
            .step:last-child { border-bottom: none; }
        }
        .step-num {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-3);
            margin-bottom: 16px;
        }
        .step-icon {
            font-size: 24px;
            margin-bottom: 12px;
        }
        .step-title { font-size: 15px; font-weight: 600; margin-bottom: 8px; }
        .step-desc { font-size: 13px; color: var(--text-2); line-height: 1.6; }

        /* ─── TECH STACK ─────────────────────────────────── */
        .tech-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 32px;
        }
        .tech-chip {
            padding: 6px 14px;
            border: 1px solid var(--border-2);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-2);
            background: var(--surface);
            font-family: 'Söhne Mono', 'Consolas', monospace;
        }

        /* ─── CTA SECTION ────────────────────────────────── */
        .cta-section {
            padding: 80px 0;
        }
        .cta-box {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 56px 48px;
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
            flex-wrap: wrap;
        }
        .cta-box h2 {
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 700;
            letter-spacing: -0.03em;
            margin-bottom: 10px;
        }
        .cta-box p { font-size: 14px; color: var(--text-2); line-height: 1.7; max-width: 440px; }
        .cta-buttons { display: flex; gap: 10px; flex-shrink: 0; flex-wrap: wrap; }

        /* ─── FOOTER ─────────────────────────────────────── */
        .footer {
            padding: 40px 0;
            border-top: 1px solid var(--border);
        }
        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .footer-copy { font-size: 13px; color: var(--text-3); }
        .footer-links { display: flex; gap: 20px; }
        .footer-links a { font-size: 13px; color: var(--text-3); transition: color 0.15s; }
        .footer-links a:hover { color: var(--text-2); }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-2);
        }
        .footer-logo .mark {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
        }
        .footer-logo .mark img { width: 28px; height: 28px; object-fit: contain; }

        /* ─── DIVIDER LINE ───────────────────────────────── */
        .line { width: 32px; height: 2px; background: var(--red); display: block; margin-bottom: 20px; }

        /* ─── RESPONSIVE ─────────────────────────────────── */
        @media (max-width: 640px) {
            .hero { padding: 64px 0 56px; }
            .cta-box { padding: 36px 24px; }
            .navbar-brand span { display: none; }
        }

        /* ─── SCROLLBAR ──────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--black); }
        ::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 3px; }
    </style>
</head>
<body>

{{-- ────────────────── NAVBAR ────────────────── --}}
<header class="navbar">
    <div class="container">
        <nav class="navbar-inner" aria-label="Navigasi utama">
            <div class="navbar-brand">
                <div class="brand-mark" aria-hidden="true">
                    <img src="{{ asset('logo.png') }}" alt="Logo Pertamina" />
                </div>
                <div class="brand-wordmark">HRIS Pertamina MC</div>
            </div>
            <div class="navbar-actions">
               
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Masuk
                </a>
            </div>
        </nav>
    </div>
</header>

{{-- ────────────────── HERO ────────────────── --}}
<section class="hero" id="hero" aria-labelledby="hero-heading">
    <div class="container">
        <div class="hero-eyebrow" aria-label="Status sistem">
            <span class="hero-eyebrow-dot" aria-hidden="true"></span>
            Sistem aktif &amp; siap digunakan
        </div>
        <h1 id="hero-heading">
            Kehadiran<br>
            <span class="accent-red">Digital</span> untuk<br>
            <span class="accent-blue">Pertamina</span> MC
        </h1>
        <p class="hero-desc">
            Platform manajemen kehadiran dan izin kerja berbasis PWA untuk PT. Pertamina Maintenance &amp; Construction.
            Absensi GPS real-time, approval workflow digital, monitoring tanpa kertas.
        </p>
        <div class="hero-actions">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg" id="hero-cta">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Masuk ke Sistem
            </a>
            <a href="#fitur" class="btn btn-outline btn-lg">
                Pelajari Fitur
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
        </div>

        <div class="hero-meta" role="list" aria-label="Statistik sistem">
            <div role="listitem">
                <div class="hero-stat-num">3</div>
                <div class="hero-stat-label">Tingkat akses</div>
            </div>
            <div role="listitem">
                <div class="hero-stat-num">GPS</div>
                <div class="hero-stat-label">Validasi lokasi</div>
            </div>
            <div role="listitem">
                <div class="hero-stat-num">PWA</div>
                <div class="hero-stat-label">Install di HP &amp; PC</div>
            </div>
            <div role="listitem">
                <div class="hero-stat-num">24/7</div>
                <div class="hero-stat-label">Monitoring real-time</div>
            </div>
        </div>
    </div>
</section>

{{-- ────────────────── FITUR ────────────────── --}}
<section class="section" id="fitur" aria-labelledby="fitur-heading">
    <div class="container">
        <span class="section-eyebrow" aria-hidden="true">
            <span style="width:16px;height:1px;background:var(--red);display:block"></span>
            Fitur Utama
        </span>
        <h2 class="section-title" id="fitur-heading">Semua yang dibutuhkan tim lapangan</h2>
        <p class="section-desc">Dirancang untuk kebutuhan operasional area industri dengan koneksi terbatas dan mobilitas tinggi.</p>

        <div class="feature-grid" role="list">
            <div class="feature-card" role="listitem">
                <div class="feature-icon red" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <div class="feature-title">Absensi GPS</div>
                <div class="feature-desc">Clock in / clock out dengan validasi koordinat dan radius lokasi kerja. Tidak bisa absen dari luar area.</div>
            </div>
            <div class="feature-card" role="listitem">
                <div class="feature-icon blue" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div class="feature-title">Pengajuan Izin Digital</div>
                <div class="feature-desc">Cuti, sakit, dan izin khusus diajukan langsung dari HP. Upload dokumen pendukung, tidak perlu form kertas.</div>
            </div>
            <div class="feature-card" role="listitem">
                <div class="feature-icon zinc" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                </div>
                <div class="feature-title">Monitoring Real-time</div>
                <div class="feature-desc">Supervisor melihat status kehadiran manpower di placement-nya secara langsung, tanpa perlu laporan manual.</div>
            </div>
            <div class="feature-card" role="listitem">
                <div class="feature-icon red" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                </div>
                <div class="feature-title">Workflow Persetujuan</div>
                <div class="feature-desc">Supervisor menyetujui atau menolak pengajuan izin dengan satu klik. Notifikasi status dikirim otomatis.</div>
            </div>
            <div class="feature-card" role="listitem">
                <div class="feature-icon blue" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </div>
                <div class="feature-title">Panel Admin Terpusat</div>
                <div class="feature-desc">Superadmin mengelola project, placement, jadwal kerja, supervisor, dan manpower dari satu dashboard.</div>
            </div>
            <div class="feature-card" role="listitem">
                <div class="feature-icon zinc" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div class="feature-title">PWA — Install di HP</div>
                <div class="feature-desc">Berjalan seperti aplikasi native. Tidak perlu Play Store atau App Store. Ringan, cepat, dan bisa offline.</div>
            </div>
        </div>
    </div>
</section>

{{-- ────────────────── ROLES ────────────────── --}}
<section class="section" id="pengguna" aria-labelledby="roles-heading">
    <div class="container">
        <span class="section-eyebrow" aria-hidden="true">
            <span style="width:16px;height:1px;background:var(--red);display:block"></span>
            Akses &amp; Peran
        </span>
        <h2 class="section-title" id="roles-heading">Tiga tingkat akses, satu sistem</h2>
        <p class="section-desc">Setiap peran mendapat akses yang sesuai dengan tanggung jawabnya di lapangan.</p>

        <div class="roles-grid" role="list">
            {{-- Superadmin --}}
            <div class="role-card red" role="listitem" aria-label="Peran Superadmin">
                <span class="role-badge badge-red">Superadmin</span>
                <h3 class="role-title">HR / Administrator</h3>
                <p class="role-desc">Kontrol penuh sistem. Mengelola seluruh data master dan memantau operasional semua project.</p>
                <ul class="role-perms" aria-label="Akses Superadmin">
                    @foreach(['Manajemen project & placement', 'Manajemen jadwal kerja (shift)', 'Tambah & kelola supervisor', 'Tambah & kelola manpower', 'Lihat rekap semua kehadiran', 'Panel admin Filament'] as $perm)
                    <li>
                        <span class="perm-check red" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        {{ $perm }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Supervisor --}}
            <div class="role-card blue" role="listitem" aria-label="Peran Supervisor">
                <span class="role-badge badge-blue">Supervisor</span>
                <h3 class="role-title">Pengawas Proyek</h3>
                <p class="role-desc">Memantau kehadiran manpower di placement-nya dan memproses pengajuan izin tim.</p>
                <ul class="role-perms" aria-label="Akses Supervisor">
                    @foreach(['Monitor kehadiran manpower harian', 'Approve / reject pengajuan izin', 'Absensi GPS mandiri', 'Riwayat kehadiran placement', 'Dashboard statistik kehadiran'] as $perm)
                    <li>
                        <span class="perm-check blue" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        {{ $perm }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Manpower --}}
            <div class="role-card zinc" role="listitem" aria-label="Peran Manpower">
                <span class="role-badge badge-zinc">Manpower</span>
                <h3 class="role-title">Karyawan Lapangan</h3>
                <p class="role-desc">Absensi mandiri dari HP dan mengajukan cuti, sakit, atau izin tanpa perlu ke kantor HR.</p>
                <ul class="role-perms" aria-label="Akses Manpower">
                    @foreach(['Clock in & out via GPS', 'Ajukan cuti / sakit / izin', 'Upload dokumen lampiran', 'Riwayat kehadiran pribadi', 'Status approval pengajuan'] as $perm)
                    <li>
                        <span class="perm-check zinc" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        {{ $perm }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ────────────────── HOW IT WORKS ────────────────── --}}
<section class="section" id="cara-kerja" aria-labelledby="how-heading">
    <div class="container">
        <span class="section-eyebrow" aria-hidden="true">
            <span style="width:16px;height:1px;background:var(--red);display:block"></span>
            Cara Kerja
        </span>
        <h2 class="section-title" id="how-heading">Absensi dalam empat langkah</h2>
        <p class="section-desc">Proses yang sederhana, dirancang untuk karyawan lapangan tanpa waktu banyak.</p>

        <div class="steps-grid" role="list" aria-label="Langkah absensi">
            <div class="step" role="listitem">
                <div class="step-num">01</div>
                <div class="step-icon" aria-hidden="true" style="color: var(--blue);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                </div>
                <div class="step-title">Login</div>
                <div class="step-desc">Masuk dengan email dan password akun yang diberikan HR. Sistem mendeteksi peran secara otomatis.</div>
            </div>
            <div class="step" role="listitem">
                <div class="step-num">02</div>
                <div class="step-icon" aria-hidden="true" style="color: var(--blue);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/>
                        <path d="M12 8a4 4 0 1 0 4 4 4 4 0 0 0-4-4zm0 6a2 2 0 1 1 2-2 2 2 0 0 1-2 2z"/>
                    </svg>
                </div>
                <div class="step-title">Aktifkan GPS</div>
                <div class="step-desc">Izinkan akses lokasi di browser. Sistem mengambil koordinat Anda saat itu juga.</div>
            </div>
            <div class="step" role="listitem">
                <div class="step-num">03</div>
                <div class="step-icon" aria-hidden="true" style="color: var(--blue);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="6"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                </div>
                <div class="step-title">Validasi Radius</div>
                <div class="step-desc">Koordinat Anda dibandingkan dengan lokasi kerja. Harus berada dalam radius yang ditentukan.</div>
            </div>
            <div class="step" role="listitem">
                <div class="step-num">04</div>
                <div class="step-icon" aria-hidden="true" style="color: var(--blue);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div class="step-title">Absensi Tercatat</div>
                <div class="step-desc">Clock in berhasil. Data tersimpan otomatis lengkap dengan waktu dan status ketepatan hadir.</div>
            </div>
        </div>
    </div>
</section>

{{-- ────────────────── TECH ────────────────── --}}
<section class="section" id="teknologi" aria-labelledby="tech-heading">
    <div class="container">
        <span class="section-eyebrow" aria-hidden="true">
            <span style="width:16px;height:1px;background:var(--red);display:block"></span>
            Teknologi
        </span>
        <h2 class="section-title" id="tech-heading">Dibangun di atas fondasi yang solid</h2>
        <p class="section-desc">Stack modern yang ringan, aman, dan mudah dimaintain untuk jangka panjang.</p>

        <div class="tech-list" role="list" aria-label="Stack teknologi">
            @foreach(['Laravel 10', 'FilamentPHP v3', 'Livewire', 'MySQL', 'PWA', 'Geolocation API', 'Laravel Sanctum', 'Service Worker'] as $tech)
            <span class="tech-chip" role="listitem">{{ $tech }}</span>
            @endforeach
        </div>
    </div>
</section>

{{-- ────────────────── CTA ────────────────── --}}
<section class="cta-section" id="mulai" aria-labelledby="cta-heading">
    <div class="container">
        <div class="cta-box">
            <div>
                <h2 id="cta-heading">Siap mulai bekerja<br>dengan sistem yang lebih baik?</h2>
                <p>Gunakan akun yang telah diberikan HR untuk masuk ke sistem. Jika belum memiliki akun, hubungi administrator.</p>
            </div>
            <div class="cta-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg" id="cta-login-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Masuk ke Sistem
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ────────────────── FOOTER ────────────────── --}}
<footer class="footer" role="contentinfo">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-logo">
                <div class="mark" aria-hidden="true">
                    <img src="{{ asset('logo.png') }}" alt="Logo Pertamina" />
                </div>
                HRIS PMC
            </div>
            <p class="footer-copy">
                &copy; {{ date('Y') }} PT. Pertamina Maintenance &amp; Construction. All rights reserved.
            </p>
            <nav class="footer-links" aria-label="Footer navigation">
                <a href="{{ route('login') }}">Login</a>
                <a href="#fitur">Fitur</a>
                <a href="#cara-kerja">Cara Kerja</a>
            </nav>
        </div>
    </div>
</footer>

</body>
</html>
