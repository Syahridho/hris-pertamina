<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>404 — Halaman Tidak Ditemukan | HRIS PMC</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --red: #E8192C;
            --blue: #003DA5;
        }
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-900 min-h-screen flex flex-col">

    <!-- Header / Nav -->
    <header class="border-b border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <img src="/logo.png" alt="Logo Pertamina" class="w-8 h-8 object-contain" />
                <span class="text-sm font-bold tracking-tight text-slate-900">HRIS PMC <span class="font-normal text-slate-500">/ Pertamina MC</span></span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center px-6 py-12">
        <div class="max-w-md w-full text-center space-y-6">
            
            <!-- Graphic Illustration (PMC themed: Gear + Map Pin) -->
            <div class="flex justify-center">
                <div class="relative w-48 h-48 flex items-center justify-center">
                    <!-- Clean SVG Illustration -->
                    <svg viewBox="0 0 200 200" class="w-full h-full text-slate-200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background Circle Grid (Subtle) -->
                        <circle cx="100" cy="100" r="80" stroke="currentColor" stroke-width="1" stroke-dasharray="4 4" />
                        <circle cx="100" cy="100" r="50" stroke="currentColor" stroke-width="1" stroke-dasharray="2 2" />
                        
                        <!-- Maintenance Gear (Outline) -->
                        <path d="M100 70C83.4315 70 70 83.4315 70 100C70 116.569 83.4315 130 100 130C116.569 130 130 116.569 130 100C130 83.4315 116.569 70 100 70Z" stroke="#cbd5e1" stroke-width="2" />
                        
                        <!-- Gear teeth -->
                        <path d="M96 60H104V70H96V60Z" fill="#cbd5e1" />
                        <path d="M96 130H104V140H96V130Z" fill="#cbd5e1" />
                        <path d="M60 96H70V104H60V96Z" fill="#cbd5e1" />
                        <path d="M130 96H140V104H130V96Z" fill="#cbd5e1" />
                        
                        <path d="M72 72L79 79" stroke="#cbd5e1" stroke-width="3" stroke-linecap="round" />
                        <path d="M121 121L128 128" stroke="#cbd5e1" stroke-width="3" stroke-linecap="round" />
                        <path d="M72 128L79 121" stroke="#cbd5e1" stroke-width="3" stroke-linecap="round" />
                        <path d="M121 79L128 72" stroke="#cbd5e1" stroke-width="3" stroke-linecap="round" />

                        <!-- Map pin (Red) -->
                        <path d="M100 75C88.9543 75 80 83.9543 80 95C80 110 100 130 100 130C100 130 120 110 120 95C120 83.9543 111.046 75 100 75Z" stroke="#E8192C" stroke-width="3.5" stroke-linejoin="round" fill="#fff" />
                        
                        <!-- Question mark instead of dot -->
                        <text x="100" y="101" fill="#003DA5" font-family="'Inter', sans-serif" font-size="18" font-weight="800" text-anchor="middle">?</text>
                    </svg>

                    <!-- Large 404 Overlay Badge -->
                    <div class="absolute bottom-2 bg-white px-3 py-1 border border-slate-200 rounded-full shadow-sm text-xs font-bold tracking-widest text-[#003DA5] uppercase">
                        Error 404
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div class="space-y-2">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Halaman Tidak Ditemukan</h2>
                <p class="text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">
                    Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan. Pastikan alamat URL sudah benar.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
                <a href="/" class="px-5 py-2.5 bg-[#E8192C] text-white text-sm font-semibold rounded-md hover:bg-[#cf1525] focus:outline-none focus:ring-2 focus:ring-[#E8192C] focus:ring-offset-2 transition-colors duration-150 inline-flex items-center justify-center gap-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Kembali ke Beranda
                </a>
                <a href="mailto:admin@hris-pertamina.com" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-md hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-2 transition-colors duration-150 inline-flex items-center justify-center gap-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    Hubungi Bantuan
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-500">
        <div class="max-w-6xl mx-auto px-6">
            &copy; {{ date('Y') }} PT. Pertamina Maintenance & Construction. Hak Cipta Dilindungi.
        </div>
    </footer>

</body>
</html>
