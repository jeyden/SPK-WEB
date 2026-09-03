@extends('student.layouts.app')

@section('title', 'Rekomendasi Program Studi')

@section('content')
<div class="mx-auto max-w-6xl space-y-5 px-3 pb-16 sm:px-5 lg:px-6">

    @php
        $totalRecommendations = count($recommendations ?? []);
    @endphp

    {{-- HEADER --}}
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="pointer-events-none absolute -right-16 -top-20 h-48 w-48 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-40 w-40 rounded-full bg-violet-500/10 blur-3xl"></div>

        <div class="relative px-5 py-5 sm:px-6 md:px-7 md:py-6">

            <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                {{-- TITLE --}}
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                            <i class="fa-solid fa-compass text-xs"></i>
                        </span>

                        <span class="text-[9px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                            Hasil    Rekomendasi
                        </span>
                    </div>

                    <h1 class="mt-2 text-xl font-black tracking-tight text-slate-900 dark:text-white sm:text-2xl">
                        Rekomendasi Program Studi
                    </h1>

                    <p class="mt-1 max-w-2xl text-xs leading-relaxed text-slate-500 dark:text-slate-400 sm:text-sm">
                        Pilihan program studi berdasarkan tingkat kesesuaian profil minat RIASEC menggunakan metode
                        <span class="font-semibold text-slate-700 dark:text-slate-200">
                            Simple Additive Weighting (SAW)
                        </span>.
                    </p>
                </div>

                {{-- TOTAL BADGE --}}
                <div class="shrink-0 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/50">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            Total Alternatif
                        </p>
                        <div class="mt-0.5 flex items-baseline gap-1.5">
                            <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white">
                                {{ $totalRecommendations }}
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                Program Studi
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- INFO STRIP --}}
            <div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-4 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between text-xs text-slate-500 dark:text-slate-400">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-indigo-500 shrink-0"></i>
                    <p>Pilih program studi untuk melihat rincian skor, analisis RIASEC, serta daftar perguruan tinggi.</p>
                </div>

                <span class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-md bg-slate-100 px-2.5 py-1 text-[10px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    SAW & RIASEC
                </span>
            </div>
        </div>
    </section>


    {{-- DAFTAR REKOMENDASI --}}
    <section>

        @if($totalRecommendations > 0)

            <div class="mb-4 flex items-center justify-between gap-3 px-1">
                <div>
                    <h2 class="text-sm font-black tracking-tight text-slate-900 dark:text-white sm:text-base">
                        Daftar Rekomendasi
                    </h2>

                    <p class="mt-0.5 text-[10px] text-slate-500 dark:text-slate-400 sm:text-xs">
                        Urutan berdasarkan tingkat kesesuaian profil RIASEC.
                    </p>
                </div>

                <span class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[9px] font-bold text-slate-500 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400">
                    {{ $totalRecommendations }} hasil
                </span>
            </div>

            {{-- GRID --}}
            <div
                id="recommendation-grid"
                class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 xl:grid-cols-3"
            >

                @foreach($recommendations as $index => $rec)

                    @php
                        $rank = (int) ($rec->rank ?? ($index + 1));
                        $scorePercent = (int) ($rec->scorePercent ?? 0);
                        $major = $rec->major;
                        $degree = optional($major)->degree;
                        $majorName = optional($major)->name ?? 'Program Studi Tidak Ditemukan';

                        $isTopThree = $rank <= 3;

                        if ($rank === 1) {
                            $rankStyle = 'bg-indigo-600 text-white';
                        } elseif ($rank === 2) {
                            $rankStyle = 'bg-violet-600 text-white';
                        } elseif ($rank === 3) {
                            $rankStyle = 'bg-blue-600 text-white';
                        } else {
                            $rankStyle = 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300';
                        }
                    @endphp

                    <a
                        href="{{ route('student.recommendations.detail', $rec->id) }}"
                        data-recommendation-item
                        class="{{ $index >= 10 ? 'hidden' : '' }} group relative flex min-h-[265px] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-indigo-700 sm:p-5"
                    >

                        {{-- TOP DECORATION --}}
                        <div class="pointer-events-none absolute -right-10 -top-10 h-24 w-24 rounded-full bg-indigo-500/5 blur-2xl transition group-hover:bg-indigo-500/10"></div>

                        <div class="relative flex flex-1 flex-col">

                            {{-- RANK + SCORE --}}
                            <div class="flex items-start justify-between gap-3">

                                <div class="flex items-center gap-2">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $rankStyle }} text-[10px] font-black shadow-sm">
                                        #{{ $rank }}
                                    </span>

                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-[0.12em] text-slate-400 dark:text-slate-500">
                                            Peringkat
                                        </p>

                                        @if($isTopThree)
                                            <p class="mt-0.5 text-[9px] font-bold text-indigo-600 dark:text-indigo-400">
                                                Top {{ $rank }} Rekomendasi
                                            </p>
                                        @else
                                            <p class="mt-0.5 text-[9px] font-medium text-slate-400 dark:text-slate-500">
                                                Rekomendasi
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-lg font-black tracking-tight text-slate-900 dark:text-white">
                                        {{ $scorePercent }}%
                                    </div>

                                    <div class="text-[9px] font-semibold text-slate-400 dark:text-slate-500">
                                        Kecocokan
                                    </div>
                                </div>

                            </div>


                            {{-- MAJOR --}}
                            <div class="mt-5">

                                <h3 class="line-clamp-2 text-sm font-black leading-snug tracking-tight text-slate-900 transition-colors group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400 sm:text-[15px]">
                                    {{ $degree ? $degree . ' ' : '' }}{{ $majorName }}
                                </h3>

                                <div class="mt-3 flex flex-wrap gap-1.5">

                                    <span class="inline-flex max-w-full items-center gap-1.5 rounded-lg bg-indigo-50 px-2 py-1.5 text-[9px] font-bold text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                        <i class="fa-solid fa-shapes text-[8px]"></i>
                                        <span class="truncate">
                                            {{ $rec->rumpun ?? '-' }}
                                        </span>
                                    </span>

                                    @if(($rec->subIlmu ?? '-') !== '-')
                                        <span class="inline-flex max-w-full items-center rounded-lg bg-slate-100 px-2 py-1.5 text-[9px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                            <span class="truncate">
                                                {{ $rec->subIlmu }}
                                            </span>
                                        </span>
                                    @endif

                                </div>

                            </div>


                            {{-- SCORE --}}
                            <div class="mt-auto pt-5">

                                <div class="mb-2 flex items-center justify-between">
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Tingkat Kesesuaian
                                    </span>

                                    <span class="text-[9px] font-black text-indigo-600 dark:text-indigo-400">
                                        {{ $scorePercent }}%
                                    </span>
                                </div>

                                <div class="h-1.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500 group-hover:from-indigo-600 group-hover:to-violet-600"
                                        style="width: {{ min(100, max(0, $scorePercent)) }}%"
                                    ></div>
                                </div>

                            </div>


                            {{-- FOOTER --}}
                            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 dark:border-slate-800">

                                <span class="text-[10px] font-bold text-slate-500 transition-colors group-hover:text-indigo-600 dark:text-slate-400 dark:group-hover:text-indigo-400">
                                    Lihat analisis
                                </span>

                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition-all group-hover:bg-indigo-600 group-hover:text-white dark:bg-slate-800 dark:text-slate-400 dark:group-hover:bg-indigo-600">
                                    <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-0.5"></i>
                                </span>

                            </div>

                        </div>

                    </a>

                @endforeach

            </div>


            {{-- LAZY LOAD INDICATOR --}}
            @if($totalRecommendations > 10)

                <div
                    id="recommendation-loader"
                    class="mt-5 flex items-center justify-center py-4"
                >
                    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[10px] font-bold text-slate-500 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400">
                        <span class="h-3.5 w-3.5 animate-spin rounded-full border-2 border-slate-200 border-t-indigo-600 dark:border-slate-700 dark:border-t-indigo-400"></span>
                        Memuat rekomendasi berikutnya...
                    </div>
                </div>

                <div
                    id="recommendation-sentinel"
                    class="h-1 w-full"
                    aria-hidden="true"
                ></div>

            @endif


        @else

            {{-- EMPTY STATE --}}
            <div class="overflow-hidden rounded-3xl border border-dashed border-slate-300 bg-white px-5 py-16 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400">
                    <i class="fa-solid fa-compass text-lg"></i>
                </div>

                <h3 class="mt-4 text-base font-black tracking-tight text-slate-900 dark:text-white">
                    Belum Ada Hasil Rekomendasi
                </h3>

                <p class="mx-auto mt-1.5 max-w-md text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    Hasil rekomendasi program studi belum tersedia. Silakan menyelesaikan asesmen RIASEC terlebih dahulu agar sistem dapat menghasilkan rekomendasi.
                </p>

            </div>

        @endif

    </section>


    {{-- METODOLOGI --}}
    @if($totalRecommendations > 0)

        <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

            <div class="pointer-events-none absolute -right-16 -bottom-20 h-40 w-40 rounded-full bg-indigo-500/5 blur-3xl"></div>

            <div class="relative flex items-start gap-3.5 p-4.5 sm:p-5">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400">
                    <i class="fa-solid fa-graduation-cap text-xs"></i>
                </div>

                <div class="min-w-0">

                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-xs font-black text-slate-900 dark:text-white sm:text-sm">
                            Tentang Hasil Rekomendasi
                        </h3>

                        <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[8px] font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                            Informasi
                        </span>
                    </div>

                    <p class="mt-1.5 text-[10px] leading-relaxed text-slate-500 dark:text-slate-400 sm:text-xs">
                        Skor kecocokan menunjukkan tingkat kesesuaian antara profil RIASEC siswa dan karakteristik
                        program studi. Hasil ini digunakan sebagai bahan eksplorasi dan pertimbangan dalam menentukan
                        pilihan pendidikan lanjutan.
                    </p>

                </div>

            </div>

        </section>

    @endif

