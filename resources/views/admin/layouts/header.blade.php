@php
    $authUser = auth()->user();
    $avatarUrl = null;

    // 1. Cek langsung dari kolom avatar pada tabel users
    if (!empty($authUser->avatar)) {
        $avatarUrl = asset('storage/' . $authUser->avatar);
    }
    // 2. Cek jika ada relasi 'student' dan punya kolom 'avatar'
    elseif ($authUser->role === 'student') {
        $studentProfile = \App\Models\Student::where('user_id', $authUser->id)->first();
        
        if ($studentProfile) {
            if (!empty($studentProfile->avatar)) {
                $avatarUrl = asset('storage/' . $studentProfile->avatar);
            }
        }
        
        // Cek melalui relasi Eloquent jika sudah didefinisikan di model User
        if (!$avatarUrl && method_exists($authUser, 'student') && $authUser->student) {
            if (!empty($authUser->student->avatar)) {
                $avatarUrl = asset('storage/' . $authUser->student->avatar);
            }
        }
    }
@endphp

<!-- HEADER -->
<header class="h-16 flex items-center justify-between px-4 md:px-6
                bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl
                border-b border-slate-200 dark:border-slate-800/80 shrink-0 relative z-50 transition-colors">

    <!-- Left: mobile toggle + role badge + title -->
    <div class="flex items-center gap-3 min-w-0">
        <button id="sidebar-open" class="md:hidden text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors focus:outline-none p-1">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        <!-- Lencana role selalu tampil di mobile maupun desktop -->
        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide
                     bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400
                     border border-indigo-100 dark:border-indigo-500/20 uppercase shrink-0">
            {{ $authUser->role ?? 'ADMINISTRATOR' }}
        </span>

        <!-- Teks panjang disembunyikan di layar mobile kecil agar tidak kepotong -->
        <h1 class="text-sm md:text-base font-semibold text-slate-800 dark:text-slate-100 truncate hidden sm:block">
            Panel Sistem Pendukung Keputusan
        </h1>
    </div>

    <!-- Right: date, theme toggle, user -->
    <div class="flex items-center gap-2 md:gap-3">

        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl
                    bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-xs font-medium
                    text-slate-600 dark:text-slate-300">
            <i class="fa-regular fa-calendar-days text-indigo-500 dark:text-indigo-400"></i>
            <span>{{ now()->translatedFormat('d M Y') }}</span>
        </div>

        <button id="theme-toggle" type="button"
            class="w-9 h-9 flex items-center justify-center rounded-xl
                   bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10
                   text-slate-600 dark:text-slate-300
                   hover:bg-slate-200 dark:hover:bg-white/10 transition-all shadow-sm cursor-pointer" title="Ubah Tema">
            <i class="fa-solid fa-sun hidden dark:inline text-amber-400 text-xs pointer-events-none"></i>
            <i class="fa-solid fa-moon dark:hidden text-indigo-600 text-xs pointer-events-none"></i>
        </button>

        <!-- User Dropdown Menu -->
        <div class="relative">
            <button id="user-menu-button" type="button" class="flex items-center gap-3 pl-2.5 pr-1 py-1 rounded-xl border-l border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors focus:outline-none group">
                <div class="text-right hidden sm:block leading-tight">
                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $authUser->name ?? 'Ahmad Fauzan' }}</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $authUser->email ?? 'admin@sekolah.sch.id' }}</p>
                </div>

                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="Avatar" class="w-9 h-9 rounded-xl object-cover shadow-md shadow-indigo-600/20 border border-slate-200 dark:border-slate-700 shrink-0">
                @else
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-500 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-indigo-600/20 shrink-0">
                        {{ strtoupper(substr($authUser->name ?? 'A', 0, 1)) }}
                    </div>
                @endif

                <i id="dropdown-chevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 dark:text-slate-500 transition-transform duration-200"></i>
            </button>

            <!-- Dropdown Content -->
            <div id="user-menu-dropdown"
                class="hidden absolute right-0 mt-3 w-56 rounded-2xl overflow-hidden
                       bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800
                       shadow-2xl py-2 z-[9999] backdrop-blur-xl">
                
                <!-- Header Info Mobile dalam Dropdown -->
                <div class="px-4 py-2 border-b border-slate-200 dark:border-slate-800 sm:hidden flex items-center gap-3">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="Avatar" class="w-8 h-8 rounded-lg object-cover">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                            {{ strtoupper(substr($authUser->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">{{ $authUser->name ?? 'Ahmad Fauzan' }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ $authUser->email ?? 'admin@sekolah.sch.id' }}</p>
                    </div>
                </div>

                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <i class="fa-regular fa-user w-4 text-indigo-500 dark:text-indigo-400"></i> Profil Saya
                </a>

                <div class="my-1 border-t border-slate-200 dark:border-slate-800"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-medium text-rose-500 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                        <i class="fa-solid fa-right-from-bracket w-4 text-rose-500 dark:text-rose-400"></i> Keluar / Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>