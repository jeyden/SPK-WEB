@extends('counselor.layouts.app')
@section('title', 'Perhitungan SAW')

@section('content')
@php
$criteria = [
    'R' => ['name' => 'Realistic', 'type' => 'benefit'],
    'I' => ['name' => 'Investigative', 'type' => 'benefit'],
    'A' => ['name' => 'Artistic', 'type' => 'benefit'],
    'S' => ['name' => 'Social', 'type' => 'benefit'],
    'E' => ['name' => 'Enterprising', 'type' => 'benefit'],
    'C' => ['name' => 'Conventional', 'type' => 'benefit']
];

$rawScores = [
    'R' => (float)($student->riasecScore->r_score ?? 0),
    'I' => (float)($student->riasecScore->i_score ?? 0),
    'A' => (float)($student->riasecScore->a_score ?? 0),
    'S' => (float)($student->riasecScore->s_score ?? 0),
    'E' => (float)($student->riasecScore->e_score ?? 0),
    'C' => (float)($student->riasecScore->c_score ?? 0)
];

$normalizedScores = [];
foreach($rawScores as $code => $score) {
    $normalizedScores[$code] = max(0, min($score, 20)) / 20;
}

$totalRawScore = array_sum($rawScores);

$calculationRows = collect($ranked ?? [])->map(function($row) use ($criteria, $normalizedScores) {
    $source = $row['weights'] ?? [];
    $raw = [];

    foreach($criteria as $code => $val) {
        $raw[$code] = max(0, (float)($source[$code] ?? 0));
    }

    $sum = array_sum($raw);
    $weights = [];
    $contributions = [];

    foreach($criteria as $code => $val) {
        $weights[$code] = $sum > 0 ? $raw[$code] / $sum : 0;
        $contributions[$code] = $normalizedScores[$code] * $weights[$code];
    }

    return [
        'major' => $row['major'] ?? null,
        'raw_weights' => $raw,
        'weights' => $weights,
        'contributions' => $contributions,
        'preference_score' => array_sum($contributions)
    ];
})->sortByDesc('preference_score')->values()->map(function($row, $i) {
    $row['rank'] = $i + 1;
    $row['percentage'] = $row['preference_score'] * 100;
    return $row;
});

