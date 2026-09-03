@extends('student.layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-16 px-0 sm:px-0">

{{-- HERO --}}
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-violet-600 to-indigo-900 dark:from-indigo-950 dark:via-violet-950 dark:to-slate-950 text-white shadow-2xl">

    {{-- Background Decorative Elements --}}
    <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -right-16 -top-16 h-72 w-72 rounded-full bg-fuchsia-400/10 blur-3xl pointer-events-none"></div>
    <div class="absolute right-0 bottom-0 h-96 w-96 rounded-full bg-indigo-400/20 blur-3xl pointer-events-none"></div>
    <div class="absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-violet-400/10 blur-3xl pointer-events-none"></div>

    {{-- Decorative Grid --}}
    <div
        class="absolute inset-0 opacity-[0.08] pointer-events-none"
        style="background-image: linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px); background-size: 42px 42px;"
    ></div>

    {{-- Decorative Circles --}}
    <div class="absolute right-10 top-8 h-3 w-3 rounded-full bg-white/30 pointer-events-none"></div>
    <div class="absolute right-20 top-20 h-2 w-2 rounded-full bg-violet-200/30 pointer-events-none"></div>
    <div class="absolute right-32 bottom-14 h-4 w-4 rounded-full bg-indigo-200/20 pointer-events-none"></div>

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-r from-black/10 via-transparent to-white/5 pointer-events-none"></div>

    <div class="relative px-6 py-8 sm:px-10 sm:py-10 lg:px-12 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_260px] items-center gap-8 lg:gap-10">

            {{-- LEFT CONTENT --}}
            <div class="min-w-0 max-w-4xl">

                {{-- Badge Sapaan Waktu --}}
                <div class="mb-5">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 text-[11px] font-bold uppercase tracking-wider text-indigo-100 shadow-sm backdrop-blur-md sm:text-xs">
                        <i class="fa-solid {{ $greeting['icon'] }}"></i>
                        {{ $greeting['text'] }}
                    </span>
                </div>

                {{-- Judul --}}
                <h1 class="flex flex-wrap items-center gap-x-3 gap-y-2 text-3xl font-black leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                    <span>Halo, {{ $student->user->name ?? 'Siswa' }}</span>

                    {{-- Emoji Melambai --}}
                    <span class="animate-wave inline-block origin-[70%_70%] text-3xl sm:text-4xl">
                        👋
                    </span>
                </h1>

                {{-- Subtitle --}}
                <p class="mt-4 max-w-2xl text-sm font-medium leading-relaxed text-indigo-100/90 sm:text-base">
                    {{ $heroSubtitle }}
                </p>

                {{-- Informasi Akademik --}}
                <div class="mt-6 flex flex-wrap items-center gap-3">

                    <span class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 text-xs font-semibold shadow-sm backdrop-blur-md">
                        <i class="fa-solid fa-calendar text-indigo-200"></i>
                        Tahun Ajaran {{ $academicYear }}
                    </span>

                    @if($student->class)
                        <span class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 text-xs font-semibold shadow-sm backdrop-blur-md">
                            <i class="fa-solid fa-school text-indigo-200"></i>
                            Kelas {{ $student->class }}
                        </span>
                    @endif

                </div>
            </div>

            {{-- RIGHT DECORATIVE AREA --}}
            <div class="relative hidden h-52 lg:block pointer-events-none">

                <div class="absolute right-0 top-1/2 h-48 w-48 -translate-y-1/2 rounded-full bg-white/10 blur-3xl"></div>

                <div class="absolute right-2 top-1/2 h-40 w-40 -translate-y-1/2 rounded-full border border-white/10 bg-white/[0.04] backdrop-blur-sm"></div>

                <div class="absolute right-10 top-1/2 h-24 w-24 -translate-y-1/2 rounded-full border border-white/10 bg-white/[0.05]"></div>

                <div class="absolute right-0 top-8 h-px w-28 bg-gradient-to-l from-white/20 to-transparent"></div>
                <div class="absolute right-0 bottom-8 h-px w-36 bg-gradient-to-l from-white/20 to-transparent"></div>

                <div class="absolute right-16 top-5 h-2 w-2 rounded-full bg-white/25"></div>
                <div class="absolute right-36 top-14 h-1.5 w-1.5 rounded-full bg-indigo-200/40"></div>
                <div class="absolute right-20 bottom-5 h-2.5 w-2.5 rounded-full bg-violet-200/30"></div>
                <div class="absolute right-44 bottom-16 h-1.5 w-1.5 rounded-full bg-white/20"></div>

                <div class="absolute right-20 top-1/2 h-14 w-14 -translate-y-1/2 rotate-45 rounded-2xl border border-white/10 bg-gradient-to-br from-white/10 to-transparent"></div>

                <div class="absolute right-7 top-1/2 h-5 w-5 -translate-y-1/2 rounded-full border border-white/20 bg-white/10"></div>
            </div>

        </div>
    </div>
</section>

{{-- Style CSS Animasi --}}
@push('styles')
<style>
@keyframes wave {
    0% {
        transform: rotate(0deg);
    }

    10% {
        transform: rotate(14deg);
    }

    20% {
        transform: rotate(-8deg);
    }

    30% {
        transform: rotate(14deg);
    }

    40% {
        transform: rotate(-8deg);
    }

    50% {
        transform: rotate(14deg);
    }

    60% {
        transform: rotate(-4deg);
    }

    70% {
        transform: rotate(10deg);
    }

    80% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(0deg);
    }
}

