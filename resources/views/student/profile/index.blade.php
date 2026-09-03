@extends('student.layouts.app')

@section('title', 'Profil & Data Diri Siswa')

@section('content')

<div class="max-w-5xl mx-auto space-y-6 pb-16 px-0 sm:px-0">

{{-- HEADER PROFIL & DATA DIRI --}}
<section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <div class="pointer-events-none absolute -right-16 -top-20 h-48 w-48 rounded-full bg-indigo-500/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-20 left-1/3 h-40 w-40 rounded-full bg-violet-500/10 blur-3xl"></div>

    <div class="relative flex flex-col gap-4 px-5 py-5 sm:px-6 md:flex-row md:items-center md:justify-between">
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                    <i class="fa-solid fa-user text-xs"></i>
                </span>

                <span class="text-[9px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                    Data Siswa
                </span>
            </div>

            <h1 class="mt-2 text-lg font-black tracking-tight text-slate-900 dark:text-white sm:text-xl">
                Profil & Data Diri
            </h1>

            <p class="mt-1 max-w-2xl text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                Informasi pribadi dan akademik yang terdaftar dalam sistem.
            </p>
        </div>

        @if ($period && $period->isOpen())
            <a href="{{ route('student.profile.edit') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-sm shadow-indigo-600/20 transition-colors w-full sm:w-auto self-start md:self-center">
                <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                Perbarui Data
            </a>
        @else
            <span class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-400 dark:text-slate-500 text-xs font-semibold w-full sm:w-auto self-start md:self-center cursor-not-allowed">
                <i class="fa-solid fa-lock text-[11px]"></i>
                Pengisian Terkunci
            </span>
        @endif
    </div>
</section>

{{-- BANNER STATUS PERIODE PENDAFTARAN --}}
@if (session('period_locked'))
    <div class="px-4 py-3.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 text-xs sm:text-sm flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation text-base shrink-0"></i>
        <span>{{ session('period_locked') }}</span>
    </div>
@elseif ($period && $period->isNotOpenedYet())
    <div class="px-4 py-3.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 text-xs sm:text-sm flex items-center gap-2">
        <i class="fa-solid fa-clock text-base shrink-0"></i>
        <span>Periode pendaftaran untuk tahun akademik {{ $period->academic_year }} belum dibuka. Pengisian data diri akan tersedia setelah Guru BK membuka pendaftaran.</span>
    </div>
@elseif ($period && $period->isClosed())
    <div class="px-4 py-3.5 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 text-xs sm:text-sm flex items-center gap-2">
        <i class="fa-solid fa-lock text-base shrink-0"></i>
        <span>Periode pendaftaran untuk tahun akademik {{ $period->academic_year }} telah ditutup. Pengisian dan perubahan data diri tidak dapat dilakukan lagi.</span>
    </div>
@elseif (!$period)
    <div class="px-4 py-3.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 text-xs sm:text-sm flex items-center gap-2">
        <i class="fa-solid fa-clock text-base shrink-0"></i>
        <span>Belum ada periode pendaftaran yang dijadwalkan. Silakan hubungi Guru BK.</span>
    </div>
@endif

<!-- Notifikasi Sukses Onboarding (Profil Tersimpan + Ajakan RIASEC) -->
@if (session('success_onboarding'))
    <div class="px-4 py-3.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-xs sm:text-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-indigo-600 dark:text-indigo-400 text-base shrink-0"></i>
            <span>{{ session('success_onboarding') }}</span>
        </div>
        <a href="{{ route('student.riasec.index') }}" class="inline-flex items-center justify-center px-3.5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-colors shrink-0 shadow-sm">
            Mulai Asesmen RIASEC <i class="fa-solid fa-arrow-right ml-1.5"></i>
        </a>
    </div>
@endif

<!-- Notifikasi Sukses Biasa (Bukan Onboarding) -->
@if (session('success'))
    <div class="px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-base shrink-0"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Profile Card -->
<div class="bg-white dark:bg-slate-900/70 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">

    <!-- Profile Identity -->
    <div class="px-5 sm:px-8 pt-7 pb-6 text-center border-b border-slate-200 dark:border-slate-800">
        <div class="mx-auto w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-950 border-4 border-white dark:border-slate-800 ring-1 ring-slate-200 dark:ring-slate-700 shadow-sm flex items-center justify-center">
            @php
                $avatarPath = $student->avatar ?? ($student->user->avatar ?? null);
            @endphp

            @if(!empty($avatarPath))
                <img src="{{ asset('storage/' . $avatarPath) }}"
                     alt="Foto Profil"
                     class="w-full h-full object-cover">
            @else
                <span class="text-2xl sm:text-3xl font-bold text-slate-500 dark:text-slate-400">
                    {{ strtoupper(substr($student->user->name ?? ($user->name ?? 'S'), 0, 1)) }}
                </span>
            @endif
        </div>

        <h3 class="mt-4 text-base sm:text-lg font-bold text-slate-800 dark:text-white">
            {{ $student->user->name ?? '-' }}
        </h3>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            NISN {{ $student->nisn ?? '-' }}
        </p>

        <span class="inline-flex mt-3 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[11px] font-semibold">
            {{ $student->high_school_major ?? '-' }}
        </span>
    </div>

    <!-- Personal Information -->
    <div class="px-5 sm:px-8 py-6">
        <div class="mb-5">
            <h4 class="text-sm font-bold text-slate-800 dark:text-white">
                Informasi Pribadi
            </h4>
            <div class="mt-2 h-px bg-slate-100 dark:bg-slate-800"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5 text-xs sm:text-sm">

            <div>
                <span class="block mb-1 text-slate-400 dark:text-slate-500">
                    Jenis Kelamin
                </span>
                <p class="font-semibold text-slate-700 dark:text-slate-200">
                    {{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : '-') }}
                </p>
            </div>

            <div>
                <span class="block mb-1 text-slate-400 dark:text-slate-500">
                    Nomor Telepon / WhatsApp
                </span>
                <p class="font-semibold text-slate-700 dark:text-slate-200 break-words">
                    {{ $student->phone ?? '-' }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <span class="block mb-1 text-slate-400 dark:text-slate-500">
                    Alamat Lengkap
                </span>
                <p class="font-semibold text-slate-700 dark:text-slate-200 leading-relaxed">
                    {{ $student->address ?? '-' }}
                </p>
            </div>

        </div>
    </div>

    <!-- Academic Information -->
    <div class="px-5 sm:px-8 py-6 border-t border-slate-200 dark:border-slate-800">
        <div class="mb-5">
            <h4 class="text-sm font-bold text-slate-800 dark:text-white">
                Informasi Akademik
            </h4>
            <div class="mt-2 h-px bg-slate-100 dark:bg-slate-800"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-8 gap-y-5 text-xs sm:text-sm">

            <div>
                <span class="block mb-1 text-slate-400 dark:text-slate-500">
                    NISN
                </span>
                <p class="font-semibold text-slate-700 dark:text-slate-200">
                    {{ $student->nisn ?? '-' }}
                </p>
            </div>

            <div>
                <span class="block mb-1 text-slate-400 dark:text-slate-500">
                    Kelas
                </span>
                <p class="font-semibold text-slate-700 dark:text-slate-200">
                    {{ $student->class ?? '-' }}
                </p>
            </div>

            <div>
                <span class="block mb-1 text-slate-400 dark:text-slate-500">
                    Jurusan Sekolah
                </span>
                <p class="font-semibold text-slate-700 dark:text-slate-200">
                    {{ $student->high_school_major ?? '-' }}
                </p>
            </div>

        </div>
    </div>

</div>

</div>
@endsection