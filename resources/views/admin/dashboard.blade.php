@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - Sistem Pendukung Keputusan Pemilihan Jurusan')

@section('content')
<div class="space-y-6 pb-16 max-w-7xl mx-auto px-2 sm:px-4 lg:px-0">

    {{-- HERO SECTION ADMIN (Tema Biru Modern & Animasi Melambai) --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-indigo-700 to-slate-900 text-white shadow-xl shadow-blue-500/10">
        {{-- Decorative Elements --}}
        <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
        <div class="absolute right-0 bottom-0 h-80 w-80 rounded-full bg-cyan-400/10 blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px); background-size: 32px 32px;"></div>

        <div class="relative px-6 py-8 sm:px-10 sm:py-12 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="space-y-2">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-blue-100 backdrop-blur-md">
                        <i class="fa-solid fa-shield-halved text-cyan-300"></i> Administrator Panel
                    </span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-white flex items-center gap-2">
                    Halo, Admin 
                    {{-- Animasi Tangan Melambai --}}
                    <span class="inline-block animate-wave origin-[70%_70%] text-2xl sm:text-3xl">👋</span>
                </h1>
                <p class="text-blue-100/80 text-xs sm:text-sm max-w-xl leading-relaxed">
                    Kelola infrastruktur sistem, pantau aktivitas pengguna, dan validasi data secara terpusat dengan cepat dan efisien.
                </p>
            </div>

            {{-- Quick Action / Date Badge --}}
            <div class="hidden md:flex flex-col items-end justify-center bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-right shadow-inner">
                <span class="text-[10px] uppercase tracking-wider text-blue-200 font-semibold">Waktu Sistem</span>
                <span class="text-sm font-bold text-white mt-0.5">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
                <span class="text-[11px] text-cyan-300 mt-1 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Sistem Aktif Normal
                </span>
            </div>
        </div>
    </section>

    {{-- STAT CARDS GRID (Responsif & Modern) --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4 sm:gap-4">
        @php
            $stats = [
                ['label' => 'Total Siswa', 'value' => $totalStudents ?? $totalUsers, 'icon' => 'fa-user-graduate', 'color' => 'blue', 'route' => route('admin.users.index')],
                ['label' => 'Total Artikel', 'value' => $totalArticles, 'icon' => 'fa-newspaper', 'color' => 'emerald', 'route' => route('admin.articles.index')],
                ['label' => 'Total Pengguna', 'value' => $totalUsers, 'icon' => 'fa-users', 'color' => 'amber', 'route' => route('admin.users.index')],
                ['label' => 'Total Admin', 'value' => $totalAdmins, 'icon' => 'fa-user-shield', 'color' => 'rose', 'route' => route('admin.users.index')],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
            <div class="absolute -right-8 -top-8 h-20 w-20 rounded-full bg-{{$stat['color']}}-500/10 blur-xl group-hover:scale-125 transition-transform"></div>
            <div class="relative">
                <div class="mb-3 flex items-center justify-between">
                    <span class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-{{$stat['color']}}-600 text-white shadow-md shadow-{{$stat['color']}}-500/20">
                        <i class="fa-solid {{$stat['icon']}} text-xs sm:text-sm"></i>
                    </span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $stat['label'] }}</p>
                <h3 class="mt-1 text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stat['value']) }}</h3>
            </div>
        </div>
        @endforeach
    </div>

    {{-- DATA TABLES GRID (Responsif Mobile & Desktop) --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        
        {{-- Users Table --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Pengguna Terbaru</h2>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors">LIHAT SEMUA →</a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-950/40 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Nama</th>
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Email</th>
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400 text-right">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($recentUsers ?? [] as $user)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-3.5 text-xs font-bold text-slate-800 dark:text-white whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400 truncate max-w-[150px] sm:max-w-xs">{{ $user->email }}</td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[9px] font-bold text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 uppercase tracking-wider">
                                    {{ $user->role }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-5 py-6 text-center text-xs text-slate-400 italic">Belum ada data pengguna terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Articles Table --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Artikel Terbaru</h2>
                </div>
                <a href="{{ route('admin.articles.index') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors">LIHAT SEMUA →</a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-950/40 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Judul</th>
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Penulis</th>
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400 text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($recentArticles ?? [] as $article)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-3.5 text-xs font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[180px] sm:max-w-xs">{{ $article->title }}</td>
                            <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $article->author->name ?? 'Anonim' }}</td>
                            <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400 text-right whitespace-nowrap">
                                {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M Y') : 'Draft' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-5 py-6 text-center text-xs text-slate-400 italic">Belum ada data artikel terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</div>

{{-- Tambahkan CSS Keyframe untuk Animasi Tangan Melambai --}}
<style>
@keyframes wave {
    0% { transform: rotate(0deg); }
    15% { transform: rotate(14deg); }
    30% { transform: rotate(-8deg); }
    40% { transform: rotate(14deg); }
    50% { transform: rotate(-4deg); }
    60% { transform: rotate(10deg); }
    70% { transform: rotate(0deg); }
    100% { transform: rotate(0deg); }
}

.animate-wave {
    animation: wave 2.2s infinite;
    display: inline-block;
}
</style>
@endsection