$alternativeCount = $calculationRows->count();
$best = $calculationRows->first();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 space-y-6">

    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <a href="{{ route('counselor.calculation.index', ['academic_year' => $academicYear]) }}"
               class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition mb-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Analisis
            </a>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                Laporan Tahapan Perhitungan SAW
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Rincian langkah demi langkah metode <em>Simple Additive Weighting</em> untuk rekomendasi program studi siswa.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-700 dark:text-slate-300 font-medium">
                Tahun: <strong class="text-slate-900 dark:text-white">{{ $academicYear ?? '-' }}</strong>
            </span>
            <span class="px-3 py-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 text-xs text-indigo-700 dark:text-indigo-300 font-medium">
                Alternatif: <strong class="text-indigo-800 dark:text-indigo-200">{{ $alternativeCount }} Prodi</strong>
            </span>
        </div>
    </div>

    @if($student->riasecScore)

        {{-- KARTU PEMENANG --}}
        @if($best)
            @php $bestMajor = $best['major']; @endphp
            <div class="rounded-2xl border border-indigo-200 dark:border-indigo-500/30 bg-gradient-to-r from-indigo-900 via-indigo-800 to-violet-900 text-white p-5 shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-trophy text-xl text-amber-300"></i>
                        </div>
                        <div>
                            <span class="inline-block px-2 py-0.5 rounded bg-white/10 text-indigo-200 text-[10px] font-black uppercase tracking-wider mb-1">
                                Rekomendasi Utama (Peringkat 1)
                            </span>
                            <h2 class="text-sm sm:text-base font-black leading-snug">
                                {{ optional($bestMajor)->degree }} {{ optional($bestMajor)->name ?? 'Program Studi Tidak Ditemukan' }}
                            </h2>
                        </div>
                    </div>
                    <div class="sm:text-right bg-black/20 sm:bg-transparent p-3 sm:p-0 rounded-xl">
                        <div class="text-xl sm:text-2xl font-black font-mono text-amber-300">
                            {{ number_format($best['preference_score'], 4, ',', '.') }}
                        </div>
                        <div class="text-xs text-indigo-200 font-medium mt-0.5">
                            Nilai Preferensi (V<sub>i</sub>) · <strong class="text-white">{{ number_format($best['percentage'], 1, ',', '.') }}% Kecocokan</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- TAHAP 1 & 2: RINGKASAN SKOR & NORMALISASI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-sm">
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Tahap 1</span>
                <h3 class="text-xs font-black text-slate-900 dark:text-white mt-0.5 mb-3">Matriks Keputusan (Skor Mentah X<sub>ij</sub>)</h3>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($criteria as $code => $info)
                        <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 p-2 text-center">
                            <div class="text-[9px] font-black text-slate-400">{{ $code }}</div>
                            <div class="text-sm font-black text-slate-900 dark:text-white font-mono">{{ number_format($rawScores[$code], 0) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-sm">
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Tahap 2</span>
                <h3 class="text-xs font-black text-slate-900 dark:text-white mt-0.5 mb-3">Normalisasi Matriks (R<sub>ij</sub> = X<sub>ij</sub> ÷ 20)</h3>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($criteria as $code => $info)
                        <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 p-2 text-center">
                            <div class="text-[9px] font-black text-slate-400">{{ $code }}</div>
                            <div class="text-sm font-black text-indigo-600 dark:text-indigo-400 font-mono">{{ number_format($normalizedScores[$code], 2) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- TAHAP 3: BOBOT KRITERIA PROGRAM STUDI (W) --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50">
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Tahap 3</span>
                <h2 class="text-xs font-black text-slate-900 dark:text-white">Matriks Bobot Kriteria Program Studi (W<sub>ij</sub>)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-950/80 text-slate-400 uppercase text-[9px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                            <th class="py-2.5 px-3 font-black">Program Studi</th>
                            @foreach($criteria as $code => $info)
                                <th class="py-2.5 px-2 text-center font-black text-indigo-600 dark:text-indigo-400 w-12">{{ $code }}</th>
                            @endforeach
                            <th class="py-2.5 px-3 text-center font-black w-16">Σ W<sub>ij</sub></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-mono text-[11px]">
                        @foreach($calculationRows as $row)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                <td class="py-2.5 px-3 font-sans font-bold text-slate-900 dark:text-white">
                                    {{ optional($row['major'])->degree }} {{ optional($row['major'])->name }}
                                </td>
                                @foreach($criteria as $code => $info)
                                    <td class="py-2.5 px-2 text-center text-slate-700 dark:text-slate-300">
                                        {{ number_format($row['weights'][$code], 2) }}
                                    </td>
                                @endforeach
                                <td class="py-2.5 px-3 text-center font-bold text-slate-900 dark:text-white">
                                    {{ number_format(array_sum($row['weights']), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAHAP 4: TABEL PERHITUNGAN & PERANKINGAN AKHIR --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50">
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Tahap 4</span>
                <h2 class="text-xs font-black text-slate-900 dark:text-white">
                    Perhitungan Matriks Terbobot (R<sub>ij</sub> × W<sub>ij</sub>) & Nilai Akhir (V<sub>i</sub>)
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-950/80 text-slate-400 uppercase text-[9px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                            <th class="py-2.5 px-2 text-center font-black w-10">Rank</th>
                            <th class="py-2.5 px-3 font-black">Program Studi</th>
                            @foreach($criteria as $code => $info)
                                <th class="py-2.5 px-1 text-center font-black text-indigo-600 dark:text-indigo-400 border-l border-slate-200 dark:border-slate-800 w-14">
                                    {{ $code }}
                                    <span class="block text-[8px] font-normal text-slate-400 normal-case">(R<sub>ij</sub> × W<sub>ij</sub>)</span>
                                </th>
                            @endforeach
                            <th class="py-2.5 px-3 text-center font-black text-violet-600 dark:text-violet-400 border-l border-slate-200 dark:border-slate-800 w-20">
                                Nilai (V<sub>i</sub>)
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-mono">
                        @foreach($calculationRows as $row)
                            @php
                                $major = $row['major'];
                                $isTop = $row['rank'] === 1;
                            @endphp

                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 {{ $isTop ? 'bg-indigo-50/50 dark:bg-indigo-500/10 font-semibold' : '' }}">
                                <td class="py-2.5 px-2 text-center font-sans font-bold text-slate-700 dark:text-slate-300 align-middle">
                                    @if($isTop)
                                        <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-indigo-600 text-white text-[9px] shadow-sm">1</span>
                                    @else
                                        {{ $row['rank'] }}
                                    @endif
                                </td>

                                <td class="py-2.5 px-3 font-sans align-middle">
                                    <div class="font-bold text-slate-900 dark:text-white text-xs leading-snug">
                                        {{ optional($major)->degree }} {{ optional($major)->name ?? 'Program Studi Tidak Ditemukan' }}
                                    </div>
                                    @if(optional($major)->code)
                                        <div class="text-[9px] text-slate-400 mt-0.5 font-mono">Kode: {{ $major->code }}</div>
                                    @endif
                                </td>

                                @foreach($criteria as $code => $info)
                                    <td class="py-2 px-1 text-center align-middle border-l border-slate-100 dark:border-slate-800/60 bg-slate-50/20 dark:bg-slate-950/20">
                                        <div class="flex flex-col items-center justify-center text-[10px] leading-tight space-y-0.5">
                                            <span class="text-slate-400 text-[9px]" title="Nilai Normalisasi (Rij)">
                                                {{ number_format($normalizedScores[$code], 2) }}
                                            </span>
                                            <span class="text-[8px] text-slate-300 dark:text-slate-600 font-bold">×</span>
                                            <span class="text-slate-700 dark:text-slate-300 text-[9px]" title="Bobot (Wij)">
                                                {{ number_format($row['weights'][$code], 2) }}
                                            </span>
                                            <span class="w-6 border-b border-slate-200 dark:border-slate-700 my-0.5"></span>
                                            <span class="text-amber-600 dark:text-amber-400 font-bold text-[9px]" title="Hasil R_ij × W_ij">
                                                {{ number_format($row['contributions'][$code], 3) }}
                                            </span>
                                        </div>
                                    </td>
                                @endforeach

                                <td class="py-2.5 px-3 text-center align-middle border-l border-slate-100 dark:border-slate-800/60 font-sans">
                                    <div class="text-xs font-black text-violet-700 dark:text-violet-300 font-mono">
                                        {{ number_format($row['preference_score'], 4, ',', '.') }}
                                    </div>
                                    <div class="text-[9px] text-emerald-600 dark:text-emerald-400 font-bold mt-0.5 font-mono">
                                        {{ number_format($row['percentage'], 1, ',', '.') }}%
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-2.5 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-[11px] text-slate-500 dark:text-slate-400 flex flex-wrap gap-4">
                <span><strong class="text-slate-400">Atas:</strong> R<sub>ij</sub> = Nilai Normalisasi</span>
                <span><strong class="text-slate-700 dark:text-slate-300">Tengah:</strong> W<sub>ij</sub> = Bobot</span>
                <span><strong class="text-amber-600 dark:text-amber-400">Bawah:</strong> R<sub>ij</sub> × W<sub>ij</sub> = Kontribusi</span>
            </div>
        </div>

    @else
        {{-- KONDISI JIKA RIASEC KOSONG --}}
        <div class="rounded-2xl border border-amber-200 dark:border-amber-500/20 bg-white dark:bg-slate-900 p-8 text-center shadow-sm">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h2 class="text-sm font-black text-slate-900 dark:text-white">Data RIASEC Belum Tersedia</h2>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Perhitungan SAW belum dapat dijalankan karena siswa belum menyelesaikan tes asesmen profil minat RIASEC.
            </p>
        </div>
    @endif

</div>
@endsection