</div>


{{-- LAZY LOAD 10 ITEM --}}
@if($totalRecommendations > 10)

<script>
document.addEventListener('DOMContentLoaded', function () {

    const items = Array.from(
        document.querySelectorAll('[data-recommendation-item]')
    );

    const loader = document.getElementById('recommendation-loader');
    const sentinel = document.getElementById('recommendation-sentinel');

    const batchSize = 10;
    let visibleCount = 10;
    let loading = false;

    if (!sentinel || items.length <= batchSize) {
        if (loader) {
            loader.remove();
        }
        return;
    }

    function loadNextBatch() {

        if (loading || visibleCount >= items.length) {
            return;
        }

        loading = true;

        if (loader) {
            loader.classList.remove('hidden');
        }

        setTimeout(function () {

            const nextItems = items.slice(
                visibleCount,
                visibleCount + batchSize
            );

            nextItems.forEach(function (item) {
                item.classList.remove('hidden');
            });

            visibleCount += nextItems.length;
            loading = false;

            if (visibleCount >= items.length) {
                if (loader) {
                    loader.innerHTML = `
                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[10px] font-bold text-slate-400 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-500">
                            Semua rekomendasi telah ditampilkan
                        </div>
                    `;
                }

                if (observer) {
                    observer.disconnect();
                }
            }

        }, 250);
    }

    const observer = new IntersectionObserver(
        function (entries) {
            if (entries[0].isIntersecting) {
                loadNextBatch();
            }
        },
        {
            root: null,
            rootMargin: '500px 0px',
            threshold: 0
        }
    );

    observer.observe(sentinel);

});
</script>
@endif

@endsection