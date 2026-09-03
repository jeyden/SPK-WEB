<!-- Mobile Backdrop Overlay -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300"></div>

<!-- Sidebar / Menu Navigasi Samping -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-white dark:bg-slate-900/95 border-r border-slate-200 dark:border-slate-800/80 backdrop-blur-xl h-full transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 md:static md:inset-auto shadow-soft md:shadow-none">
    
    <!-- Logo & Brand Header -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-800/80">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-8 w-auto object-contain filter dark:brightness-110">
            <span class="text-slate-800 dark:text-white font-bold text-sm tracking-wide truncate">Admin Panel</span>
        </div>
        <!-- Tombol Close khusus Mobile di dalam Sidebar -->
        <button id="sidebar-close" class="md:hidden text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        
        <div class="px-3 pb-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Menu Utama</div>
        
        <!-- Dashboard Admin -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-gauge-high w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        <div class="pt-4 pb-2 px-3 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Manajemen Data Master</div>
        
        <!-- Manajemen User -->
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.users*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-users-gear w-5 text-center"></i>
            <span>Manajemen User</span>
        </a>
        
        <!-- Data Artikel -->
        <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.articles*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
            <i class="fa-solid fa-newspaper w-5 text-center"></i>
            <span>Manajemen Artikel</span>
        </a>

    </div>
</aside>