@extends('counselor.layouts.app')

@section('title', 'Periode Pendaftaran')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 pb-16">

    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="pointer-events-none absolute -right-16 -top-20 h-48 w-48 rounded-full bg-indigo-500/10 blur-3xl"></div>

        <div class="relative flex flex-col gap-4 px-5 py-5 sm:px-6 md:flex-row md:items-center md:justify-between">
            <div>
                <span class="text-[9px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                    Manajemen Pendaftaran
                </span>
                <h1 class="mt-2 text-lg font-black tracking-tight text-slate-900 dark:text-white sm:text-xl">
                    Periode Pendaftaran
                </h1>
                <p class="mt-1 max-w-2xl text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    Kelola periode pendaftaran rekomendasi jurusan berdasarkan tahun akademik.
                </p>
            </div>

            <a href="{{ route('counselor.registration-periods.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-sm shadow-indigo-600/20 transition-colors w-full sm:w-auto">
                <i class="fa-solid fa-plus text-[11px]"></i>
                Buat Periode
            </a>
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

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        {{-- Table Header --}}
        <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-800 dark:text-white">
                    Daftar Periode
                </h2>
                <p class="mt-0.5 text-[11px] text-slate-400 dark:text-slate-500">
                    Periode pendaftaran rekomendasi jurusan yang tersedia
                </p>
            </div>

            <div class="inline-flex w-fit items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-[10px] font-semibold text-slate-500 dark:bg-white/5 dark:text-slate-400">
                <i class="fa-regular fa-calendar text-indigo-500"></i>
                Tahun Akademik
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 dark:border-slate-800 dark:bg-slate-800/40">
                        <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Tahun Akademik
                        </th>
                        <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Periode
                        </th>
                        <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Keterangan
                        </th>
                        <th class="px-5 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Pendaftar
                        </th>
                        <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Status
                        </th>
                        <th class="px-5 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($periods as $period)
                        <tr class="group transition-colors hover:bg-slate-50/70 dark:hover:bg-white/[0.025]">

                            {{-- Tahun Akademik --}}
                            <td class="px-5 py-4 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                        <i class="fa-regular fa-calendar text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 dark:text-slate-100">
                                            {{ $period->academic_year }}
                                        </div>
                                        <div class="mt-0.5 text-[10px] text-slate-400">
                                            Tahun akademik
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Periode --}}
                            <td class="px-5 py-4 align-middle">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-xs font-medium text-slate-700 dark:text-slate-200">
                                        <i class="fa-regular fa-clock w-3 text-slate-400"></i>
                                        {{ $period->start_date->format('d M Y') }}
                                    </div>
                                    <div class="pl-5 text-[10px] font-medium text-slate-400">
                                        sampai {{ $period->end_date->format('d M Y') }}
                                    </div>
                                </div>
                            </td>

                            {{-- Keterangan --}}
                            <td class="max-w-xs px-5 py-4 align-middle">
                                <p class="truncate text-xs text-slate-500 dark:text-slate-400" title="{{ $period->description ?? '-' }}">
                                    {{ $period->description ?? '-' }}
                                </p>
                            </td>

                            {{-- Pendaftar --}}
                            <td class="px-5 py-4 align-middle text-center">
                                <a href="{{ route('counselor.registration-periods.registrants', $period) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-bold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 transition-colors">
                                    <i class="fa-solid fa-users text-[10px]"></i>
                                    {{ $period->registrants_count ?? 0 }} siswa
                                </a>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 align-middle">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[10px] font-bold {{ $period->statusBadgeClass() }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $period->statusLabel() }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 align-middle">
                                <div class="flex items-center justify-end gap-1.5">

                                    @if (!$period->isClosed())
                                        @if (!$period->isOpen())
                                            <form action="{{ route('counselor.registration-periods.open', $period) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        title="Buka periode"
                                                        class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-emerald-50 px-3 text-[10px] font-bold text-emerald-600 transition-colors hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20">
                                                    <i class="fa-solid fa-lock-open text-[10px]"></i>
                                                    Buka
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('counselor.registration-periods.close', $period) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        title="Tutup periode"
                                                        class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-rose-50 px-3 text-[10px] font-bold text-rose-600 transition-colors hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20">
                                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                                    Tutup
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    <a href="{{ route('counselor.registration-periods.edit', $period) }}"
                                       title="Edit periode"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition-colors hover:bg-indigo-50 hover:text-indigo-600 dark:bg-white/5 dark:text-slate-400 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400">
                                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                    </a>

                                    <form action="{{ route('counselor.registration-periods.destroy', $period) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus periode ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Hapus periode"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600 dark:bg-white/5 dark:text-slate-500 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-white/5 dark:text-slate-500">
                                        <i class="fa-regular fa-calendar-xmark text-lg"></i>
                                    </div>
                                    <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                        Belum Ada Periode Pendaftaran
                                    </h3>
                                    <p class="mt-1 text-[11px] leading-relaxed text-slate-400 dark:text-slate-500">
                                        Buat periode pendaftaran untuk mulai mengatur proses rekomendasi jurusan.
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
            @forelse ($periods as $period)
                <div class="p-4 transition-colors hover:bg-slate-50/70 dark:hover:bg-white/[0.025]">

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                <i class="fa-regular fa-calendar"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-100">
                                    {{ $period->academic_year }}
                                </p>
                                <p class="mt-0.5 text-[10px] text-slate-400">
                                    Tahun akademik
                                </p>
                            </div>
                        </div>

                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1.5 text-[9px] font-bold {{ $period->statusBadgeClass() }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            {{ $period->statusLabel() }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 dark:bg-white/[0.03]">
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">
                                Mulai
                            </p>
                            <p class="mt-1 text-[11px] font-semibold text-slate-700 dark:text-slate-200">
                                {{ $period->start_date->format('d M Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">
                                Berakhir
                            </p>
                            <p class="mt-1 text-[11px] font-semibold text-slate-700 dark:text-slate-200">
                                {{ $period->end_date->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">
                            Keterangan
                        </p>
                        <p class="mt-1 text-[11px] leading-relaxed text-slate-500 dark:text-slate-400">
                            {{ $period->description ?? '-' }}
                        </p>
                    </div>

                    <a href="{{ route('counselor.registration-periods.registrants', $period) }}"
                       class="mt-3 flex items-center justify-between rounded-xl bg-slate-50 dark:bg-white/[0.03] p-3 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                        <span class="flex items-center gap-2 text-[11px] font-bold text-slate-700 dark:text-slate-300">
                            <i class="fa-solid fa-users text-indigo-500"></i>
                            Lihat Pendaftar
                        </span>
                        <span class="text-[11px] font-black text-slate-900 dark:text-white">
                            {{ $period->registrants_count ?? 0 }} siswa
                        </span>
                    </a>

                    <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3 dark:border-slate-800">

                        @if (!$period->isClosed())
                            @if (!$period->isOpen())
                                <form action="{{ route('counselor.registration-periods.open', $period) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="flex h-9 w-full items-center justify-center gap-1.5 rounded-lg bg-emerald-50 text-[10px] font-bold text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20">
                                        <i class="fa-solid fa-lock-open"></i>
                                        Buka
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('counselor.registration-periods.close', $period) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="flex h-9 w-full items-center justify-center gap-1.5 rounded-lg bg-rose-50 text-[10px] font-bold text-rose-600 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20">
                                        <i class="fa-solid fa-lock"></i>
                                        Tutup
                                    </button>
                                </form>
                            @endif
                        @endif

                        <a href="{{ route('counselor.registration-periods.edit', $period) }}"
                           class="flex h-9 flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 text-[10px] font-bold text-slate-600 hover:bg-slate-200 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Edit
                        </a>

                        <form action="{{ route('counselor.registration-periods.destroy', $period) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus periode ini?');"
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="flex h-9 w-full items-center justify-center gap-1.5 rounded-lg bg-slate-100 text-[10px] font-bold text-slate-500 hover:bg-rose-50 hover:text-rose-600 dark:bg-white/5 dark:text-slate-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                <i class="fa-solid fa-trash-can"></i>
                                Hapus
                            </button>
                        </form>

                    </div>
                </div>
            @empty
                <div class="px-5 py-14 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-white/5 dark:text-slate-500">
                        <i class="fa-regular fa-calendar-xmark text-lg"></i>
                    </div>
                    <h3 class="mt-4 text-xs font-bold text-slate-700 dark:text-slate-200">
                        Belum Ada Periode Pendaftaran
                    </h3>
                    <p class="mx-auto mt-1 max-w-xs text-[11px] leading-relaxed text-slate-400 dark:text-slate-500">
                        Buat periode pendaftaran untuk mulai mengatur proses rekomendasi jurusan.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($periods->hasPages())
            <div class="border-t border-slate-100 px-4 py-3 dark:border-slate-800 sm:px-5">
                {{ $periods->links() }}
            </div>
        @endif

    </div>

</div>
@endsection