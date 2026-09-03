@extends('counselor.layouts.app')

@section('title', 'Dashboard Guru BK')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-16 px-0 sm:px-0">

{{-- HERO --}}
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-violet-600 to-indigo-900 dark:from-indigo-950 dark:via-violet-950 dark:to-slate-950 text-white shadow-2xl">

    <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -right-16 -top-16 h-72 w-72 rounded-full bg-fuchsia-400/10 blur-3xl pointer-events-none"></div>
    <div class="absolute right-0 bottom-0 h-96 w-96 rounded-full bg-indigo-400/20 blur-3xl pointer-events-none"></div>

    <div
        class="absolute inset-0 opacity-[0.08] pointer-events-none"
        style="background-image: linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px); background-size: 42px 42px;"
    ></div>

    <div class="relative px-6 py-8 sm:px-10 sm:py-10 lg:px-12 lg:py-12">
        <div class="min-w-0 max-w-4xl">

            <div class="mb-5">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 text-[11px] font-bold uppercase tracking-wider text-indigo-100 shadow-sm backdrop-blur-md sm:text-xs">
                    <i class="fa-solid {{ $greeting['icon'] }}"></i>
                    {{ $greeting['text'] }}
                </span>
            </div>

            <h1 class="flex flex-wrap items-center gap-x-3 gap-y-2 text-3xl font-black leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                <span>Halo, {{ auth()->user()->name ?? 'Guru BK' }}</span>
                <span class="animate-wave inline-block origin-[70%_70%] text-3xl sm:text-4xl">👋</span>
            </h1>

            <p class="mt-4 max-w-2xl text-sm font-medium leading-relaxed text-indigo-100/90 sm:text-base">
                Pantau partisipasi asesmen RIASEC, tren rumpun minat, dan progres perhitungan rekomendasi jurusan berbasis SAW untuk seluruh siswa.
            </p>

            <div class="mt-6 flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 text-xs font-semibold shadow-sm backdrop-blur-md">
                    <i class="fa-solid fa-calendar text-indigo-200"></i>
                    Tahun Ajaran {{ $activePeriod->academic_year ?? '-' }}
                </span>

                @if($activePeriod)
                    <span class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 text-xs font-semibold shadow-sm backdrop-blur-md">
                        <i class="fa-solid fa-circle-dot text-indigo-200"></i>
                        Status Pendaftaran: {{ $activePeriod->statusLabel() }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
@keyframes wave {
    0% { transform: rotate(0deg); }
    10% { transform: rotate(14deg); }
    20% { transform: rotate(-8deg); }
    30% { transform: rotate(14deg); }
    40% { transform: rotate(-8deg); }
    50% { transform: rotate(14deg); }
    60% { transform: rotate(-4deg); }
    70% { transform: rotate(10deg); }
    80% { transform: rotate(0deg); }
    100% { transform: rotate(0deg); }
}
.animate-wave {
    display: inline-block;
    animation: wave 1.8s ease-in-out infinite;
    transform-origin: 70% 70%;
    will-change: transform;
}
</style>
@endpush

{{-- STAT CARDS --}}
@php
    $accentClasses = [
        'indigo' => ['bg' => 'bg-indigo-100 dark:bg-indigo-500/10', 'text' => 'text-indigo-600 dark:text-indigo-400'],
        'emerald' => ['bg' => 'bg-emerald-100 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400'],
        'violet' => ['bg' => 'bg-violet-100 dark:bg-violet-500/10', 'text' => 'text-violet-600 dark:text-violet-400'],
        'fuchsia' => ['bg' => 'bg-fuchsia-100 dark:bg-fuchsia-500/10', 'text' => 'text-fuchsia-600 dark:text-fuchsia-400'],
        'amber' => ['bg' => 'bg-amber-100 dark:bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400'],
    ];
@endphp

<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach($statCards as $card)
        @php $accent = $accentClasses[$card['accent']] ?? $accentClasses['indigo']; @endphp
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
            <div class="flex items-start justify-between gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center {{ $accent['bg'] }} {{ $accent['text'] }}">
                    <i class="fa-solid {{ $card['icon'] }}"></i>
                </div>
            </div>

            <p class="mt-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                {{ $card['label'] }}
            </p>

            <div class="mt-1 flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-slate-900 dark:text-white">
                    {{ $card['value'] }}
                </span>
                @if(!empty($card['suffix']))
                    <span class="text-xs font-bold text-slate-400">{{ $card['suffix'] }}</span>
                @endif
            </div>

            <p class="mt-1.5 text-[11px] leading-relaxed text-slate-500 dark:text-slate-400">
                {{ $card['sub'] }}
            </p>
        </div>
    @endforeach
</section>

{{-- MAIN GRID: TABEL AKTIVITAS (KIRI) + ANALITIK (KANAN) --}}
<section class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_360px] gap-5">

