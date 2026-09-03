@extends('student.layouts.app')

@section('title', 'Hasil Asesmen RIASEC')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-16 px-0 sm:px-0">

    @php
        $rScore = $score->r_score ?? 0;
        $iScore = $score->i_score ?? 0;
        $aScore = $score->a_score ?? 0;
        $sScore = $score->s_score ?? 0;
        $eScore = $score->e_score ?? 0;
        $cScore = $score->c_score ?? 0;

        $tsk = $score->tsk ?? ($rScore + $iScore + $aScore + $sScore + $eScore + $cScore);
        $domCode = strtoupper($score->dominant_code ?? '');

        $riasecExplanations = [
            'R' => [
                'name' => 'Realistic',
                'desc' => 'Menyukai aktivitas praktis, teknis, mekanis, dan berinteraksi langsung dengan objek, alat, atau lingkungan nyata.'
            ],
            'I' => [
                'name' => 'Investigative',
                'desc' => 'Berorientasi pada analisis, pemecahan masalah, penelitian, sains, dan eksplorasi pengetahuan.'
            ],
            'A' => [
                'name' => 'Artistic',
                'desc' => 'Kreatif, ekspresif, dan menyukai kebebasan dalam seni, desain, gagasan, serta cara berekspresi.'
            ],
            'S' => [
                'name' => 'Social',
                'desc' => 'Menyukai interaksi dengan orang lain, membantu, membimbing, mengajar, dan membangun hubungan sosial.'
            ],
            'E' => [
                'name' => 'Enterprising',
                'desc' => 'Memiliki kecenderungan memimpin, memengaruhi, bernegosiasi, berwirausaha, dan mencapai target.'
            ],
            'C' => [
                'name' => 'Conventional',
                'desc' => 'Terstruktur, teliti, sistematis, dan menyukai data, administrasi, angka, serta prosedur yang jelas.'
            ],
        ];

        $results = [
            ['code' => 'R', 'name' => 'Realistic', 'score' => $rScore, 'short' => 'Praktis & teknis'],
            ['code' => 'I', 'name' => 'Investigative', 'score' => $iScore, 'short' => 'Analitis & riset'],
            ['code' => 'A', 'name' => 'Artistic', 'score' => $aScore, 'short' => 'Kreatif & ekspresif'],
            ['code' => 'S', 'name' => 'Social', 'score' => $sScore, 'short' => 'Sosial & membantu'],
            ['code' => 'E', 'name' => 'Enterprising', 'score' => $eScore, 'short' => 'Memimpin & bisnis'],
            ['code' => 'C', 'name' => 'Conventional', 'score' => $cScore, 'short' => 'Terstruktur & teliti'],
        ];

        $maxDimensionScore = 20;
        $maxTsk = 120;
        $tskPercentage = min(100, round(($tsk / $maxTsk) * 100));

        if ($tsk >= 97) {
            $tskLevel = 'Sangat Tinggi';
            $tskDescription = 'Profil minat menunjukkan kekuatan yang sangat tinggi secara keseluruhan. Hasil ini dapat menjadi dasar kuat untuk mengeksplorasi berbagai bidang studi yang relevan.';
        } elseif ($tsk >= 73) {
            $tskLevel = 'Tinggi';
            $tskDescription = 'Profil minat menunjukkan kekuatan yang tinggi secara keseluruhan dan dapat menjadi salah satu pertimbangan penting dalam mengeksplorasi pilihan program studi.';
        } elseif ($tsk >= 49) {
            $tskLevel = 'Sedang';
            $tskDescription = 'Profil minat berada pada tingkat sedang. Hasil asesmen dapat digunakan sebagai bahan eksplorasi sebelum menentukan pilihan program studi.';
        } else {
            $tskLevel = 'Rendah';
            $tskDescription = 'Profil minat masih berada pada tingkat rendah secara keseluruhan. Siswa disarankan memperluas eksplorasi terhadap berbagai aktivitas dan bidang pembelajaran.';
        }
    @endphp

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <section class="relative overflow-hidden rounded-3xl border border-blue-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="absolute -right-24 -top-28 h-64 w-64 rounded-full bg-blue-100/60 blur-3xl dark:bg-blue-950/30"></div>
        <div class="absolute -bottom-32 -left-20 h-56 w-56 rounded-full bg-sky-100/50 blur-3xl dark:bg-sky-950/20"></div>

        <div class="relative p-6 sm:p-8 lg:p-10">
            <div class="max-w-2xl">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Asesmen Selesai
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-blue-950 dark:text-blue-50 sm:text-3xl lg:text-4xl">
                    Hasil Asesmen RIASEC
                </h1>

                <p class="mt-3 max-w-xl text-xs leading-6 text-slate-600 dark:text-slate-300 sm:text-sm">
                    Ringkasan profil minat berdasarkan enam dimensi RIASEC sebagai bahan refleksi dan eksplorasi pilihan program studi.
                </p>
            </div>
        </div>
    </section>

    {{-- =========================================================
        RINGKASAN TSK & TOTAL SKOR
    ========================================================== --}}
    <section class="rounded-3xl border border-blue-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:items-center">
                
                {{-- Kiri: Deskripsi & Informasi --}}
                <div class="lg:col-span-7">
                    <div class="mb-2 flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">
                            <i class="fa-solid fa-chart-simple text-xs"></i>
                        </span>
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">
                                Ringkasan Profil
                            </p>
                            <h2 class="text-base font-bold text-blue-950 dark:text-blue-50">
                                Kekuatan Profil Minat Siswa
                            </h2>
                        </div>
                    </div>

                    <p class="mt-3 text-xs leading-6 text-slate-600 dark:text-slate-300 sm:text-sm">
                        {{ $tskDescription }}
                    </p>
                </div>

                {{-- Kanan: Total Skor, Tingkat, & Progress Bar --}}
                <div class="rounded-2xl border border-blue-100 bg-blue-50/40 p-5 dark:border-slate-800 dark:bg-slate-800/40 lg:col-span-5">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Skor Keseluruhan</p>
                                <span class="rounded-md bg-blue-100 px-1.5 py-0.5 text-[9px] font-extrabold text-blue-700 dark:bg-blue-950/60 dark:text-blue-300">
                                    Tingkat: {{ $tskLevel }}
                                </span>
                            </div>
                            <div class="mt-0.5">
                                <span class="text-2xl font-extrabold text-blue-950 dark:text-white">{{ $tsk }}</span>
                                <span class="text-xs font-semibold text-slate-400">/ {{ $maxTsk }}</span>
                            </div>
                        </div>
                        <span class="text-sm font-black text-blue-700 dark:text-blue-300 bg-blue-100/70 dark:bg-blue-950/60 px-3 py-1 rounded-xl">
                            {{ $tskPercentage }}%
                        </span>
                    </div>

                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                        <div
                            class="h-full rounded-full bg-blue-600 transition-all duration-500 dark:bg-blue-500"
                            style="width: {{ $tskPercentage }}%"
                        ></div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================
        DIMENSI RIASEC
    ========================================================== --}}
    <section class="rounded-3xl border border-blue-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800 sm:px-8">
            <div class="flex flex-col gap-1">
                <span class="text-[9px] font-bold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                    Analisis Dimensi
                </span>

                <h2 class="text-lg font-bold text-blue-950 dark:text-blue-50">
                    Distribusi Skor RIASEC
                </h2>

                <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">
                    Setiap dimensi memiliki skor maksimum 20 berdasarkan instrumen asesmen.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 sm:p-8 lg:grid-cols-3">

            @foreach($results as $res)

                @php
                    $percentage = min(100, round(($res['score'] / $maxDimensionScore) * 100));
                    $isDominant = !empty($domCode) && str_contains($domCode, $res['code']);
                @endphp

                <div class="group rounded-2xl border p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-md
                    {{ $isDominant
                        ? 'border-blue-200 bg-blue-50/60 dark:border-blue-800/60 dark:bg-blue-950/20'
                        : 'border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/60' }}">

                    <div class="flex items-start justify-between gap-4">

                        <div class="flex min-w-0 items-center gap-3">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-sm font-extrabold text-white shadow-sm dark:bg-blue-700">
                                {{ $res['code'] }}
                            </div>

                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-bold text-blue-950 dark:text-blue-100">
                                    {{ $res['name'] }}
                                </h3>

                                <p class="mt-0.5 text-[10px] text-slate-500 dark:text-slate-400">
                                    {{ $res['short'] }}
                                </p>
                            </div>
                        </div>

                        <div class="shrink-0 text-right">
                            <span class="text-xl font-extrabold text-blue-950 dark:text-blue-100">
                                {{ $res['score'] }}
                            </span>
                            <span class="text-[10px] font-semibold text-slate-400">
                                /20
                            </span>
                        </div>

                    </div>

                    <div class="mt-5">
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-[9px] font-semibold uppercase tracking-wider text-slate-400">
                                Tingkat kecenderungan
                            </span>

                            <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400">
                                {{ $percentage }}%
                            </span>
                        </div>

                        <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                            <div
                                class="h-full rounded-full bg-blue-600 transition-all duration-500 dark:bg-blue-500"
                                style="width: {{ $percentage }}%"
                            ></div>
                        </div>
                    </div>

                    @if($isDominant)
                        <div class="mt-4 inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-white px-2.5 py-1.5 text-[9px] font-bold text-blue-700 dark:border-blue-800 dark:bg-blue-950/30 dark:text-blue-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                            Dimensi Dominan
                        </div>
                    @endif

                </div>

            @endforeach

        </div>
    </section>

    {{-- =========================================================
        INTERPRETASI PROFIL
    ========================================================== --}}
    @if(!empty($domCode))

        <section class="rounded-3xl border border-blue-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

            <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800 sm:px-8">
                <span class="text-[9px] font-bold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                    Interpretasi Profil
                </span>

                <div class="mt-1 flex flex-wrap items-center gap-3">
                    <h2 class="text-lg font-bold text-blue-950 dark:text-blue-50">
                        Karakteristik RIASEC Anda
                    </h2>

                    <span class="rounded-lg bg-blue-100 px-2.5 py-1 text-[10px] font-extrabold tracking-wider text-blue-700 dark:bg-blue-950/60 dark:text-blue-300">
                        {{ $domCode }}
                    </span>
                </div>

                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">
                    Dimensi berikut membentuk kombinasi profil minat utama berdasarkan hasil asesmen.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 p-6 sm:p-8 md:grid-cols-2 lg:grid-cols-3">

                @foreach(str_split($domCode) as $index => $char)

                    @if(isset($riasecExplanations[$char]))

                        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/70 p-5 dark:border-slate-800 dark:bg-slate-800/30">

                            <div class="absolute right-0 top-0 h-20 w-20 rounded-bl-full bg-blue-100/50 dark:bg-blue-950/20"></div>

                            <div class="relative">

                                <div class="flex items-center gap-3">

                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-xs font-extrabold text-blue-700 dark:bg-blue-950/70 dark:text-blue-300">
                                        {{ $char }}
                                    </span>

                                    <div>
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                                            Dimensi {{ $index + 1 }}
                                        </span>

                                        <h3 class="text-sm font-bold text-blue-950 dark:text-blue-100">
                                            {{ $riasecExplanations[$char]['name'] }}
                                        </h3>
                                    </div>

                                </div>

                                <p class="mt-4 text-xs leading-6 text-slate-600 dark:text-slate-300">
                                    {{ $riasecExplanations[$char]['desc'] }}
                                </p>

                            </div>

                        </div>

                    @endif

                @endforeach

            </div>
        </section>

    @endif

    {{-- =========================================================
        CATATAN AKADEMIK
    ========================================================== --}}
    <section class="rounded-3xl border border-slate-200 bg-slate-50/70 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">

        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-start sm:p-8">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300">
                <i class="fa-solid fa-circle-info text-sm"></i>
            </div>

            <div>
                <h3 class="text-sm font-bold text-blue-950 dark:text-blue-100">
                    Catatan Interpretasi
                </h3>

                <p class="mt-2 text-xs leading-6 text-slate-600 dark:text-slate-300">
                    Hasil asesmen RIASEC menggambarkan kecenderungan minat berdasarkan jawaban yang diberikan saat asesmen. Hasil ini bukan penentu tunggal pilihan program studi, tetapi dapat digunakan sebagai bahan pertimbangan dalam proses eksplorasi dan pengambilan keputusan akademik.
                </p>
            </div>

        </div>
    </section>

    {{-- =========================================================
        CTA REKOMENDASI
    ========================================================== --}}
    <section class="relative overflow-hidden rounded-3xl border border-blue-800 bg-blue-900 shadow-md dark:border-blue-900 dark:bg-blue-950">

        <div class="absolute -right-20 -top-24 h-52 w-52 rounded-full bg-blue-700/40 blur-2xl"></div>
        <div class="absolute -bottom-24 -left-20 h-48 w-48 rounded-full bg-sky-600/20 blur-2xl"></div>

        <div class="relative flex flex-col gap-6 p-6 sm:p-8 lg:flex-row lg:items-center lg:justify-between lg:p-9">

            <div class="max-w-2xl">

                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-blue-700/70 bg-blue-800/60 px-3 py-1.5 text-[9px] font-bold uppercase tracking-widest text-blue-200">
                    <i class="fa-solid fa-compass text-[9px]"></i>
                    Eksplorasi Akademik
                </div>

                <h2 class="text-xl font-bold text-white sm:text-2xl">
                    Temukan Program Studi yang Relevan
                </h2>

                <p class="mt-2 text-xs leading-6 text-blue-100/80 sm:text-sm">
                    Gunakan profil RIASEC Anda untuk melihat rekomendasi program studi yang memiliki kesesuaian dengan karakteristik minat Anda.
                </p>

            </div>

            <a
                href="{{ route('student.recommendations.index') }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-5 py-3.5 text-xs font-bold text-blue-900 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-50 hover:shadow-lg dark:bg-blue-50 dark:hover:bg-white"
            >
                Lihat Rekomendasi Studi
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>

        </div>
    </section>

</div>
@endsection