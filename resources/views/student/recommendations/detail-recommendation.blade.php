@extends('student.layouts.app')

@section('title', 'Detail Rekomendasi Program Studi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-16 px-0 sm:px-0">

    {{-- HEADER --}}
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="pointer-events-none absolute -right-20 -top-24 h-56 w-56 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="relative p-5 sm:p-7 lg:p-8">
            <a href="{{ route('student.recommendations.index') }}" class="mb-5 inline-flex items-center gap-2 text-[10px] font-bold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">
                <i class="fa-solid fa-arrow-left text-[9px]"></i>Kembali ke Daftar Rekomendasi
            </a>
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-[9px] font-black uppercase tracking-wider text-indigo-700 dark:border-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-300">
                            <i class="fa-solid fa-ranking-star"></i>Rekomendasi #{{ $recommendation->rank ?? '-' }}
                        </span>
                        <span class="rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $statusInfo['class'] }}">
                            <i class="fa-solid {{ $statusInfo['icon'] }} mr-1"></i>{{ $statusInfo['status'] }}
                        </span>
                    </div>
                    <h1 class="mt-4 text-xl font-black leading-tight tracking-tight text-slate-900 dark:text-white sm:text-2xl lg:text-3xl">
                        {{ $majorName }}
                    </h1>
                    <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-[10px] text-slate-500 dark:text-slate-400">
                        <span><i class="fa-solid fa-layer-group mr-1 text-indigo-500"></i>{{ $rumpun }}</span>
                        @if($subIlmu !== '-')
                            <span class="text-slate-300">•</span><span>{{ $subIlmu }}</span>
                        @endif
                        @if($academicYear)
                            <span class="text-slate-300">•</span><span>Tahun Akademik {{ $academicYear }}</span>
                        @endif
                    </div>
                    @if(optional($major)->description)
                        <p class="mt-4 max-w-3xl text-xs leading-6 text-slate-600 dark:text-slate-300">{{ $major->description }}</p>
                    @endif
                </div>
                <div class="shrink-0 rounded-2xl border border-slate-200 bg-slate-50 p-5 lg:min-w-[190px] lg:text-right dark:border-slate-800 dark:bg-slate-800/50">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Tingkat Kecocokan</p>
                    <p class="mt-1 text-3xl font-black {{ $statusInfo['score_class'] }}">{{ $scorePercentDisplay }}%</p>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                        <div class="{{ $statusInfo['progress_class'] }} h-full rounded-full" style="width:{{ $scorePercent }}%"></div>
                    </div>
                    <p class="mt-2 text-[9px] text-slate-400">Nilai preferensi SAW</p>
                </div>
            </div>
        </div>
    </section>

    {{-- RINGKASAN --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400"><i class="fa-solid fa-chart-line text-xs"></i></span>
                <div><p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Skor SAW</p><p class="text-lg font-black text-slate-900 dark:text-white">{{ $scorePercentDisplay }}%</p></div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400"><i class="fa-solid fa-brain text-xs"></i></span>
                <div><p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">TSK RIASEC</p><p class="text-lg font-black text-slate-900 dark:text-white">{{ number_format($tsk, 0) }}<span class="text-[10px] text-slate-400"> / 120</span></p></div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400"><i class="fa-solid fa-link text-xs"></i></span>
                <div><p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Dimensi Sesuai</p><p class="text-lg font-black text-slate-900 dark:text-white">{{ $riasecPresentation['matched_count'] }}<span class="text-[10px] text-slate-400"> / 3</span></p></div>
            </div>
        </div>
    </section>

    {{-- TSK --}}
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-800 sm:px-7">
            <span class="text-[9px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Profil Minat</span>
            <h2 class="mt-1 text-base font-black text-slate-900 dark:text-white sm:text-lg">Total Skor Keseluruhan RIASEC</h2>
        </div>
        <div class="p-5 sm:p-7">
            <div class="rounded-2xl border p-5 {{ $tskInfo['class'] }}">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest opacity-70">TSK RIASEC</p>
                        <div class="mt-1 flex items-end gap-3">
                            <span class="text-3xl font-black">{{ number_format($tsk, 0) }}</span>
                            <span class="mb-1 text-xs font-bold opacity-60">/ 120</span>
                        </div>
                    </div>
                    <span class="inline-flex w-fit items-center rounded-lg border px-3 py-1.5 text-[10px] font-black uppercase tracking-wide {{ $tskInfo['class'] }}">{{ $tskInfo['level'] }}</span>
                </div>
                <div class="mt-4 h-2 overflow-hidden rounded-full bg-white/60 dark:bg-slate-800/50">
                    <div class="h-full rounded-full bg-current opacity-60" style="width:{{ min(100, round(($tsk / 120) * 100)) }}%"></div>
                </div>
                <p class="mt-4 text-xs leading-6 opacity-80">{{ $tskInfo['description'] }}</p>
            </div>
        </div>
    </section>

    {{-- ANALISIS --}}
    <section class="rounded-3xl border border-indigo-100 bg-indigo-50/60 shadow-sm dark:border-indigo-900/50 dark:bg-indigo-950/20">
        <div class="p-5 sm:p-7">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white"><i class="fa-solid fa-lightbulb text-sm"></i></div>
                <div>
                    <span class="text-[9px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Analisis Personal</span>
                    <h2 class="mt-1 text-base font-black text-indigo-950 dark:text-indigo-100 sm:text-lg">Mengapa Program Studi Ini Direkomendasikan?</h2>
                    <p class="mt-3 text-xs leading-6 text-indigo-950/75 dark:text-indigo-100/75 sm:text-sm">{{ $analysisText }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- PROFIL SISWA --}}
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-800 sm:px-7">
            <span class="text-[9px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Profil Minat Siswa</span>
            <h2 class="mt-1 text-base font-black text-slate-900 dark:text-white sm:text-lg">Distribusi Dimensi RIASEC</h2>
        </div>
        <div class="grid grid-cols-1 gap-3 p-5 sm:grid-cols-2 sm:p-7 lg:grid-cols-3">
            @foreach($riasecPresentation['items'] as $code => $item)
                <div class="flex flex-col justify-between rounded-2xl border {{ $item['is_matched'] ? 'border-indigo-200 bg-indigo-50/50 dark:border-indigo-800/60 dark:bg-indigo-950/20' : 'border-slate-200 bg-slate-50/40 dark:border-slate-800 dark:bg-slate-800/20' }} p-4">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-[10px] font-black text-white">{{ $code }}</span>
                                <div>
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $item['name'] }}</span>
                                    <p class="text-[9px] text-slate-400">{{ $item['short'] }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-slate-900 dark:text-white">{{ number_format($item['student_score'], 0) }}</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                            <div class="h-full rounded-full bg-indigo-500 transition-all duration-300" style="width: {{ $item['student_percent'] }}%;"></div>
                        </div>
                        @if($item['is_matched'])
                            <div class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-indigo-100 px-2 py-1 text-[8px] font-black uppercase tracking-wide text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300"><i class="fa-solid fa-check"></i>Sesuai dengan bidang</div>
                        @elseif($item['is_student_top'])
                            <div class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2 py-1 text-[8px] font-black uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">Dimensi dominan siswa</div>
                        @else
                            <p class="mt-2 text-[9px] text-slate-400">Skor dimensi siswa</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- KARAKTERISTIK PROGRAM STUDI --}}
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-800 sm:px-7">
            <span class="text-[9px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Profil Program Studi</span>
            <h2 class="mt-1 text-base font-black text-slate-900 dark:text-white sm:text-lg">Karakteristik Minat Program Studi</h2>
            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">Bobot menunjukkan tingkat relevansi setiap dimensi RIASEC terhadap program studi.</p>
        </div>
        <div class="grid grid-cols-1 gap-3 p-5 sm:grid-cols-2 sm:p-7 lg:grid-cols-3">
            @foreach($riasecPresentation['items'] as $code => $item)
                <div class="flex flex-col justify-between rounded-2xl border {{ $item['is_major_top'] ? 'border-indigo-200 bg-indigo-50/50 dark:border-indigo-800 dark:bg-indigo-950/20' : 'border-slate-200 dark:border-slate-800' }} p-4">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-[10px] font-black text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">{{ $code }}</span>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white">{{ $item['name'] }}</span>
                                    @if($item['is_major_top'])
                                        <p class="text-[8px] font-bold uppercase text-indigo-600 dark:text-indigo-400">Dimensi utama prodi</p>
                                    @endif
                                </div>
                            </div>
                            <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">{{ number_format($item['major_weight'], 2) }}</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                            <div class="h-full rounded-full bg-indigo-500 transition-all duration-300" style="width: {{ $item['major_percent'] }}%;"></div>
                        </div>
                        <p class="mt-2 text-[9px] text-slate-400">Bobot relevansi program studi</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- PERHITUNGAN SAW --}}
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-800 sm:px-7">
            <span class="text-[9px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Metode Perhitungan</span>
            <h2 class="mt-1 text-base font-black text-slate-900 dark:text-white sm:text-lg">Perhitungan SAW Program Studi</h2>
            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">Normalisasi skor siswa dibandingkan dengan bobot karakteristik RIASEC program studi.</p>
        </div>
        <div class="p-5 sm:p-7">
            <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                <table class="w-full min-w-[760px] text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/70">
                        <tr>
                            <th class="px-4 py-3 text-[9px] font-black uppercase tracking-wider text-slate-500">Dimensi</th>
                            <th class="px-4 py-3 text-[9px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Skor Siswa</th>
                            <th class="px-4 py-3 text-[9px] font-black uppercase tracking-wider text-amber-600 dark:text-amber-400">Normalisasi</th>
                            <th class="px-4 py-3 text-[9px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Bobot Prodi</th>
                            <th class="px-4 py-3 text-[9px] font-black uppercase tracking-wider text-blue-600 dark:text-blue-400">Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($sawData['rows'] as $code => $row)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-[9px] font-black text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">{{ $code }}</span>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $row['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($row['student'], 0) }}/20</td>
                                <td class="px-4 py-3 text-xs font-bold text-amber-600 dark:text-amber-400">{{ number_format($row['normalized'], 4) }}</td>
                                <td class="px-4 py-3 text-xs font-black text-indigo-600 dark:text-indigo-400">{{ number_format($row['weight'], 2) }}</td>
                                <td class="px-4 py-3 text-xs font-black text-blue-600 dark:text-blue-400">{{ number_format($row['contribution'], 4) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-indigo-50/60 dark:bg-indigo-950/20">
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-right text-[10px] font-black uppercase text-slate-500">Total</td>
                            <td class="px-4 py-4 text-xs font-black text-indigo-700 dark:text-indigo-300">{{ number_format($sawData['total_weight'], 2) }}</td>
                            <td class="px-4 py-4 text-xs font-black text-blue-700 dark:text-blue-300">{{ number_format($sawData['total_contribution'], 4) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4 rounded-2xl border border-indigo-100 bg-indigo-50/50 p-5 dark:border-indigo-900/40 dark:bg-indigo-950/20">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Rumus Normalisasi</p>
                        <p class="mt-1 text-xs font-black text-slate-800 dark:text-white">Xij / 20</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Total Bobot</p>
                        <p class="mt-1 text-xs font-black text-slate-800 dark:text-white">{{ number_format($sawData['total_weight'], 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Nilai Preferensi SAW</p>
                        <p class="mt-1 text-xl font-black text-blue-700 dark:text-blue-300">{{ $scorePercentDisplay }}%</p>
                    </div>
                </div>
                <div class="mt-4 border-t border-indigo-100 pt-4 dark:border-indigo-900/40">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Rincian Formula</p>
                    <p class="mt-1 text-xs leading-6 text-slate-600 dark:text-slate-300">
                        Nilai preferensi diperoleh dari agregasi nilai normalisasi setiap dimensi RIASEC dengan bobot karakteristik program studi. Nilai yang tersimpan pada hasil rekomendasi digunakan sebagai nilai akhir sistem.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- PTN --}}
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-800 sm:px-7">
            <span class="text-[9px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Pilihan Perguruan Tinggi</span>
            <h2 class="mt-1 text-base font-black text-slate-900 dark:text-white sm:text-lg">PTN yang Menyediakan Program Studi Ini</h2>
            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">Daftar perguruan tinggi rujukan yang dikurasi dari jajaran top 5 PTN terbaik di Indonesia.</p>
        </div>
        <div class="p-5 sm:p-7">
            @if($campuses->count())
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    @foreach($campuses as $index => $campus)
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-800/30">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-xs font-black text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">{{ $index + 1 }}</div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-black text-slate-800 dark:text-white">{{ $campus->name }}</p>
                                <p class="mt-1 text-[9px] text-slate-500 dark:text-slate-400">Akreditasi: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $campus->pivot->accreditation ?? 'Unggul' }}</span> <span class="text-indigo-600 dark:text-indigo-400 font-semibold ml-1">• Top 5 PTN Indonesia</span></p>
                            </div>
                            <i class="fa-solid fa-building-columns text-sm text-indigo-500"></i>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 p-6 text-center dark:border-slate-700">
                    <i class="fa-solid fa-building-columns text-xl text-slate-400"></i>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Informasi perguruan tinggi untuk program studi ini belum tersedia.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- CATATAN --}}
    <section class="rounded-2xl border border-amber-100 bg-amber-50/60 p-5 dark:border-amber-900/40 dark:bg-amber-950/20">
        <div class="flex items-start gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400"><i class="fa-solid fa-circle-info text-xs"></i></span>
            <div>
                <h3 class="text-xs font-black text-amber-900 dark:text-amber-200">Catatan Penggunaan Hasil</h3>
                <p class="mt-1.5 text-[10px] leading-5 text-amber-900/70 dark:text-amber-200/70 sm:text-xs">Rekomendasi ini merupakan hasil pengolahan profil minat RIASEC menggunakan metode SAW. Hasil tidak dimaksudkan sebagai keputusan akhir, tetapi sebagai bahan pertimbangan untuk membantu siswa mengeksplorasi pilihan program studi yang lebih sesuai dengan karakteristik minatnya.</p>
            </div>
        </div>
    </section>

    {{-- NAVIGASI --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
        <a href="{{ route('student.recommendations.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-xs font-bold text-white shadow-sm hover:bg-indigo-700">
            Semua Rekomendasi<i class="fa-solid fa-list text-[10px]"></i>
        </a>
    </div>
</div>
@endsection