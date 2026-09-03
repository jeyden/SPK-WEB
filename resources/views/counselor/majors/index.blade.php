@extends('counselor.layouts.app')

@section('title', 'Daftar Alternatif Jurusan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-16 px-4 sm:px-6">
    
    <!-- HEADER & ACTION BAR -->
    <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight">Daftar Alternatif Jurusan</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Master data program studi, rumpun, sub-ilmu, dan matriks bobot kriteria RIASEC.</p>
        </div>
        
        <form method="GET" action="{{ route('counselor.majors.index') }}" class="flex items-center gap-2">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-search text-[10px]"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama prodi..."
                    class="w-full sm:w-64 pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-indigo-500 transition-all">
            </div>
            @if(request('search'))
                <a href="{{ route('counselor.majors.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABEL UTAMA & BOBOT -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800 text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider bg-slate-50 dark:bg-slate-950/50">
                    <th class="py-3.5 px-4">Program Studi</th>
                    <th class="py-3.5 px-4">Rumpun</th>
                    <th class="py-3.5 px-4">Sub-Ilmu</th>
                    @foreach($criteria as $code => $info)
                        <th class="py-3.5 px-2 text-center font-black text-indigo-600 dark:text-indigo-400 w-10" title="{{ $info }}">{{ $code }}</th>
                    @endforeach
                    <th class="py-3.5 px-4 text-center font-black w-16">Σ W</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-600 dark:text-slate-300">
                @forelse ($calculationRows as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                        <!-- Nama Program Studi (Jenjang digabung di sini) -->
                        <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">
                            {{ optional($row['major'])->degree }} {{ optional($row['major'])->name }}
                        </td>
                        
                        <!-- Rumpun -->
                        <td class="py-3 px-4 font-medium">
                            {{ optional(optional($row['major'])->fieldOfStudy)->rumpunName() ?? '-' }}
                        </td>
                        
                        <!-- Sub-Ilmu -->
                        <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                            {{ optional(optional($row['major'])->fieldOfStudy)->subIlmuName() ?? '-' }}
                        </td>

                        <!-- Bobot Per Kriteria RIASEC -->
                        @foreach($criteria as $code => $info)
                            <td class="py-3 px-2 text-center font-mono text-[11px] text-slate-700 dark:text-slate-300">
                                {{ number_format($row['weights'][$code], 2) }}
                            </td>
                        @endforeach

                        <!-- Total Bobot (Sum) -->
                        <td class="py-3 px-4 text-center font-mono font-bold text-indigo-600 dark:text-indigo-400">
                            {{ number_format(array_sum($row['weights']), 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 4 + count($criteria) }}" class="py-12 text-center text-slate-500 text-xs">
                            @if(request('search'))
                                Pencarian untuk "<span class="font-semibold text-slate-700 dark:text-slate-300">{{ request('search') }}</span>" tidak ditemukan.
                            @else
                                Belum ada data alternatif jurusan. Silakan jalankan Seeder database terlebih dahulu (<code class="font-mono bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded text-indigo-500">php artisan db:seed</code>).
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection