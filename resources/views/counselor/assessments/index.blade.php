@extends('counselor.layouts.app')

@section('title', 'Penilaian RIASEC Siswa')

@section('styles')
<link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- HEADER UTAMA -->
    <div class="bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 dark:from-slate-900/80 dark:via-slate-900/60 dark:to-slate-950/90 p-5 sm:p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 backdrop-blur-xl shadow-xl shadow-slate-200/50 dark:shadow-none space-y-4 sm:space-y-0 sm:flex sm:items-center sm:justify-between">
        <div class="space-y-1">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">Penilaian RIASEC Siswa</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pantau status asesmen RIASEC (24 pernyataan, 6 dimensi) dan status hasil rekomendasi SAW setiap siswa.</p>
        </div>
    </div>

    <!-- NOTIFIKASI FLASH -->
    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-400 text-xs flex items-center gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-sm flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-800 dark:text-rose-400 text-xs flex items-center gap-3 shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-sm flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- KONTROL PENCARIAN & FILTER TAHUN AKADEMIK -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-gradient-to-br from-slate-50 via-white to-indigo-50/20 dark:from-slate-900/80 dark:to-slate-950/90 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800/80 p-4 rounded-2xl shadow-sm">

        <form method="GET" action="" class="flex items-center gap-2.5 w-full sm:w-auto">
            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">Tahun Ajaran:</label>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="academic_year"
                    class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 transition-all w-full sm:w-44 shadow-2xs">
                    @for ($year = 2024; $year <= 2040; $year++)
                        @php $optionValue = $year . '/' . ($year + 1); @endphp
                        <option value="{{ $optionValue }}" {{ ($academicYear ?? '') === $optionValue ? 'selected' : '' }}>
                            {{ $optionValue }}
                        </option>
                    @endfor
                </select>

                <button type="submit" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-xl border border-indigo-200/70 dark:border-indigo-500/20 transition-all flex items-center justify-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-filter text-[10px]"></i>
                    <span>Filter</span>
                </button>
            </div>
        </form>

        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" id="searchStudent" placeholder="Cari nama atau NISN siswa..."
                class="w-full bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-all shadow-2xs">
        </div>
    </div>

    <!-- TABEL UTAMA -->
    <div class="bg-gradient-to-br from-slate-50 via-white to-indigo-50/20 dark:from-slate-900/80 dark:via-slate-900/60 dark:to-slate-950/90 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-none">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="studentTable">
                <thead class="bg-slate-100/80 dark:bg-slate-950/80 text-slate-500 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5">NISN / Nama Siswa</th>
                        <th class="px-5 py-3.5">Jurusan / Kelas</th>
                        <th class="px-5 py-3.5 text-center">TSK</th>
                        <th class="px-5 py-3.5 text-center">Status Asesmen</th>
                        <th class="px-5 py-3.5 text-center">Status Rekomendasi</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($students as $student)
                        @php
                            $studentName = optional($student->user)->name ?? 'Tanpa Nama';
                            $studentNisn = $student->nisn ?? '-';

                            // Gabungan Jurusan Sekolah + Kelas dalam satu field.
                            // Sesuaikan nama kolom berikut apabila skema Anda berbeda.
                            $majorLabel = $student->high_school_major ?? null;
                            $classLabel = $student->class_name ?? $student->kelas ?? null;
                            $classInfo = collect([$majorLabel, $classLabel])->filter()->implode(' / ');

                            $riasec = $student->riasecScore;
                            $tsk = $riasec->tsk ?? null;
                            $isCompleted = $student->assessment_status === 'completed';
                            $isRecommended = $student->recommendation_status === 'completed';
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-white/5 transition-all student-row" data-search="{{ strtolower($studentName . ' ' . $studentNisn) }}">

                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm">{{ $studentName }}</div>
                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $studentNisn }}</div>
                            </td>

                            <td class="px-5 py-4">
                                @if ($classInfo)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                        {{ $classInfo }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic text-[11px]">-</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if (!is_null($tsk))
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200/70 dark:border-indigo-500/20 text-indigo-700 dark:text-indigo-300 font-mono font-bold text-xs shadow-2xs">
                                        {{ $tsk }} / 120
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic text-[11px]">-</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if ($isCompleted)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200/70 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-500/10 border border-rose-200/70 dark:border-rose-500/20 text-rose-700 dark:text-rose-300 text-[11px] font-bold">
                                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Belum
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if ($isRecommended)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-200/70 dark:border-blue-500/20 text-blue-700 dark:text-blue-300 text-[11px] font-bold">
                                        <i class="fa-solid fa-ranking-star text-[10px]"></i> Ada Hasil
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[11px] font-bold">
                                        Belum Dihitung
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('counselor.assessments.assess', ['student' => $student->id, 'academic_year' => $academicYear]) }}"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/20 transition-all whitespace-nowrap">
                                    <i class="fa-solid fa-eye text-[11px]"></i>
                                    <span>Lihat Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                Belum ada data siswa yang terdaftar pada tahun ajaran ini.
                            </td>
                        </tr>
                    @endforelse

                    <tr id="noSearchResult" class="hidden">
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500 italic">
                            Tidak ada siswa yang cocok dengan kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchStudent').addEventListener('input', function(e) {
        let term = e.target.value.toLowerCase().trim();
        let rows = document.querySelectorAll('.student-row');
        let noResult = document.getElementById('noSearchResult');
        let visibleCount = 0;

        rows.forEach(row => {
            let searchData = row.getAttribute('data-search');
            if (searchData.includes(term)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (noResult) {
            if (visibleCount === 0 && rows.length > 0) {
                noResult.classList.remove('hidden');
            } else {
                noResult.classList.add('hidden');
            }
        }
    });
</script>
@endsection