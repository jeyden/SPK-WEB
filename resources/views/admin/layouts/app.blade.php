<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Pendukung Keputusan</title>
<!-- FAVICON KUSTOM -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    },
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

        /* Smooth, deliberate theme transition — applied narrowly, not via blanket !important overrides */
        body, aside, header, main, footer, .theme-transition {
            transition: background-color 200ms ease, border-color 200ms ease, color 200ms ease;
        }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background-color: rgb(148 163 184 / 0.4); border-radius: 9999px; }
        html.dark ::-webkit-scrollbar-thumb { background-color: rgb(71 85 105 / 0.6); }
    </style>

    <!-- Anti-flash dark/light init -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @stack('styles')
</head>
<body class="h-full antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100">

    <!-- Mobile Backdrop Overlay -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 hidden md:hidden"></div>

    <div class="flex h-screen relative overflow-hidden">

        <!-- SIDEBAR -->
        @include('admin.layouts.sidebar')

        <!-- MAIN WRAPPER -->
        <div class="flex flex-col flex-1 h-full overflow-hidden min-w-0">

            <!-- HEADER -->
            @include('admin.layouts.header')

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100/70 dark:bg-slate-900/40 p-4 md:p-6">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

            <!-- FOOTER -->
            @include('admin.layouts.footer')

        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        (function () {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const openBtn = document.getElementById('sidebar-open');
            const closeBtn = document.getElementById('sidebar-close');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                backdrop?.classList.remove('hidden');
            }
            function closeSidebar() {
                sidebar?.classList.add('-translate-x-full');
                backdrop?.classList.add('hidden');
            }
            openBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);
        })();

        // Theme toggle
        (function () {
            const toggleBtn = document.getElementById('theme-toggle');
            toggleBtn?.addEventListener('click', function () {
                const html = document.documentElement;
                const isDark = html.classList.toggle('dark');
                localStorage.theme = isDark ? 'dark' : 'light';
            });
        })();

        // User dropdown
        (function () {
            const btn = document.getElementById('user-menu-button');
            const menu = document.getElementById('user-menu-dropdown');
            if (!btn || !menu) return;
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
            document.addEventListener('click', () => menu.classList.add('hidden'));
        })();
    </script>

    @stack('scripts')
</body>
</html>