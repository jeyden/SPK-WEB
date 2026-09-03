@extends('counselor.layouts.app')

@section('title', 'Detail Penilaian RIASEC Siswa')

@section('content')
<div class="max-w-5xl mx-auto px-3 sm:px-5 lg:px-0 py-4 sm:py-6 space-y-4 sm:space-y-5 pb-12">

    {{-- NAVIGASI --}}
    <div>
        <a href="{{ route('counselor.assessments.index', ['academic_year' => $academicYear]) }}"
           class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
            </span>
            Kembali ke Daftar Penilaian
        </a>
    </div>

    {{-- PROFIL SISWA --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
        <div class="p-4 sm:p-5 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                <div class="flex items-start gap-3.5 min-w-0">
                    <div class="flex items-center justify-center w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                        <i class="fa-solid fa-user-graduate text-indigo-600 dark:text-indigo-400 text-base"></i>
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-[0.16em] text-indigo-600 dark:text-indigo-400">
                                Penilaian RIASEC
                            </span>
                            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                            <span class="text-[10px] sm:text-[11px] font-medium text-slate-400 dark:text-slate-500">
                                Profil Siswa
                            </span>
                        </div>

                        <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white leading-tight break-words">
                            {{ optional($student->user)->name ?? 'Tanpa Nama' }}
                        </h1>

                        @php
                            $majorLabel = $student->high_school_major ?? null;
                            $classLabel = $student->class_name ?? $student->kelas ?? null;
                            $classInfo = collect([$majorLabel, $classLabel])->filter()->implode(' / ');
                        @endphp

                        <div class="flex flex-wrap gap-x-4 gap-y-1.5 mt-2 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">
                            <span>
                                NISN:
                                <b class="font-mono text-slate-700 dark:text-slate-300">{{ $student->nisn ?? '-' }}</b>
                            </span>

                            @if ($classInfo)
                                <span>
                                    Jurusan / Kelas:
                                    <b class="text-slate-700 dark:text-slate-300">{{ $classInfo }}</b>
                                </span>
                            @endif

                            <span>
                                Tahun Ajaran:
                                <b class="text-slate-700 dark:text-slate-300">{{ $academicYear }}</b>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 self-start lg:self-center">
                    <i class="fa-solid fa-id-card text-indigo-500 dark:text-indigo-400 text-xs"></i>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-600 dark:text-slate-300">
                        Data Siswa
                    </span>
                </div>

            </div>
        </div>
    </section>

    {{-- HASIL RIASEC --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
        <div class="p-4 sm:p-5 lg:p-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                        <i class="fa-solid fa-chart-pie text-indigo-600 dark:text-indigo-400 text-sm"></i>
                    </div>

                    <div>
                        <h2 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">
                            Hasil Asesmen RIASEC
                        </h2>
                        <p class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                            24 pernyataan · 6 dimensi · skala 1–5
                        </p>
                    </div>
                </div>
            </div>

            @if ($score)
                @php
                    $dimensions = [
                        ['code' => 'R', 'label' => 'Realistic', 'value' => (float) ($score->r_score ?? 0)],
                        ['code' => 'I', 'label' => 'Investigative', 'value' => (float) ($score->i_score ?? 0)],
                        ['code' => 'A', 'label' => 'Artistic', 'value' => (float) ($score->a_score ?? 0)],
                        ['code' => 'S', 'label' => 'Social', 'value' => (float) ($score->s_score ?? 0)],
                        ['code' => 'E', 'label' => 'Enterprising', 'value' => (float) ($score->e_score ?? 0)],
                        ['code' => 'K', 'label' => 'Conventional', 'value' => (float) ($score->c_score ?? 0)],
                    ];

                    $tsk = (int) round($score->tsk ?? array_sum(array_column($dimensions, 'value')));

                    if ($tsk >= 97) {
                        $tskLevel = 'Sangat Tinggi';
                        $tskDescription = 'Profil RIASEC siswa menunjukkan kekuatan yang sangat tinggi pada keseluruhan enam dimensi minat.';
                        $tskBadge = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300';
                    } elseif ($tsk >= 73) {
                        $tskLevel = 'Tinggi';
                        $tskDescription = 'Profil RIASEC siswa menunjukkan kekuatan yang tinggi pada keseluruhan enam dimensi minat.';
                        $tskBadge = 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300';
                    } elseif ($tsk >= 49) {
                        $tskLevel = 'Sedang';
                        $tskDescription = 'Profil RIASEC siswa menunjukkan kekuatan yang sedang pada keseluruhan enam dimensi minat.';
                        $tskBadge = 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300';
                    } else {
                        $tskLevel = 'Rendah';
                        $tskDescription = 'Profil RIASEC siswa menunjukkan kekuatan yang masih rendah pada keseluruhan enam dimensi minat.';
                        $tskBadge = 'bg-slate-100 text-slate-700 dark:bg-slate-500/15 dark:text-slate-300';
                    }
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5 sm:gap-3">
                    @foreach ($dimensions as $dim)
                        @php
                            $percent = $dim['value'] > 0
                                ? min(100, round(($dim['value'] / 20) * 100))
                                : 0;
                        @endphp

                        <div class="group rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/70 p-3 sm:p-3.5 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition">

                            <div class="flex items-center justify-between gap-2">
                                <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-500/15 text-[10px] font-black text-indigo-700 dark:text-indigo-300">
                                    {{ $dim['code'] }}
                                </span>

                                <span class="text-[9px] sm:text-[10px] font-semibold text-slate-400 dark:text-slate-500 truncate">
                                    {{ $dim['label'] }}
                                </span>
                            </div>

                            <div class="mt-3">
                                <div class="flex items-end justify-between gap-1">
                                    <span class="text-lg sm:text-xl font-black text-slate-900 dark:text-white leading-none">
                                        {{ $dim['value'] }}
                                    </span>
                                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500">
                                        /20
                                    </span>
                                </div>

                                <div class="mt-2.5 h-1.5 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                                    <div class="h-full rounded-full bg-indigo-500 dark:bg-indigo-400 transition-all"
                                         style="width: {{ $percent }}%"></div>
                                </div>

                                <div class="mt-1.5 text-right text-[9px] font-semibold text-slate-400 dark:text-slate-500">
                                    {{ $percent }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- TSK --}}
                <div class="mt-4 rounded-xl border border-indigo-100 dark:border-indigo-500/20 bg-indigo-50/70 dark:bg-indigo-500/10 p-3.5 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div class="flex items-start gap-3 min-w-0">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-white dark:bg-slate-900 border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                                <i class="fa-solid fa-chart-simple text-indigo-600 dark:text-indigo-400 text-sm"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-slate-200">
                                    Kekuatan Profil RIASEC
                                </p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                                    Total Skor Komposit menunjukkan tingkat kekuatan keseluruhan profil RIASEC siswa.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-extrabold {{ $tskBadge }}">
                                {{ $tskLevel }}
                            </span>

                            <div class="text-right">
                                <p class="text-xl sm:text-2xl font-black text-indigo-700 dark:text-indigo-400 leading-none">
                                    {{ $tsk }}
                                    <span class="text-xs sm:text-sm font-semibold text-slate-400 dark:text-slate-500">/120</span>
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="mt-3 pt-3 border-t border-indigo-100 dark:border-indigo-500/20">
                        <p class="text-[10px] sm:text-[11px] text-indigo-800 dark:text-indigo-300 leading-relaxed">
                            {{ $tskDescription }}
                        </p>
                    </div>
                </div>

            @else

                <div class="flex items-start gap-3 rounded-xl border border-amber-200 dark:border-amber-500/20 bg-amber-50 dark:bg-amber-500/10 p-3.5 sm:p-4">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/80 dark:bg-amber-500/10 shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 dark:text-amber-400 text-xs"></i>
                    </div>

                    <p class="text-[11px] sm:text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                        Siswa ini belum menyelesaikan asesmen RIASEC (24 pernyataan). Hasil rekomendasi SAW tidak dapat dihitung sebelum asesmen selesai.
                    </p>
                </div>

            @endif
        </div>
    </section>

    {{-- STATUS REKOMENDASI --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
        <div class="p-4 sm:p-5 lg:p-6">

            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 shrink-0">
                    <i class="fa-solid fa-ranking-star text-emerald-600 dark:text-emerald-400 text-sm"></i>
                </div>

                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">
                        Status Hasil Rekomendasi
                    </h2>
                    <p class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                        Detail peringkat program studi tersedia di menu Perhitungan
                    </p>
                </div>
            </div>

            @if ($recommendation)

                <div class="rounded-xl border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50/70 dark:bg-emerald-500/10 p-3.5 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div class="flex items-start gap-3 min-w-0">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-500/20 shrink-0">
                                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-xs"></i>
                            </div>

                            <div>
                                <p class="text-xs sm:text-sm font-extrabold text-emerald-800 dark:text-emerald-300">
                                    Perhitungan telah selesai
                                </p>
                                <p class="text-[10px] sm:text-[11px] text-emerald-700/80 dark:text-emerald-300/80 mt-1 leading-relaxed">
                                    Perhitungan SAW untuk siswa ini sudah diproses dan hasil rekomendasi tersedia.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('counselor.calculation.report', $student->id) }}"
                           class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] sm:text-xs font-bold shadow-sm transition whitespace-nowrap">
                            <i class="fa-solid fa-file-lines"></i>
                            Lihat Hasil Rekomendasi
                        </a>

                    </div>
                </div>

            @else

                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3.5 sm:p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shrink-0">
                            <i class="fa-solid fa-circle-info text-slate-400 dark:text-slate-500 text-xs"></i>
                        </div>

                        <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Belum ada hasil rekomendasi untuk siswa ini. Jalankan "Proses Perhitungan" pada menu Perhitungan setelah asesmen RIASEC selesai.
                        </p>
                    </div>
                </div>

            @endif
        </div>
    </section>

</div>
@endsection