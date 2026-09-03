@extends('counselor.layouts.app')

@section('title', 'Ringkasan Penilaian RIASEC')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-16">

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-4">
        <a href="{{ route('counselor.assessments.index', ['academic_year' => $academicYear]) }}"
           class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Penilaian</span>
        </a>
    </div>

    <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6">
        <span class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
            Ringkasan Penilaian RIASEC
        </span>
        <h1 class="text-lg font-extrabold text-slate-900 dark:text-white mt-1">
            {{ optional($student->user)->name ?? 'Tanpa Nama' }}
        </h1>
        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-slate-500 dark:text-slate-400">
            <span>NISN: <b class="font-mono text-slate-700 dark:text-slate-300">{{ $student->nisn ?? '-' }}</b></span>
            <span>Tahun Ajaran: <b class="text-slate-700 dark:text-slate-300">{{ $academicYear }}</b></span>
        </div>

        <div class="mt-5 pt-5 border-t border-slate-200 dark:border-slate-800">
            @if ($recommendation)
                @php
                    $major = $recommendation->major;
                    $finalCampus = $recommendation->finalCampus;
                    $scorePercent = round(((float) ($recommendation->preference_score ?? 0)) * 100);
                @endphp
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wide mb-1">Program Studi Terbaik</p>
                        <p class="text-sm font-extrabold text-slate-900 dark:text-white">{{ optional($major)->name ?? '-' }}</p>
                        @if ($finalCampus)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $finalCampus->name }}</p>
                        @endif
                    </div>
                    <span class="px-3 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 text-sm font-black">
                        {{ $scorePercent }}%
                    </span>
                </div>
            @else
                <p class="text-xs text-slate-400 dark:text-slate-500 italic">Belum ada hasil rekomendasi untuk siswa ini.</p>
            @endif
        </div>

        <div class="mt-5">
            <a href="{{ route('counselor.assessments.assess', ['student' => $student->id, 'academic_year' => $academicYear]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-chart-pie"></i>
                Lihat Detail Skor RIASEC
            </a>
        </div>
    </section>
</div>
@endsection