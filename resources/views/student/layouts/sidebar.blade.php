<!-- Sidebar / Menu Navigasi Samping Siswa -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 h-full
           bg-white dark:bg-slate-900/95
           border-r border-slate-200 dark:border-slate-800/80
           backdrop-blur-xl
           -translate-x-full md:translate-x-0 md:static md:inset-auto
           transition-transform duration-300 ease-in-out">

    <!-- Logo & Brand Header (Tombol silang persis gaya panel BK) -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-800/80">
        <div class="flex items-center gap-3 min-w-0">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-8 w-auto object-contain shrink-0">
            <span class="text-slate-900 dark:text-white font-bold text-sm tracking-wide truncate">Panel Siswa</span>
        </div>

        <!-- Tombol Close (Silang) khusus Mobile -->
        <button id="sidebar-close" class="md:hidden text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors p-1 shrink-0">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">

        <!-- Inisialisasi Status Tahapan Siswa -->
        @php
            $student = auth()->user()->student ?? null;
            $isProfileCompleted = $student && $student->profile_completed;
            $hasRiasecDone = $student && $student->riasecScore;
            
            // Variabel indikator tahapan (True jika sudah beres semua)
            $isFullyOnboarded = $isProfileCompleted && $hasRiasecDone;
        @endphp

        <div class="px-3 pb-2 text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
            Menu Utama
        </div>

        <!-- DASHBOARD (Tidak dikunci, jika diklik saat belum lengkap diarahkan ke onboarding) -->
        <a href="{{ route('student.dashboard') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all
                   {{ request()->routeIs('student.dashboard')
                       ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30'
                       : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-gauge-high w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        <!-- DATA DIRI (Selalu Terbuka) -->
        <a href="{{ route('student.profile.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all
                   {{ request()->routeIs('student.profile*')
                       ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30'
                       : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-user-pen w-5 text-center"></i>
            <span>Data Diri</span>
            @if(!$isProfileCompleted)
                <span class="ml-auto w-2 h-2 rounded-full bg-red-500 animate-pulse shrink-0" title="Wajib Diisi"></span>
            @endif
        </a>

        <!-- ASESMEN RIASEC -->
        @if($isProfileCompleted)
            <a href="{{ route('student.riasec.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all
                       {{ request()->routeIs('student.riasec*')
                           ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30'
                           : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-clipboard-question w-5 text-center"></i>
                <span>Asesmen RIASEC</span>
                @if(!$hasRiasecDone)
                    <span class="ml-auto w-2 h-2 rounded-full bg-amber-500 animate-pulse shrink-0" title="Wajib Dikerjakan"></span>
                @endif
            </a>
        @else
            <!-- Terkunci (Gaya seragam: hanya ikon gembok di kanan tanpa teks) -->
            <div class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800/40 cursor-not-allowed select-none">
                <div class="flex items-center gap-3 min-w-0">
                    <i class="fa-solid fa-clipboard-question w-5 text-center shrink-0"></i>
                    <span class="truncate">Asesmen RIASEC</span>
                </div>
                <i class="fa-solid fa-lock text-xs text-amber-500 shrink-0"></i>
            </div>
        @endif

        <div class="pt-4 pb-2 px-3 text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
            Hasil & Informasi
        </div>

        <!-- REKOMENDASI JURUSAN -->
        @if($isFullyOnboarded)
            <a href="{{ route('student.recommendations.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all
                       {{ request()->routeIs('student.recommendations*')
                           ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30'
                           : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-square-poll-vertical w-5 text-center"></i>
                <span>Rekomendasi Jurusan</span>
            </a>
        @else
            <!-- Terkunci (Gaya seragam: hanya ikon gembok di kanan tanpa teks) -->
            <div class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800/40 cursor-not-allowed select-none">
                <div class="flex items-center gap-3 min-w-0">
                    <i class="fa-solid fa-square-poll-vertical w-5 text-center shrink-0"></i>
                    <span class="truncate">Rekomendasi Jurusan</span>
                </div>
                <i class="fa-solid fa-lock text-xs text-amber-500 shrink-0"></i>
            </div>
        @endif

        <!-- ARTIKEL & PANDUAN (Selalu Terbuka) -->
        <a href="{{ route('student.articles.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all
                   {{ request()->routeIs('student.articles*')
                       ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30'
                       : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-newspaper w-5 text-center"></i>
            <span>Artikel & Panduan</span>
        </a>

    </div>
</aside>