/* Pastikan animasi berjalan */
.animate-wave {
    display: inline-block;
    animation: wave 1.8s ease-in-out infinite;
    transform-origin: 70% 70%;
    will-change: transform;
}
</style>
@endpush

    {{-- PROGRESS ASESMEN --}}
    <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">

        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">
                        Progress Asesmen
                    </h2>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Selesaikan setiap tahapan untuk mendapatkan hasil analisis.
                    </p>
                </div>

                <div class="sm:text-right">
                    <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400">
                        {{ $progress }}%
                    </span>
                    <span class="block text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        Selesai
                    </span>
                </div>

            </div>

            <div class="mt-5 h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div
                    class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-violet-500 to-emerald-500 transition-all duration-1000"
                    style="width: {{ $progress }}%">
                </div>
            </div>
        </div>


        <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-3 gap-3">

            @foreach($steps as $index => $step)
                <a
                    href="{{ $step['route'] }}"
                    class="group relative p-4 rounded-2xl border {{ $step['completed'] ? 'border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/50 dark:bg-emerald-950/20' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40' }} hover:-translate-y-1 hover:shadow-md transition-all duration-300">

                    <div class="flex items-start justify-between gap-3">

                        <div class="w-11 h-11 rounded-xl flex items-center justify-center {{ $step['completed'] ? 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' }}">
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                        </div>

                        @if($step['completed'])
                            <span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </span>
                        @else
                            <span class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-400 flex items-center justify-center">
                                <span class="text-[10px] font-black">
                                    {{ $index + 1 }}
                                </span>
                            </span>
                        @endif

                    </div>

                    <h3 class="mt-4 text-sm font-black text-slate-800 dark:text-white">
                        {{ $step['title'] }}
                    </h3>

                    <p class="mt-1.5 text-[11px] leading-relaxed text-slate-500 dark:text-slate-400">
                        {{ $step['description'] }}
                    </p>

                    <div class="mt-4 flex items-center gap-1.5 text-[10px] font-bold {{ $step['completed'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-indigo-600 dark:text-indigo-400' }}">
                        {{ $step['completed'] ? 'Selesai' : 'Lihat Sekarang' }}
                        <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                    </div>

                </a>
            @endforeach

        </div>
    </section>


   {{-- RIASEC --}}
@if($riasec)

