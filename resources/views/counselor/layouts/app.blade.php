<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Pendukung Keputusan</title>

    <!-- FAVICON KUSTOM -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
<!-- Load DotLottie / Lottie Web Player Script -->
<script src="https://unpkg.com/@dotlottie/player-element@latest/dist/dotlottie-player.mjs" type="module"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    boxShadow: {
                        soft: '0 1px 2px 0 rgb(15 23 42 / 0.04), 0 1px 3px 0 rgb(15 23 42 / 0.06)',
                        card: '0 4px 12px -2px rgb(15 23 42 / 0.06), 0 2px 4px -2px rgb(15 23 42 / 0.04)',
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Smooth theme transition — applied narrowly, no !important overrides */
        body, aside, header, main, footer {
            transition: background-color 200ms ease, border-color 200ms ease, color 200ms ease;
        }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background-color: rgb(148 163 184 / 0.4); border-radius: 9999px; }
        html.dark ::-webkit-scrollbar-thumb { background-color: rgb(71 85 105 / 0.6); }
    </style>

    <!-- Script Inisialisasi Dark/Light Mode anti-flash -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @stack('styles')
</head>
<body class="h-full text-slate-800 dark:text-slate-100 antialiased bg-slate-50 dark:bg-slate-950">

    <!-- Mobile Backdrop Overlay -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300"></div>

    <div class="flex h-screen bg-slate-50 dark:bg-slate-950 relative overflow-hidden">

        <!-- SIDEBAR (Termasuk Navigasi Samping Guru BK) -->
        @include('counselor.layouts.sidebar')

        <!-- MAIN WRAPPER -->
        <div class="flex flex-col flex-1 h-full overflow-hidden min-w-0">

            <!-- HEADER -->
            @include('counselor.layouts.header')

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100/60 dark:bg-slate-900/50 p-6">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

            <!-- FOOTER -->
            @include('counselor.layouts.footer')

        </div>
    </div>

    @stack('scripts')
</body>
</html>