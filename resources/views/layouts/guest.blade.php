<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#ffffff">

        <title>{{ config('app.name', 'HRIS PMC') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --red: #E8192C;
                --blue: #003DA5;
                --slate-50: #f8fafc;
                --slate-100: #f1f5f9;
                --slate-200: #e2e8f0;
                --slate-300: #cbd5e1;
                --slate-500: #64748b;
                --slate-900: #0f172a;
            }
            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--slate-50);
                color: var(--slate-900);
            }
            .auth-card {
                background-color: #ffffff;
                border: 1px solid var(--slate-200);
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
                border-radius: 8px;
            }
            .brand-indicator {
                display: flex;
                height: 3px;
                width: 100%;
                position: absolute;
                top: 0; left: 0;
            }
            .brand-red { background-color: var(--red); flex: 1; }
            .brand-blue { background-color: var(--blue); flex: 1; }
        </style>
    </head>
    <body class="antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="relative w-full sm:max-w-md mt-6 px-8 py-8 auth-card overflow-hidden">
                <!-- Top Brand Strip -->
                <div class="brand-indicator">
                    <div class="brand-red"></div>
                    <div class="brand-blue"></div>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
