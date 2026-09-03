@php
    // Menentukan sumber foto berdasarkan role pengguna saat ini
    $avatarUrl = null;
    if (auth()->user()->role === 'student' && auth()->user()->student && auth()->user()->student->photo) {
        $avatarUrl = asset('storage/' . auth()->user()->student->photo);
    } elseif (auth()->user()->avatar) {
        $avatarUrl = asset('storage/' . auth()->user()->avatar);
    }
@endphp

<!-- Header Dashboard -->
<header class="h-16 flex items-center justify-between px-6 bg-white dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800/80 backdrop-blur-xl z-20 shrink-0 transition-colors">
    
    <!-- Tombol Toggle Sidebar (Mobile) & Judul Halaman / Breadcrumb -->
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="md:hidden text-slate-500 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white transition-colors focus:outline-none p-1">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 dark:border-indigo-400/30 text-indigo-600 dark:text-indigo-300 uppercase tracking-wider">
                {{ auth()->user()->role }}
            </span>
            <h1 class="text-sm font-bold text-slate-800 dark:text-white hidden sm:block">Panel Sistem Pendukung Keputusan</h1>
        </div>
    </div>

    <!-- Sisi Kanan Header (Indikator Waktu, Tombol Tema, & Dropdown Profil) -->
    <div class="flex items-center gap-3">
        
        <!-- Indikator Waktu / Tanggal Ringkas -->
        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 px-3 py-1.5 rounded-xl">
            <i class="fa-regular fa-calendar-days text-indigo-500"></i>
            <span>{{ date('d M Y') }}</span>
        </div>

        <!-- TOMBOL TOGGLE DARK/LIGHT MODE -->
        <button id="theme-toggle" type="button" class="p-2 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-white transition-all shadow-sm" title="Ubah Tema">
            <!-- Ikon Matahari (Muncul saat mode Dark) -->
            <i class="fa-solid fa-sun hidden dark:block text-amber-400 text-xs"></i>
            <!-- Ikon Bulan (Muncul saat mode Light) -->
            <i class="fa-solid fa-moon block dark:hidden text-indigo-600 text-xs"></i>
        </button>

        <!-- Profil Dropdown (Vanilla JS) -->
        <div class="relative">
            <!-- Dropdown Trigger -->
            <button id="profile-dropdown-button" type="button" class="flex items-center gap-3 pl-2 border-l border-slate-200 dark:border-slate-800 focus:outline-none group">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-semibold text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                </div>
                
                <!-- Tampilkan Foto Profil atau Inisial Huruf -->
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="Avatar" class="w-9 h-9 rounded-xl object-cover shadow-md shadow-indigo-600/20 border border-slate-200 dark:border-slate-700">
                @else
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center text-white font-bold text-xs shadow-md shadow-indigo-600/20">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif

                <i id="dropdown-chevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="profile-dropdown-menu" class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl py-2 z-50 backdrop-blur-xl hidden">
                
                <!-- Header Info Mobile dalam Dropdown -->
                <div class="px-4 py-2 border-b border-slate-200 dark:border-slate-800 sm:hidden flex items-center gap-3">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="Avatar" class="w-8 h-8 rounded-lg object-cover">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <!-- Menu Item: Profil Saya -->
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <i class="fa-regular fa-user w-4 text-indigo-500 dark:text-indigo-400"></i>
                    <span>Profil Saya</span>
                </a>

                <div class="my-1 border-t border-slate-200 dark:border-slate-800"></div>

                <!-- Menu Item: Logout -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-medium text-rose-500 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                        <i class="fa-solid fa-right-from-bracket w-4 text-rose-500 dark:text-rose-400"></i>
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>

<!-- Script Terintegrasi untuk Toggle Sidebar, Dropdown Profil, & Dark/Light Mode -->
@push('scripts')
<script>
    // Script Sidebar
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarClose = document.getElementById('sidebar-close');
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');

    function toggleSidebar() {
        if (sidebar && sidebarBackdrop) {
            sidebar.classList.toggle('-translate-x-full');
            sidebarBackdrop.classList.toggle('hidden');
        }
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
    if (sidebarClose) sidebarClose.addEventListener('click', toggleSidebar);
    if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', toggleSidebar);

    // Script Dropdown Profil (Vanilla JS)
    const profileButton = document.getElementById('profile-dropdown-button');
    const profileMenu = document.getElementById('profile-dropdown-menu');
    const dropdownChevron = document.getElementById('dropdown-chevron');

    if (profileButton && profileMenu) {
        profileButton.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
            dropdownChevron.classList.toggle('rotate-180');
        });

        // Tutup dropdown ketika klik di luar area
        document.addEventListener('click', function(e) {
            if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
                dropdownChevron.classList.remove('rotate-180');
            }
        });
    }

    // Script Toggle Dark/Light Mode
    const themeToggleBtn = document.getElementById('theme-toggle');

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });
    }
</script>
@endpush