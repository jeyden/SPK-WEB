@extends('counselor.layouts.app')

@section('title', 'Laporan Rekomendasi Program Studi & Analisis RIASEC')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-16 px-3 sm:px-4">

    {{-- HEADER & NAVIGATION --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <a href="{{ route('counselor.calculation.index', ['academic_year' => $academicYear]) }}"
               class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Rekapitulasi</span>
            </a>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('counselor.calculation.detail', $student->id) }}"
                   class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-xs font-bold transition">
                    <i class="fa-solid fa-file-lines"></i> Detail Perhitungan SAW
                </a>
                <a href="{{ route('counselor.calculation.print.single', $student->id) }}" target="_blank"
                   class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold transition">
                    <i class="fa-solid fa-print"></i> Cetak
                </a>
                <a href="{{ route('counselor.calculation.pdf.single', $student->id) }}" target="_blank"
                   class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-sm shadow-indigo-500/25 transition">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>

    {{-- PROFIL SISWA & RIASEC --}}
    <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 bg-gradient-to-r from-indigo-50/50 via-transparent to-transparent dark:from-indigo-950/20">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-block text-[10px] font-extrabold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-2.5 py-1 rounded-md mb-2">
                        Laporan Hasil Rekomendasi
                    </span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ optional($student->user)->name ?? 'Tanpa Nama' }}
                    </h1>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-slate-500 dark:text-slate-400">
                        <span>NISN: <b class="font-mono text-slate-700 dark:text-slate-300">{{ $student->nisn ?? '-' }}</b></span>
                        <span>•</span>
                        <span>Tahun Ajaran: <b class="text-slate-700 dark:text-slate-300">{{ $academicYear }}</b></span>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm self-start sm:self-center">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fa-solid fa-user-graduate text-sm"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400">Status</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Siswa Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIASEC SECTION --}}
        <div class="border-t border-slate-200 dark:border-slate-800 p-5 sm:p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="fa-solid fa-chart-pie text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900 dark:text-white">Analisis Minat RIASEC</h2>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Profil minat holistik sebagai dasar perhitungan kecocokan.</p>
                </div>
            </div>

            @if($student->riasecScore)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                    @foreach($riasecItems as $item)
                        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 p-3 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-1.5 py-0.5 rounded">{{ $item['code'] }}</span>
                                <span class="hidden sm:inline text-[10px] font-medium text-slate-400 truncate">{{ $item['name'] }}</span>
                            </div>
                            <div class="text-lg font-black text-slate-900 dark:text-white mt-1.5">
                                {{ $item['student_score'] }}
                                <span class="text-[10px] text-slate-400 font-medium">/20</span>
                            </div>
                            <div class="mt-2 h-1.5 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                                <div class="h-full rounded-full bg-indigo-500 transition-all duration-500" style="width: {{ $item['student_percent'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- KETERANGAN TSK --}}
                <div class="mt-4 rounded-xl border {{ $tskInfo['class'] }} p-4 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-chart-simple text-indigo-500"></i>
                                <span class="text-xs font-extrabold">Kekuatan Profil Minat (TSK)</span>
                            </div>
                            <p class="text-[11px] mt-1 leading-relaxed opacity-90">{{ $tskInfo['description'] }}</p>
                        </div>
                        <div class="shrink-0 text-center sm:text-right bg-white/60 dark:bg-slate-900/60 px-4 py-2 rounded-xl border border-black/5 dark:border-white/5">
                            <span class="block text-2xl font-black text-slate-900 dark:text-white">{{ $tsk }} <small class="text-xs font-semibold opacity-70">/120</small></span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">{{ $tskInfo['level'] }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex gap-3 items-start p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 dark:text-amber-400 mt-0.5"></i>
                    <p class="text-xs text-amber-800 dark:text-amber-300">Siswa belum menyelesaikan asesmen RIASEC.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- HASIL REKOMENDASI DENGAN SCROLL CONTAINER --}}
    <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        {{-- HEADER ANALISIS & INFORMASI DISATUKAN --}}
        <div class="p-5 sm:p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center shrink-0 text-emerald-600 dark:text-emerald-400">
                        <i class="fa-solid fa-ranking-star text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Analisis Kecocokan Program Studi</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                            Menampilkan perbandingan profil minat siswa dari total <strong class="text-slate-700 dark:text-slate-300">{{ $results->count() }} alternatif</strong> program studi menggunakan metode SAW.
                        </p>
                    </div>
                </div>
                <div class="shrink-0 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-xs font-bold">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Tampil Berurutan Berdasarkan Skor</span>
                </div>
            </div>
        </div>

        {{-- CONTAINER DENGAN SCROLL INTERNAL (Maksimum tinggi sekitar 700px) --}}
        <div class="p-4 sm:p-6 max-h-[700px] overflow-y-auto space-y-4 custom-scrollbar">
            @forelse($results as $r)
                <article class="rounded-2xl border {{ $r['rank'] == 1 ? 'border-indigo-300 dark:border-indigo-500/40 ring-1 ring-indigo-200 dark:ring-indigo-500/20 bg-indigo-50/20 dark:bg-indigo-950/10' : 'border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/40' }} overflow-hidden transition-all">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl {{ $r['rank'] == 1 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }} flex items-center justify-center shrink-0 font-black text-xs">
                                    #{{ $r['rank'] }}
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Alternatif Program Studi</span>
                                    <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white mt-0.5">{{ $r['major_name'] }}</h3>

                                    @if($r['rumpun'] !== '-' || $r['sub_ilmu'] !== '-')
                                        <div class="flex flex-wrap gap-x-2 gap-y-1 mt-1.5 text-[11px] text-slate-500 dark:text-slate-400">
                                            @if($r['rumpun'] !== '-')
                                                <span>Rumpun: <b class="text-slate-700 dark:text-slate-300">{{ $r['rumpun'] }}</b></span>
                                            @endif
                                            @if($r['sub_ilmu'] !== '-')
                                                <span>•</span>
                                                <span>Sub-Ilmu: <b class="text-slate-700 dark:text-slate-300">{{ $r['sub_ilmu'] }}</b></span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5 self-start sm:self-center">
                                <div class="px-3.5 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 text-center min-w-[75px]">
                                    <span class="block text-[9px] uppercase font-bold text-indigo-500 dark:text-indigo-400">Skor</span>
                                    <span class="block text-base font-black text-indigo-700 dark:text-indigo-300 leading-tight">{{ $r['score_percent_display'] }}%</span>
                                </div>
                                <span class="px-3 py-2 rounded-xl border text-[10px] font-extrabold whitespace-nowrap {{ $r['status_class'] }}">
                                    {{ $r['status'] }}
                                </span>
                            </div>
                        </div>

                        {{-- ANALISIS KECOCOKAN --}}
                        <div class="mt-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-lightbulb text-indigo-500 text-xs"></i>
                                <h4 class="text-xs font-extrabold text-slate-800 dark:text-slate-200">Analisis Kecocokan Minat</h4>
                            </div>
                            <p class="text-xs sm:text-[13px] leading-relaxed text-slate-600 dark:text-slate-300">{{ $r['analysis_text'] }}</p>
                        </div>

                        {{-- PTN REKOMENDASI --}}
                        <div class="mt-3 pt-3 border-t border-slate-200/60 dark:border-slate-800">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-building-columns text-indigo-500 text-xs"></i>
                                <h4 class="text-xs font-extrabold text-slate-700 dark:text-slate-300">PTN yang Memfasilitasi</h4>
                            </div>

                            @if($r['campuses']->count())
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($r['campuses'] as $c)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[10px] font-bold text-slate-700 dark:text-slate-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            {{ $c['campus']->name ?? '-' }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-[11px] text-slate-400 italic">Informasi PTN belum tersedia.</p>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-12">
                    <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-3 text-slate-400">
                        <i class="fa-solid fa-folder-open text-lg"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum Ada Rekomendasi</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Data rekomendasi belum tersedia untuk siswa ini.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection