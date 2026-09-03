@extends('counselor.layouts.app')

@section('title', 'Detail Siswa - ' . ($student->user->name ?? ''))

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-16">

    <!-- HEADER / BACK -->
    <div>
        <a href="{{ route('counselor.registration-periods.registrants', $period) }}"
           class="inline-flex items-center gap-2 text-[11px] font-semibold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Daftar Pendaftar
        </a>
    </div>

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
                        {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
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