{{-- AKTIVITAS ASESMEN & HASIL SISWA TERBARU --}}
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">

    <div class="flex items-center justify-between gap-3 p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800">
        <div>
            <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">
                Aktivitas Asesmen & Hasil Siswa Terbaru
            </h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Ringkasan status pengerjaan RIASEC dan profil minat siswa.
            </p>
        </div>

        @if(Route::has('counselor.students.index'))
            <a href="{{ route('counselor.students.index') }}"
                class="shrink-0 inline-flex items-center gap-1.5 text-[11px] sm:text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:gap-2.5 transition-all">
                Lihat semua
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        @endif
    </div>

    @if($recentActivities->count())
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/70 dark:bg-slate-950/40 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-5 sm:px-6 py-3">Nama Siswa & Kelas</th>
                        <th class="px-4 py-3">Tanggal Asesmen</th>
                        <th class="px-4 py-3">Status Asesmen</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($recentActivities as $activity)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-950/30 transition-colors">
                            <td class="px-5 sm:px-6 py-4">
                                <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">
                                    {{ $activity['nama'] }}
                                </p>
                                <p class="mt-0.5 text-[11px] text-slate-400">
                                    Kelas {{ $activity['kelas'] }}
                                </p>
                            </td>

                            <td class="px-4 py-4">
                                @if($activity['assessment_date'])
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $activity['assessment_date'] }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>

                            <td class="px-4 py-4">
                                @if($activity['is_completed'])
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 dark:bg-amber-500/10 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Belum Mengerjakan
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-4">
    <div class="flex items-center justify-end gap-2">
        @if($activity['is_completed'])
            @if($activity['has_recommendation'])
                {{-- Jika sudah dihitung SAW, tampilkan tombol normal --}}
                @if(Route::has('counselor.calculation.report'))
                    <a href="{{ route('counselor.calculation.report', $activity['student_id']) }}"
                        title="Lihat detail hasil asesmen"
                        class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-indigo-600 flex items-center justify-center transition">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </a>
                @endif

                @if(Route::has('counselor.calculation.pdf.single'))
                    <a href="{{ route('counselor.calculation.pdf.single', $activity['student_id']) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        title="Unduh laporan rekomendasi"
                        class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-indigo-600 flex items-center justify-center transition">
                        <i class="fa-solid fa-download text-xs"></i>
                    </a>
                @endif
            @else
                {{-- Jika sudah asesmen tapi belum dihitung SAW --}}
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-violet-50 dark:bg-violet-500/10 text-[11px] font-bold text-violet-600 dark:text-violet-400 border border-violet-100 dark:border-violet-500/20">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    Lakukan perhitungan dulu
                </span>
            @endif
        @else
            <span class="text-xs text-slate-400 italic">—</span>
        @endif
    </div>
</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="flex min-h-[190px] items-center justify-center px-5 py-8">
            <div class="flex flex-col items-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 shadow-sm">
                    <i class="fa-solid fa-clipboard-list text-lg"></i>
                </div>
                <p class="mt-3 text-xs font-semibold text-slate-500 dark:text-slate-400">
                    Belum ada aktivitas asesmen siswa.
                </p>
            </div>
        </div>
    @endif