<section class="grid grid-cols-1 lg:grid-cols-5 gap-5">

    <div class="lg:col-span-2 bg-gradient-to-br from-violet-600 via-indigo-600 to-indigo-800 dark:from-violet-950 dark:via-indigo-950 dark:to-slate-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden flex flex-col justify-between border border-white/10">

        <!-- Watermark Icon Besar di Background agar tidak kosong -->
        <div class="absolute -right-6 -bottom-6 text-white/5 pointer-events-none select-none">
            <i class="fa-solid fa-compass-drafting text-[180px]"></i>
        </div>

        <!-- Efek Blur Cahaya Dekoratif -->
        <div class="absolute -left-10 -top-10 w-40 h-40 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

        <!-- Konten Utama -->
        <div class="relative z-10 flex-1">
            <span class="inline-flex items-center gap-2 text-[10px] uppercase tracking-wider font-bold text-violet-200 bg-white/10 px-3 py-1 rounded-full border border-white/10">
                <i class="fa-solid fa-brain"></i>
                Profil Minat RIASEC
            </span>

            <div class="mt-4">
                <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-white drop-shadow-sm">
                    {{ $riasec->dominant_code ?? $dominantCode }}
                </h2>
                <p class="text-xs text-violet-100/80 mt-1">Kombinasi tipe kepribadian dan minat dominan Anda.</p>
            </div>

            <div class="mt-6 pt-5 border-t border-white/15">
                <p class="text-[10px] uppercase tracking-wider font-bold text-violet-200 mb-2">
                    Kecenderungan Utama
                </p>

                <div class="flex flex-wrap gap-2">
                    @foreach($dominantRiasec->take(3) as $item)
                        <span class="px-3 py-1.5 rounded-xl bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold shadow-sm">
                            {{ $item['name'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tombol Aksi di Bawah -->
        <div class="relative z-10 mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
            <a href="{{ route('student.riasec.index') }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-white hover:text-violet-200 transition-colors group">
                <span>Lihat Detail Lengkap</span>
                <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
            </a>
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                <i class="fa-solid fa-chart-pie text-xs text-violet-200"></i>
            </div>
        </div>

    </div>

        <div class="lg:col-span-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">

            <div class="flex items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
                        Hasil Enam Dimensi
                    </h2>
                    <p class="mt-1 text-[10px] text-slate-400">
                        Skor berdasarkan asesmen minat yang telah dikerjakan.
                    </p>
                </div>

                <a
                    href="{{ route('student.riasec.index') }}"
                    class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-indigo-600 flex items-center justify-center transition">
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                </a>
            </div>

            @php
                // Skala visual relatif terhadap skor tertinggi siswa sendiri,
                // supaya batang tidak terlihat "kosong" walau angka asli kecil.
                // Angka yang ditampilkan di sisi kanan tetap skor asli (tidak diubah).
                $riasecMax = collect($riasecData)->max('score') ?: 1;
            @endphp

            <div class="space-y-4">
                @foreach($riasecData as $item)
                    @php
                        $visualPercent = $riasecMax > 0
                            ? max(14, min(98, round(($item['score'] / $riasecMax) * 98)))
                            : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-[10px] font-black">
                                    {{ $item['code'] }}
                                </span>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                    {{ $item['name'] }}
                                </span>
                            </div>

                            <span class="text-xs font-black text-slate-700 dark:text-slate-300">
                                {{ $item['score'] }}
                            </span>
                        </div>

                        <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-1000"
                                style="width: {{ $visualPercent }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </section>

    @else

    <section class="relative overflow-hidden rounded-3xl border border-violet-200 dark:border-violet-900/50 bg-violet-50 dark:bg-violet-950/20 p-6 sm:p-8">

        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-5">

            <div class="w-14 h-14 rounded-2xl bg-violet-600 text-white flex items-center justify-center text-xl shadow-lg shadow-violet-600/20 shrink-0">
                <i class="fa-solid fa-brain"></i>
            </div>

            <div class="flex-1">
                <span class="text-[10px] uppercase tracking-wider font-bold text-violet-600 dark:text-violet-400">
                    Tes Minat Belum Dikerjakan
                </span>

                <h2 class="mt-1 text-lg font-black text-slate-900 dark:text-white">
                    Kenali kecenderungan minat Anda
                </h2>

                <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    Kerjakan 60 pertanyaan RIASEC untuk mengetahui profil minat
                    dan membantu sistem memberikan rekomendasi jurusan.
                </p>

                <a
                    href="{{ route('student.riasec.index') }}"
                    class="inline-flex items-center gap-2 mt-4 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-lg shadow-violet-600/20 transition-all">
                    Mulai Tes RIASEC
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </section>

    @endif

{{-- REKOMENDASI JURUSAN --}}
<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
    {{-- HEADER --}}
    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800 sm:px-6">
        <div>
            <span class="text-[9px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                Hasil Analisis
            </span>
            <h2 class="mt-1 text-base font-extrabold text-slate-900 dark:text-white">
                Rekomendasi Jurusan Unggulan
            </h2>
        </div>
        <a href="{{ route('student.recommendations.index') }}" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-indigo-600 flex items-center justify-center transition">
            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
        </a>
    </div>

    @if(isset($perfectRecommendations) && $perfectRecommendations->count() > 0)
        @php
            $topScore = $perfectRecommendations->first()['score'] ?? 0;
            $topScoreRaw = $topScore <= 1 ? $topScore * 100 : $topScore;
            $topScoreDisplay = number_format($topScoreRaw, 2, '.', '');
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-[230px_minmax(0,1fr)]">
            {{-- SCORE PANEL --}}
            <div class="relative flex flex-col items-center justify-center border-b border-slate-100 bg-slate-50/70 px-6 py-7 dark:border-slate-800 dark:bg-slate-950/40 lg:border-b-0 lg:border-r">
                <div class="relative h-32 w-32">
                    <svg viewBox="0 0 120 120" class="h-full w-full -rotate-90">
                        <circle cx="60" cy="60" r="50" fill="none" stroke-width="9" class="stroke-slate-200 dark:stroke-slate-800" />
                        <circle cx="60" cy="60" r="50" fill="none" stroke-width="9" stroke-linecap="round" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * min(100, max(0, $topScoreRaw)) / 100) }}" class="stroke-emerald-500 transition-all duration-500" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white">
                            {{ $topScoreDisplay }}%
                        </span>
                        <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">
                            Kecocokan
                        </span>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 dark:bg-emerald-500/10">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                            Sangat Direkomendasikan
                        </span>
                    </div>
                    <p class="mt-2 text-[10px] leading-relaxed text-slate-400">
                        Hasil berdasarkan kecocokan kriteria dan bobot analisis.
                    </p>
                </div>
            </div>

            {{-- MAJOR LIST --}}
            <div class="p-4 sm:p-5">
                <div class="mb-3 flex items-center justify-between">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                        Daftar Jurusan
                    </span>
                    <span class="text-[9px] font-semibold text-slate-400">
                        Skor tertinggi
                    </span>
                </div>

                <div class="space-y-2.5">
                    @foreach($perfectRecommendations as $index => $rec)
                        @php
                            $itemScore = $rec['score'] ?? 0;
                            $itemScoreRaw = $itemScore <= 1 ? $itemScore * 100 : $itemScore;
                            $itemScoreDisplay = number_format($itemScoreRaw, 2, '.', '');
                        @endphp

                        <a href="{{ route('student.recommendations.index') }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white px-3.5 py-3 transition-all duration-200 hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-indigo-500/40">
                            {{-- Ranking --}}
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-[10px] font-black text-slate-500 transition-colors group-hover:bg-indigo-600 group-hover:text-white dark:bg-slate-800 dark:text-slate-400 dark:group-hover:bg-indigo-500 dark:group-hover:text-white">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            {{-- Major --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[8px] font-black uppercase tracking-wider text-indigo-500 dark:text-indigo-400">
                                        {{ $rec['degree'] ?? 'S1' }}
                                    </span>
                                    @if($index === 0)
                                        <span class="rounded bg-amber-50 px-1.5 py-0.5 text-[7px] font-bold text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                                            TOP
                                        </span>
                                    @endif
                                </div>
                                <h3 class="mt-0.5 truncate text-xs font-extrabold text-slate-800 transition-colors group-hover:text-indigo-600 dark:text-slate-100 dark:group-hover:text-indigo-400 sm:text-sm" title="{{ trim(($rec['degree'] ?? '') . ' ' . ($rec['major_name'] ?? '')) }}">
                                    {{ $rec['major_name'] ?? 'Jurusan' }}
                                </h3>
                            </div>

                            {{-- Score --}}
                            <div class="hidden shrink-0 text-right sm:block">
                                <div class="text-xs font-black text-emerald-600 dark:text-emerald-400">
                                    {{ $itemScoreDisplay }}%
                                </div>
                                <div class="text-[8px] font-semibold text-slate-400">
                                    Match
                                </div>
                            </div>

                            {{-- Arrow --}}
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-400 transition-all group-hover:bg-indigo-600 group-hover:text-white dark:bg-slate-800 dark:group-hover:bg-indigo-500">
                                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        {{-- EMPTY STATE --}}
        <div class="flex min-h-[190px] items-center justify-center px-5 py-8">
            <div class="flex flex-col items-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 shadow-sm">
                    <i class="fa-solid fa-chart-column text-lg"></i>
                </div>
                <p class="mt-3 text-xs font-semibold text-slate-500 dark:text-slate-400">
                    Belum ada hasil rekomendasi.
                </p>
            </div>
        </div>
    @endif

