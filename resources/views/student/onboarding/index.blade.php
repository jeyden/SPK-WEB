@extends('student.layouts.app')

@section('title', 'Langkah Awal & Validasi Data')

@section('content')
<div class="mx-auto max-w-5xl space-y-5 pb-10">

    {{-- HEADER --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-700 p-6 text-white shadow-lg shadow-indigo-500/10 sm:p-8">
        <div class="relative z-10 max-w-3xl">
            <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/10">
                    <i class="fa-solid fa-shield-halved text-[11px] text-indigo-100"></i>
                </span>
                <span class="text-[9px] font-black uppercase tracking-[0.18em] text-indigo-200">
                    Persiapan Profil
                </span>
            </div>

            <h1 class="mt-3 text-xl font-black tracking-tight sm:text-2xl">
                Lengkapi Profil Akademik Anda
            </h1>

            <p class="mt-2 max-w-2xl text-xs leading-relaxed text-indigo-100 sm:text-sm">
                Lengkapi data diri dan asesmen RIASEC untuk membuka akses penuh ke analisis serta rekomendasi jurusan perkuliahan.
            </p>
        </div>

        <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full border border-white/10"></div>
        <div class="absolute -right-5 -bottom-24 h-56 w-56 rounded-full bg-violet-400/15 blur-3xl"></div>
    </section>


    {{-- PROGRESS --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">

        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                    Alur Persiapan
                </p>
                <h2 class="mt-1 text-sm font-black text-slate-900 dark:text-white">
                    Status Kelengkapan Akun
                </h2>
            </div>

            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500">
                {{ ($hasProfile ? 1 : 0) + ($hasRiasec ? 1 : 0) }}/2 tahap selesai
            </span>
        </div>

        <div class="relative mt-7 grid grid-cols-3">

            {{-- Progress Line --}}
            <div class="absolute left-[16%] right-[16%] top-5 h-0.5 bg-slate-200 dark:bg-slate-800"></div>

            <div
                class="absolute left-[16%] top-5 h-0.5 bg-indigo-500 transition-all duration-500"
                style="width: {{ $hasProfile && $hasRiasec ? '68%' : ($hasProfile ? '34%' : '0%') }}"
            ></div>

            {{-- STEP 1 --}}
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-full text-xs font-black shadow-sm
                    {{ $hasProfile
                        ? 'bg-emerald-500 text-white'
                        : 'bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-500/20' }}">
                    @if($hasProfile)
                        <i class="fa-solid fa-check"></i>
                    @else
                        1
                    @endif
                </div>

                <p class="mt-2 text-[10px] font-black text-slate-800 dark:text-white sm:text-xs">
                    Data Diri
                </p>

                <span class="mt-0.5 text-[8px] text-slate-400 dark:text-slate-500">
                    {{ $hasProfile ? 'Selesai' : 'Wajib diisi' }}
                </span>
            </div>

            {{-- STEP 2 --}}
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-full text-xs font-black shadow-sm
                    {{ $hasRiasec
                        ? 'bg-emerald-500 text-white'
                        : ($hasProfile
                            ? 'bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-500/20'
                            : 'bg-slate-100 text-slate-400 dark:bg-slate-800') }}">
                    @if($hasRiasec)
                        <i class="fa-solid fa-check"></i>
                    @else
                        2
                    @endif
                </div>

                <p class="mt-2 text-[10px] font-black text-slate-800 dark:text-white sm:text-xs">
                    RIASEC
                </p>

                <span class="mt-0.5 text-[8px] text-slate-400 dark:text-slate-500">
                    {{ $hasRiasec ? 'Selesai' : 'Berikutnya' }}
                </span>
            </div>

            {{-- STEP 3 --}}
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-full text-xs font-black shadow-sm
                    {{ ($hasProfile && $hasRiasec)
                        ? 'bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-500/20'
                        : 'bg-slate-100 text-slate-400 dark:bg-slate-800' }}">
                    @if($hasProfile && $hasRiasec)
                        <i class="fa-solid fa-check"></i>
                    @else
                        3
                    @endif
                </div>

                <p class="mt-2 text-[10px] font-black text-slate-800 dark:text-white sm:text-xs">
                    Akses Penuh
                </p>

                <span class="mt-0.5 text-[8px] text-slate-400 dark:text-slate-500">
                    Dashboard
                </span>
            </div>

        </div>
    </section>


    {{-- ACTION CARDS --}}
    <section class="grid grid-cols-1 gap-4 md:grid-cols-2">

        {{-- DATA DIRI --}}
        <article class="group relative overflow-hidden rounded-3xl border bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md dark:bg-slate-900 sm:p-6
            {{ $hasProfile
                ? 'border-emerald-200 dark:border-emerald-500/20'
                : 'border-indigo-200 dark:border-indigo-500/30' }}">

            <div class="flex items-start justify-between gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl
                    {{ $hasProfile
                        ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                        : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400' }}">
                    <i class="fa-solid fa-id-card text-sm"></i>
                </div>

                @if($hasProfile)
                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[8px] font-black uppercase tracking-wider text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                        Lengkap
                    </span>
                @else
                    <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[8px] font-black uppercase tracking-wider text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                        Tahap 01
                    </span>
                @endif

            </div>

            <div class="mt-5">
                <h3 class="text-sm font-black text-slate-900 dark:text-white sm:text-base">
                    Data Diri Siswa
                </h3>

                <p class="mt-1.5 text-[10px] leading-relaxed text-slate-500 dark:text-slate-400 sm:text-xs">
                    Pastikan informasi identitas, kelas, asal sekolah, dan data akademik Anda telah sesuai.
                </p>
            </div>

            <a
                href="{{ route('student.profile.edit') }}"
                class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-[10px] font-black transition-all
                {{ $hasProfile
                    ? 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700'
                    : 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/20 hover:bg-indigo-700' }}"
            >
                {{ $hasProfile ? 'Perbarui Data Diri' : 'Lengkapi Data Diri' }}
                <i class="fa-solid fa-arrow-right text-[8px]"></i>
            </a>

        </article>


        {{-- RIASEC --}}
        <article class="group relative overflow-hidden rounded-3xl border bg-white p-5 shadow-sm transition-all duration-300
            {{ $hasRiasec
                ? 'border-emerald-200 dark:border-emerald-500/20 hover:shadow-md'
                : ($hasProfile
                    ? 'border-violet-200 dark:border-violet-500/30 hover:-translate-y-0.5 hover:shadow-md'
                    : 'border-slate-200 opacity-70 dark:border-slate-800') }}
            dark:bg-slate-900 sm:p-6">

            <div class="flex items-start justify-between gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl
                    {{ $hasRiasec
                        ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                        : 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400' }}">
                    <i class="fa-solid fa-compass text-sm"></i>
                </div>

                @if($hasRiasec)
                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[8px] font-black uppercase tracking-wider text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                        Selesai
                    </span>
                @elseif($hasProfile)
                    <span class="rounded-full bg-violet-50 px-2.5 py-1 text-[8px] font-black uppercase tracking-wider text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                        Tahap 02
                    </span>
                @else
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[8px] font-black uppercase tracking-wider text-slate-400 dark:bg-slate-800">
                        Terkunci
                    </span>
                @endif

            </div>

            <div class="mt-5">
                <h3 class="text-sm font-black text-slate-900 dark:text-white sm:text-base">
                    Asesmen Minat RIASEC
                </h3>

                <p class="mt-1.5 text-[10px] leading-relaxed text-slate-500 dark:text-slate-400 sm:text-xs">
                    Kenali kecenderungan minat dan karakter Anda melalui asesmen sebagai dasar analisis jurusan.
                </p>
            </div>

            @if($hasProfile)
                <a
                    href="{{ route('student.riasec.index') }}"
                    class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-[10px] font-black transition-all
                    {{ $hasRiasec
                        ? 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700'
                        : 'bg-violet-600 text-white shadow-sm shadow-violet-500/20 hover:bg-violet-700' }}"
                >
                    {{ $hasRiasec ? 'Lihat Hasil Asesmen' : 'Mulai Asesmen RIASEC' }}
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            @else
                <button
                    type="button"
                    disabled
                    class="mt-5 flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-[10px] font-black text-slate-400 dark:bg-slate-800 dark:text-slate-500"
                >
                    <i class="fa-solid fa-lock text-[8px]"></i>
                    Lengkapi Data Diri Terlebih Dahulu
                </button>
            @endif

        </article>

    </section>


    {{-- FOOTNOTE --}}
    @if($hasProfile && $hasRiasec)
        <div class="flex items-center justify-center gap-2 pt-1 text-center text-[9px] text-slate-400 dark:text-slate-500">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            Seluruh tahap persiapan telah selesai. Dashboard dan rekomendasi jurusan siap digunakan.
        </div>
    @endif

</div>
@endsection