</div>
    {{-- WIDGET ANALITIK --}}
    <div class="space-y-5">

        {{-- DISTRIBUSI RUMPUN MINAT RIASEC --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
                        Distribusi Rumpun Minat
                    </h2>
                    <p class="mt-1 text-[10px] text-slate-400">
                        Rata-rata skor 6 dimensi RIASEC seluruh siswa.
                    </p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-compass-drafting text-xs"></i>
                </div>
            </div>

            <div class="space-y-3.5">
                @foreach($riasecDistribution as $item)
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-[9px] font-black">
                                    {{ $item['code'] }}
                                </span>
                                <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                    {{ $item['name'] }}
                                </span>
                            </div>
                            <span class="text-[11px] font-black text-slate-700 dark:text-slate-300">
                                {{ $item['score'] }}
                            </span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-1000"
                                style="width: {{ $item['percent'] }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

       {{-- STATUS KALKULASI SAW --}}
<div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 text-white shadow-xl {{ $sawSelesaiSemua ? 'bg-gradient-to-br from-emerald-600 via-emerald-700 to-slate-900' : 'bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-900' }}">
    <div class="absolute -right-8 -bottom-8 text-white/10 pointer-events-none select-none">
        <i class="fa-solid fa-diagram-project text-[140px]"></i>
    </div>

    <div class="relative z-10">
        <span class="inline-flex items-center gap-2 text-[10px] uppercase tracking-wider font-bold bg-white/15 px-3 py-1 rounded-full border border-white/10">
            <i class="fa-solid fa-microchip"></i>
            Status Kalkulasi SAW
        </span>

        <h3 class="mt-4 text-lg font-black leading-snug">
            @if($sawSelesaiSemua)
                Seluruh siswa telah selesai diproses
            @else
                {{ $belumDiproses }} siswa menunggu perhitungan
            @endif
        </h3>

        <p class="mt-1.5 text-[11px] leading-relaxed text-white/80">
            @if($sawSelesaiSemua)
                Semua hasil asesmen pada periode aktif sudah dihitung dengan metode Simple Additive Weighting dan siap divalidasi.
            @else
                {{ $sudahAsesmen }} siswa telah menyelesaikan RIASEC, sebagian masih menunggu perhitungan peringkat rekomendasi.
            @endif
        </p>

        @if(Route::has('counselor.calculation.index'))
            <a href="{{ route('counselor.calculation.index') }}"
                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 px-4 py-2.5 text-xs font-bold shadow-sm backdrop-blur-md transition-all">
                Perhitungan & Rekomendasi
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        @endif
    </div>
</div>

        {{-- DONUT PROGRES ASESMEN --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
                    Progres Asesmen
                </h2>
                <p class="mt-1 text-[10px] text-slate-400">
                    Selesai vs belum mengerjakan RIASEC.
                </p>
            </div>

            @php
                $circumference = 2 * M_PI * 50; // r = 50
                $percent = min(100, max(0, $assessmentDonut['percent']));
                $dashOffset = $circumference * (1 - ($percent / 100));
            @endphp

            <div class="flex items-center gap-6">
                <div class="relative h-28 w-28 shrink-0">
                    <svg viewBox="0 0 120 120" class="h-full w-full -rotate-90">
                        <circle cx="60" cy="60" r="50" fill="none" stroke-width="10"
                            class="stroke-slate-100 dark:stroke-slate-800" />
                        <circle cx="60" cy="60" r="50" fill="none" stroke-width="10" stroke-linecap="round"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $dashOffset }}"
                            class="stroke-emerald-500 transition-all duration-1000" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-xl font-black text-slate-900 dark:text-white">{{ $percent }}%</span>
                        <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Selesai</span>
                    </div>
                </div>

                <div class="flex-1 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 dark:text-slate-300">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                        <span class="text-xs font-black text-slate-800 dark:text-white">{{ $assessmentDonut['completed'] }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 dark:text-slate-300">
                            <span class="h-2 w-2 rounded-full bg-slate-200 dark:bg-slate-700"></span>
                            Belum Mengerjakan
                        </span>
                        <span class="text-xs font-black text-slate-800 dark:text-white">{{ $assessmentDonut['pending'] }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


{{-- FOOTER INFO --}}
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-slate-950 dark:to-indigo-950 p-6 sm:p-7 text-white">
    <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-chalkboard-user"></i>
        </div>
        <div>
            <h3 class="text-sm font-black">
                Validasi hasil sebelum digunakan siswa
            </h3>
            <p class="mt-1 text-[11px] leading-relaxed text-slate-300 max-w-2xl">
                Rekomendasi hasil kalkulasi SAW bersifat pendukung keputusan. Pastikan setiap hasil sudah ditinjau sebelum didiskusikan bersama siswa dan orang tua.
            </p>
        </div>
    </div>
</section>

</div>
@endsection