</section>
 {{-- ARTIKEL --}}
<section>

    <div class="flex items-end justify-between gap-3 mb-4">
        <div>
            <span class="text-[10px] uppercase tracking-wider font-bold text-indigo-600 dark:text-indigo-400">
                Informasi
            </span>

            <h2 class="mt-1 text-base sm:text-lg font-black text-slate-900 dark:text-white">
                Artikel/Panduan Terbaru
            </h2>
        </div>

        @if($articles->count() && \Illuminate\Support\Facades\Route::has('student.articles.index'))
            <a href="{{ route('student.articles.index') }}"
                class="inline-flex items-center gap-1.5 text-[10px] sm:text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:gap-2.5 transition-all">
                Lihat semua
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        @endif
    </div>

    @if($articles->count())

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

            @foreach($articles as $article)

                {{-- Seluruh card dibungkus tag <a> agar bisa diklik di area mana saja --}}
                <a href="{{ route('student.articles.show', $article->slug) }}" 
                   class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between block">

                    <div>
                        <div class="h-1 bg-gradient-to-r from-indigo-500 to-violet-500"></div>

                        <div class="p-4">

                            <div class="flex items-center gap-2 text-[9px] font-bold uppercase tracking-wider text-slate-400">
                                <i class="fa-regular fa-calendar"></i>
                                {{ optional($article->published_at)->format('d M Y') }}
                            </div>

                            <h3 class="mt-3 text-sm font-black leading-snug text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $article->title }}
                            </h3>

                            <p class="mt-2 text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 100) }}
                            </p>

                        </div>
                    </div>

                    <div class="px-4 pb-4 pt-0">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-indigo-600 dark:text-indigo-400">
                            Baca selengkapnya
                            <i class="fa-solid fa-arrow-right text-[9px] group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </div>

                </a>

            @endforeach

        </div>

    @else

        <div class="bg-white dark:bg-slate-900 border border-dashed border-slate-300 dark:border-slate-700 rounded-2xl p-8 text-center">
            <i class="fa-regular fa-newspaper text-2xl text-slate-300 dark:text-slate-700"></i>
            <p class="mt-3 text-xs font-semibold text-slate-500 dark:text-slate-400">
                Belum ada artikel terbaru.
            </p>
        </div>

    @endif

</section>


    {{-- FOOTER INFO --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-slate-950 dark:to-indigo-950 p-6 sm:p-7 text-white">

        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">

            <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user-graduate"></i>
            </div>

            <div>
                <h3 class="text-sm font-black">
                    Gunakan hasil sebagai bahan pertimbangan
                </h3>

                <p class="mt-1 text-[11px] leading-relaxed text-slate-300 max-w-2xl">
                    Rekomendasi sistem digunakan sebagai bahan pertimbangan dalam
                    menentukan pilihan jurusan. Diskusikan hasil akhir bersama Guru BK.
                </p>
            </div>

        </div>

    </section>

</div>
@endsection