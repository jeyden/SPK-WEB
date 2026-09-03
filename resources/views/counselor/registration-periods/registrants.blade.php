@extends('counselor.layouts.app')

@section('title', 'Pendaftar Periode ' . $period->academic_year)

@section('content')
<div class="max-w-6xl mx-auto space-y-6 pb-16">

    {{-- HEADER --}}
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="pointer-events-none absolute -right-16 -top-20 h-48 w-48 rounded-full bg-indigo-500/10 blur-3xl"></div>

        <div class="relative flex flex-col gap-4 px-5 py-5 sm:px-6 md:flex-row md:items-center md:justify-between">
            <div>
                <a href="{{ route('counselor.registration-periods.index') }}"
                   class="inline-flex items-center gap-2 text-[11px] font-semibold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition mb-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Periode Pendaftaran
                </a>

                <span class="text-[9px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                    Daftar Pendaftar
                </span>
                <h1 class="mt-1 text-lg font-black tracking-tight text-slate-900 dark:text-white sm:text-xl">
                    Tahun Akademik {{ $period->academic_year }}
                </h1>
                <p class="mt-1 max-w-2xl text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    {{ $period->start_date->format('d M Y') }} &mdash; {{ $period->end_date->format('d M Y') }}
                    <span class="mx-1.5">&middot;</span>
                    <span class="inline-flex items-center gap-1.5 font-bold {{ $period->statusBadgeClass() }} px-2 py-0.5 rounded-full text-[10px]">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        {{ $period->statusLabel() }}
                    </span>
                </p>
            </div>

            <div class="rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 px-4 py-3 text-center shrink-0">
                <p class="text-[9px] font-bold uppercase tracking-wider text-indigo-500 dark:text-indigo-400">Total Pendaftar</p>
                <p class="text-2xl font-black text-indigo-700 dark:text-indigo-300 mt-0.5">{{ $registrants->total() }}</p>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-base shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="px-4 py-3 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 text-xs flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-base shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('counselor.registration-periods.registrants', $period) }}"
          class="flex items-center gap-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-3.5 rounded-2xl shadow-sm">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NISN siswa..."
                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-all">
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-xl border border-indigo-200/70 dark:border-indigo-500/20 transition-all shrink-0">
            Cari
        </button>
        @if ($search)
            <a href="{{ route('counselor.registration-periods.registrants', $period) }}"
               class="px-3 py-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-xs font-semibold shrink-0">
                Reset
            </a>
        @endif
    </form>

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="hidden overflow-x-auto md:block">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 dark:border-slate-800 dark:bg-slate-800/40">
                        <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">NISN / Nama Siswa</th>
                        <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Jurusan / Kelas</th>
                        <th class="px-5 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Asesmen RIASEC</th>
                        <th class="px-5 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($registrants as $student)
                        @php
                            $studentName = optional($student->user)->name ?? 'Tanpa Nama';
                            $majorLabel = $student->high_school_major ?? null;
                            $classLabel = $student->class_name ?? $student->kelas ?? null;
                            $classInfo = collect([$majorLabel, $classLabel])->filter()->implode(' / ');
                            $hasRiasec = (bool) $student->riasecScore;
                        @endphp
                        <tr class="group transition-colors hover:bg-slate-50/70 dark:hover:bg-white/[0.025]">
                            <td class="px-5 py-4 align-middle">
                                <div class="text-xs font-bold text-slate-800 dark:text-slate-100">{{ $studentName }}</div>
                                <div class="mt-0.5 text-[10px] text-slate-400 font-mono">{{ $student->nisn ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-4 align-middle">
                                <span class="text-xs text-slate-600 dark:text-slate-300">{{ $classInfo ?: '-' }}</span>
                            </td>
                            <td class="px-5 py-4 align-middle text-center">
                                @if ($hasRiasec)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200/70 dark:border-emerald-500/20 px-2.5 py-1 text-[10px] font-bold text-emerald-700 dark:text-emerald-300">
                                        <i class="fa-solid fa-circle-check text-[9px]"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 dark:bg-rose-500/10 border border-rose-200/70 dark:border-rose-500/20 px-2.5 py-1 text-[10px] font-bold text-rose-700 dark:text-rose-300">
                                        <i class="fa-solid fa-circle-xmark text-[9px]"></i> Belum
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 align-middle">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Pada bagian tabel desktop dan mobile, ubah link detail menjadi: -->
                                    <a href="{{ route('counselor.registration-periods.registrants.show', [$period, $student]) }}"
                                    title="Lihat detail siswa"
                                    class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-slate-100 px-3 text-[10px] font-bold text-slate-600 transition-colors hover:bg-indigo-50 hover:text-indigo-600 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400">
                                        <i class="fa-solid fa-eye text-[10px]"></i>
                                        Detail
                                    </a>

                                    <form action="{{ route('counselor.registration-periods.registrants.destroy', [$period, $student]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus pendaftar ini dari periode? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Hapus pendaftar"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600 dark:bg-white/5 dark:text-slate-500 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-white/5 dark:text-slate-500">
                                        <i class="fa-solid fa-user-slash text-lg"></i>
                                    </div>
                                    <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                        Belum Ada Siswa Terdaftar
                                    </h3>
                                    <p class="mt-1 text-[11px] leading-relaxed text-slate-400 dark:text-slate-500">
                                        Belum ada siswa yang terdaftar pada tahun akademik {{ $period->academic_year }}.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card --}}
        <div class="divide-y divide-slate-100 dark:divide-slate-800 md:hidden">
            @forelse ($registrants as $student)
                @php
                    $studentName = optional($student->user)->name ?? 'Tanpa Nama';
                    $majorLabel = $student->high_school_major ?? null;
                    $classLabel = $student->class_name ?? $student->kelas ?? null;
                    $classInfo = collect([$majorLabel, $classLabel])->filter()->implode(' / ');
                    $hasRiasec = (bool) $student->riasecScore;
                @endphp
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-100">{{ $studentName }}</p>
                            <p class="mt-0.5 text-[10px] text-slate-400 font-mono">{{ $student->nisn ?? '-' }}</p>
                            @if ($classInfo)
                                <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">{{ $classInfo }}</p>
                            @endif
                        </div>
                        @if ($hasRiasec)
                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200/70 dark:border-emerald-500/20 px-2.5 py-1 text-[9px] font-bold text-emerald-700 dark:text-emerald-300">
                                Selesai
                            </span>
                        @else
                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-rose-50 dark:bg-rose-500/10 border border-rose-200/70 dark:border-rose-500/20 px-2.5 py-1 text-[9px] font-bold text-rose-700 dark:text-rose-300">
                                Belum
                            </span>
                        @endif
                    </div>

                    <div class="mt-3 flex items-center gap-2">
                        <a href="{{ route('counselor.assessments.assess', ['student' => $student->id, 'academic_year' => $period->academic_year]) }}"
                           class="flex h-9 flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 text-[10px] font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-white/5 dark:text-slate-300">
                            <i class="fa-solid fa-eye"></i>
                            Detail
                        </a>
                        <form action="{{ route('counselor.registration-periods.registrants.destroy', [$period, $student]) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus pendaftar ini dari periode?');"
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="flex h-9 w-full items-center justify-center gap-1.5 rounded-lg bg-slate-100 text-[10px] font-bold text-slate-500 hover:bg-rose-50 hover:text-rose-600 dark:bg-white/5 dark:text-slate-400">
                                <i class="fa-solid fa-trash-can"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-5 py-14 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-white/5 dark:text-slate-500">
                        <i class="fa-solid fa-user-slash text-lg"></i>
                    </div>
                    <h3 class="mt-4 text-xs font-bold text-slate-700 dark:text-slate-200">
                        Belum Ada Siswa Terdaftar
                    </h3>
                </div>
            @endforelse
        </div>

        @if ($registrants->hasPages())
            <div class="border-t border-slate-100 px-4 py-3 dark:border-slate-800 sm:px-5">
                {{ $registrants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection