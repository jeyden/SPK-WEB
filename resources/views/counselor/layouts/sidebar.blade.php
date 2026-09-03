<!-- Sidebar / Menu Navigasi Samping Guru BK -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 backdrop-blur-xl h-full transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 md:static md:inset-auto flex-shrink-0 shadow-sm md:shadow-none">
    
    <!-- Logo & Brand Header -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-8 w-auto object-contain filter brightness-110">
            <span class="text-slate-800 dark:text-white font-bold text-sm tracking-wide truncate">BK Panel</span>
        </div>
        <!-- Tombol Close khusus Mobile di dalam Sidebar -->
        <button id="sidebar-close" class="md:hidden text-slate-400 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors p-1">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        
        <!-- KELOMPOK 1: MENU UTAMA -->
        <div class="px-3 pb-2 text-[10px] font-semibold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Menu Utama</div>
        
        <!-- 1. Dashboard -->
        <a href="{{ route('counselor.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('counselor.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        <!-- KELOMPOK 2: MANAJEMEN DATA -->
        <div class="pt-4 pb-2 px-3 text-[10px] font-semibold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Manajemen Data</div>
        
        <!-- 2. Periode Pendaftaran -->
        <a href="{{ route('counselor.registration-periods.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('counselor.registration-periods*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-calendar-check w-5 text-center"></i>
            <span>Periode Pendaftaran</span>
        </a>

        <!-- 3. Kriteria Penilaian -->
        <a href="{{ route('counselor.criteria.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('counselor.criteria*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-list-check w-5 text-center"></i>
            <span>Kriteria Penilaian</span>
        </a>

        <!-- 4. Alternatif Jurusan -->
        <a href="{{ route('counselor.majors.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('counselor.majors*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-graduation-cap w-5 text-center"></i>
            <span>Alternatif Jurusan</span>
        </a>

        <!-- KELOMPOK 3: PROSES & REKOMENDASI -->
        <div class="pt-4 pb-2 px-3 text-[10px] font-semibold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Proses & Rekomendasi</div>
        
        <!-- 5. Asesmen RIASEC -->
        <a href="{{ route('counselor.assessments.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs(['counselor.assessments*', 'counselor.assesments*']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-user-pen w-5 text-center"></i>
            <span>Asesmen RIASEC</span>
        </a>

        <!-- 6. Perhitungan & Rekomendasi -->
        <a href="{{ route('counselor.calculation.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('counselor.calculation*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-calculator w-5 text-center"></i>
            <span>Perhitungan & Rekomendasi</span>
        </a>

    </div>

</aside>    