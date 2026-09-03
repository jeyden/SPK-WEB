@extends('counselor.layouts.app')

@section('title', 'Perhitungan & Perangkingan SAW')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-16 px-0 sm:px-0">

    <!-- HEADER & ACTION BAR -->
    <div class="bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 dark:from-slate-900/80 dark:via-slate-900/60 dark:to-slate-950/90 p-5 sm:p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 backdrop-blur-xl shadow-xl shadow-slate-200/50 dark:shadow-none space-y-4 lg:space-y-0 lg:flex lg:items-center lg:justify-between">
        
        <!-- Teks Judul & Deskripsi -->
        <div class="space-y-1">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">Perhitungan & Rekomendasi SAW</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Kelola hasil rekomendasi penjurusan siswa menggunakan metode Simple Additive Weighting.</p>
        </div>

        <!-- Tombol Aksi Utama -->
<div class="flex flex-wrap items-center gap-2.5" x-data="{ isProcessing: false }">
    <!-- Form Proses Perhitungan -->
    <form method="POST" action="{{ route('counselor.calculation.process') }}" @submit="isProcessing = true" class="flex-1 sm:flex-none">
        @csrf
        <input type="hidden" name="academic_year" value="{{ $academicYear }}">
        <button type="submit"
            :disabled="isProcessing"
            onclick="const b=this; if(b.dataset.processing==='1'){event.preventDefault();return false;} b.dataset.processing='1'; b.disabled=true; b.classList.add('!bg-indigo-500','cursor-wait','scale-[0.98]'); const icon=b.querySelector('.calc-icon'); const spinner=b.querySelector('.calc-spinner'); const text=b.querySelector('.calc-text'); icon.classList.add('hidden'); spinner.classList.remove('hidden'); text.textContent='Memproses...'; event.preventDefault(); requestAnimationFrame(()=>requestAnimationFrame(()=>setTimeout(()=>b.form.submit(),80))); return false;"
            class="group relative w-full sm:w-auto min-w-[180px] overflow-hidden px-4 py-2.5 bg-gradient-to-r from-indigo-600 via-violet-600 to-indigo-600 bg-[length:200%_100%] hover:bg-[position:100%_0] text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-90 disabled:cursor-wait">
            <span class="absolute inset-0 -translate-x-full bg-white/20 skew-x-[-20deg] group-hover:animate-pulse pointer-events-none"></span>
            <i class="fa-solid fa-calculator calc-icon relative transition-all duration-200"></i>
            <i class="fa-solid fa-spinner fa-spin calc-spinner relative hidden"></i>
            <span class="calc-text relative">Proses Perhitungan</span>
        </button>
    </form>

    <!-- Modal Fullscreen Loading -->
    <div x-show="isProcessing" style="display: none;" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-gray-900/70 backdrop-blur-md transition-all">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-2xl flex flex-col items-center max-w-sm text-center mx-4 border border-gray-100 dark:border-gray-700">
            
            <!-- Menggunakan metode Render Stabil (Sama persis konsep yang sering dipakai di halaman login) -->
            <div class="w-20 h-20 mb-4 flex items-center justify-center bg-indigo-50 dark:bg-indigo-900/50 rounded-2xl">
                <div class="absolute w-16 h-16 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                <i class="fa-solid fa-calculator text-indigo-600 dark:text-indigo-400 text-2xl animate-pulse"></i>
            </div>

            <!-- Keterangan Teks -->
            <h3 class="text-base font-bold text-gray-900 dark:text-white mt-2">Sedang Menghitung SAW...</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 leading-relaxed">Sistem sedang memproses kriteria dan melakukan perangkingan rekomendasi jurusan siswa. Mohon tunggu sebentar.</p>
        </div>
    </div>

            <!-- Tombol Pengaturan & Laporan -->
            <button onclick="openSettingsModal()" class="flex-1 sm:flex-none px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition-all flex items-center justify-center gap-2 shadow-2xs">
                <i class="fa-solid fa-gear text-indigo-500"></i>
                <span>Pengaturan & Laporan</span>
            </button>
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
                <option value="">Semua Tahun</option>
                @for ($year = 2024; $year <= 2040; $year++)
                    @php
                        $optionValue = $year . '/' . ($year + 1);
                    @endphp
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

        <!-- Input Search Realtime -->
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" id="searchStudent" placeholder="Cari nama atau NISN siswa..."
                class="w-full bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-all shadow-2xs">
        </div>
    </div>

    <!-- TABEL UTAMA -->
    <div class="bg-gradient-to-br from-slate-50 via-white to-indigo-50/25 dark:from-slate-900/80 dark:via-slate-900/60 dark:to-slate-950/90 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-none">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="studentTable">
                <thead class="bg-slate-100/80 dark:bg-slate-950/80 text-slate-500 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5 w-4/12">Nama Siswa / NISN</th>
                        <th class="px-5 py-3.5 w-3/12">Jurusan Sekolah</th>
                        <th class="px-5 py-3.5 w-2/12">Status Perhitungan</th>
                        <th class="px-5 py-3.5 text-right w-3/12">Aksi / Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @php $renderedCount = 0; @endphp
                    @forelse ($rankings as $studentId => $items)
                        @php
                            $topRank = $items->first();
                            // PENTING: $topRank->student bisa null jika siswa terkait
                            // sudah dihapus (mis. akun user-nya dihapus admin) tapi
                            // baris hasil perhitungan ini belum ikut terhapus.
                            // Lewati baris semacam ini agar halaman tidak crash.
                            $student = $topRank->student ?? null;
                        @endphp

                        @continue(!$student)
                        @php $renderedCount++; @endphp

                        @php
                            // optional() di sini menjaga jika relasi user pada
                            // student juga null/sudah terhapus (soft delete dsb).
                            $studentName = optional($student->user)->name ?? 'Tanpa Nama';
                            $studentNisn = $student->nisn ?? '-';
                            $highSchoolMajor = $student->high_school_major ?? '-';
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-white/5 transition-all student-row" data-search="{{ strtolower($studentName . ' ' . $studentNisn . ' ' . $highSchoolMajor) }}">
                            <td class="px-5 py-4 align-middle">
                                <div class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm">{{ $studentName }}</div>
                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">NISN: {{ $studentNisn }}</div>
                            </td>
                            <td class="px-5 py-4 align-middle font-medium text-slate-700 dark:text-slate-300">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[11px] border border-slate-200 dark:border-slate-700">
                                    {{ $highSchoolMajor }}
                                </span>
                            </td>
                            <td class="px-5 py-4 align-middle">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200/70 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-[11px] font-medium shadow-2xs">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    <span>Selesai Dihitung</span>
                                </span>
                            </td>
                            <td class="px-5 py-4 align-middle text-right">
                                <a href="{{ route('counselor.calculation.report', $topRank->student_id) }}" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold transition-all inline-flex items-center gap-1.5 shadow-lg shadow-indigo-600/20 whitespace-nowrap">
                                    <i class="fa-solid fa-eye text-[11px]"></i>
                                    <span>Lihat Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                Belum ada data perhitungan untuk Tahun Ajaran <b class="text-slate-700 dark:text-slate-300">{{ $academicYear }}</b>. Silakan klik tombol <b class="text-indigo-600 dark:text-indigo-400">"Proses Perhitungan"</b> di atas.
                            </td>
                        </tr>
                    @endforelse

                    @if ($renderedCount === 0 && $rankings->count() > 0)
                        {{-- Semua baris ranking yang ada ternyata "yatim" (siswanya sudah terhapus). --}}
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                Data siswa untuk hasil perhitungan ini sudah tidak tersedia (mungkin telah dihapus). Silakan proses ulang perhitungan.
                            </td>
                        </tr>
                    @endif

                    <!-- Baris Kosong untuk Filter Pencarian -->
                    <tr id="noSearchResult" class="hidden">
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500 italic">
                            Tidak ada siswa yang cocok dengan kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- ================= MODAL PENGATURAN & EKSPOR REKAPITULASI (FINAL & BERSIH) ================= -->
<div id="settingsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm hidden p-4 sm:p-6">
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-4xl shadow-2xl relative overflow-hidden flex flex-col max-h-[92vh]">
        
        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base tracking-tight">Pengaturan & Laporan Rekapitulasi</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Konfigurasi instansi, kop surat, dan berkas cetak.</p>
            </div>
            <button type="button" onclick="closeSettingsModal()" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Body Form -->
        <form id="settingsForm" action="{{ route('counselor.calculation.settings.update') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 space-y-5">
            @csrf

            <!-- Hidden Weights -->
            <input type="hidden" name="weight_academic" value="{{ $weightAcademic ?? 0.50 }}">
            <input type="hidden" name="weight_riasec" value="{{ $weightRiasec ?? 0.30 }}">
            <input type="hidden" name="weight_major" value="{{ $weightMajor ?? 0.20 }}">

            <!-- Notifikasi AJAX -->
            <div id="settingsAlert" class="hidden p-3 rounded-xl text-xs flex items-center gap-2"></div>

            <!-- Grid Utama -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                
                <!-- KOLOM KIRI: Identitas & Logo (5 Kolom) -->
                <div class="md:col-span-5 space-y-4">
                    <!-- Identitas -->
                    <div class="p-4 rounded-2xl bg-slate-50/70 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 space-y-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Identitas Instansi</span>

                        <div>
                            <label class="block text-[11px] font-medium text-slate-600 dark:text-slate-400 mb-1">Tahun Akademik</label>
                            <input type="text" name="tahun_ajaran" value="{{ $academicYear ?? '' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 shadow-2xs font-medium" required>
                        </div>

                        <div>
                            <label class="block text-[11px] font-medium text-slate-600 dark:text-slate-400 mb-1">Nama Instansi</label>
                            <input type="text" name="nama_instansi" value="{{ $instansi ?? '' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 shadow-2xs font-medium">
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="p-4 rounded-2xl bg-slate-50/70 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 space-y-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Logo Cetak</span>

                        <div class="grid grid-cols-2 gap-3">
                            <!-- Logo Kiri -->
                            <div class="space-y-1">
                                <span class="block text-[10px] text-slate-500">Logo Kiri</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center overflow-hidden shrink-0">
                                        <img id="logoLeftPreview" src="{{ $logoLeftUrl ?? '' }}" alt="Kiri" class="w-full h-full object-contain {{ empty($logoLeftUrl) ? 'hidden' : '' }}">
                                        <span id="logoLeftPlaceholder" class="text-[8px] text-slate-400 {{ empty($logoLeftUrl) ? '' : 'hidden' }}">Null</span>
                                    </div>
                                    <label class="cursor-pointer bg-slate-200/60 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-2 py-1.5 rounded-lg text-[10px] font-semibold transition-all text-center w-full truncate">
                                        Pilih
                                        <input type="file" id="logoLeftInput" name="logo_left_file" accept="image/*" class="hidden">
                                    </label>
                                </div>
                                <input type="hidden" name="logo_left_url" value="{{ $logoLeftUrl ?? '' }}">
                            </div>

                            <!-- Logo Kanan -->
                            <div class="space-y-1">
                                <span class="block text-[10px] text-slate-500">Logo Kanan</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center overflow-hidden shrink-0">
                                        <img id="logoRightPreview" src="{{ $logoRightUrl ?? '' }}" alt="Kanan" class="w-full h-full object-contain {{ empty($logoRightUrl) ? 'hidden' : '' }}">
                                        <span id="logoRightPlaceholder" class="text-[8px] text-slate-400 {{ empty($logoRightUrl) ? '' : 'hidden' }}">Null</span>
                                    </div>
                                    <label class="cursor-pointer bg-slate-200/60 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-2 py-1.5 rounded-lg text-[10px] font-semibold transition-all text-center w-full truncate">
                                        Pilih
                                        <input type="file" id="logoRightInput" name="logo_right_file" accept="image/*" class="hidden">
                                    </label>
                                </div>
                                <input type="hidden" name="logo_right_url" value="{{ $logoRightUrl ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: Kop Surat 5 Baris (7 Kolom) -->
                <div class="md:col-span-7 p-4 rounded-2xl bg-slate-50/70 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 space-y-3">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block pb-1 border-b border-slate-200/40 dark:border-slate-800">Teks Kop Surat (5 Baris)</span>
                    
                    <div class="space-y-2.5">
                        <div>
                            <label class="block text-[10px] text-slate-500 dark:text-slate-400 mb-0.5">Baris 1 (Yayasan)</label>
                            <input type="text" name="letterhead_line1" value="{{ $letterheadLine1 ?? 'Majelis Dikdasmen Daerah Muhammadiyah Kabupaten Tasikmalaya' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 shadow-2xs">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 dark:text-slate-400 mb-0.5">Baris 2 (Pesantren/Unit)</label>
                            <input type="text" name="letterhead_line2" value="{{ $letterheadLine2 ?? 'Pondok Pesantren At-Tajdid' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 shadow-2xs">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 dark:text-slate-400 mb-0.5">Baris 3 (Nama Sekolah Utama)</label>
                            <input type="text" name="letterhead_line3" value="{{ $letterheadLine3 ?? 'SMA AT-TAJDID BOARDING SCHOOL' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-white font-semibold focus:outline-none focus:border-indigo-500 shadow-2xs">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 dark:text-slate-400 mb-0.5">Baris 4 (Alamat & Kontak)</label>
                            <input type="text" name="letterhead_line4" value="{{ $letterheadLine4 ?? 'Alamat: Jl. Muhammadiyah Cikedokan Singaparna, Telp. (0265) 546865 Tasikmalaya 46411' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 shadow-2xs">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 dark:text-slate-400 mb-0.5">Baris 5 (Website / Email)</label>
                            <input type="text" name="letterhead_line5" value="{{ $letterheadLine5 ?? 'Website: www.ponpesattajdid.sch.id' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 shadow-2xs">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Aksi -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <button type="submit" id="settingsSubmitBtn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm disabled:opacity-60">
                    <span id="settingsSubmitText">Simpan Pengaturan</span>
                </button>
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('counselor.calculation.export-pdf-all') }}" target="_blank" class="flex-1 sm:flex-none px-3.5 py-2 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-file-pdf text-[11px]"></i> <span>PDF Semua</span>
                    </a>
                    <a href="{{ route('counselor.calculation.print-all') }}" target="_blank" class="flex-1 sm:flex-none px-3.5 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-print text-[11px]"></i> <span>Cetak Semua</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= SCRIPT JAVASCRIPT AMAN (BEBAS ERROR CORS & NULL) ================= -->
<script>
    // Pencarian Siswa (Opsional / Aman jika elemen tidak ada)
    const searchStudent = document.getElementById('searchStudent');
    if (searchStudent) {
        searchStudent.addEventListener('input', function(e) {
            let term = e.target.value.toLowerCase().trim();
            let rows = document.querySelectorAll('.student-row');
            let noResult = document.getElementById('noSearchResult');
            let visibleCount = 0;

            rows.forEach(row => {
                let searchData = row.getAttribute('data-search') || '';
                if (searchData.toLowerCase().includes(term)) {
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
    }

    function openSettingsModal() {
        const modal = document.getElementById('settingsModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeSettingsModal() {
        const modal = document.getElementById('settingsModal');
        if (modal) modal.classList.add('hidden');
        hideSettingsAlert();
    }

    // Pratinjau Logo Kiri
    const logoLeftInput = document.getElementById('logoLeftInput');
    const logoLeftPreview = document.getElementById('logoLeftPreview');
    const logoLeftPlaceholder = document.getElementById('logoLeftPlaceholder');

    if (logoLeftInput) {
        logoLeftInput.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (event) {
                if (logoLeftPreview) {
                    logoLeftPreview.src = event.target.result;
                    logoLeftPreview.classList.remove('hidden');
                }
                if (logoLeftPlaceholder) logoLeftPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    }

    // Pratinjau Logo Kanan
    const logoRightInput = document.getElementById('logoRightInput');
    const logoRightPreview = document.getElementById('logoRightPreview');
    const logoRightPlaceholder = document.getElementById('logoRightPlaceholder');

    if (logoRightInput) {
        logoRightInput.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (event) {
                if (logoRightPreview) {
                    logoRightPreview.src = event.target.result;
                    logoRightPreview.classList.remove('hidden');
                }
                if (logoRightPlaceholder) logoRightPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    }

    function showSettingsAlert(type, message) {
        const alertBox = document.getElementById('settingsAlert');
        if (!alertBox) return;
        alertBox.textContent = message;
        alertBox.classList.remove('hidden');

        if (type === 'success') {
            alertBox.className = 'mt-4 p-3 rounded-xl text-xs flex items-center gap-2 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-400';
        } else {
            alertBox.className = 'mt-4 p-3 rounded-xl text-xs flex items-center gap-2 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-800 dark:text-rose-400';
        }
    }

    function hideSettingsAlert() {
        const alertBox = document.getElementById('settingsAlert');
        if (alertBox) alertBox.classList.add('hidden');
    }

    // AJAX Form Submit Setting
    const settingsForm = document.getElementById('settingsForm');
    const settingsSubmitBtn = document.getElementById('settingsSubmitBtn');
    const settingsSubmitText = document.getElementById('settingsSubmitText');

    if (settingsForm) {
        settingsForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            hideSettingsAlert();

            if (settingsSubmitBtn) settingsSubmitBtn.disabled = true;
            if (settingsSubmitText) settingsSubmitText.textContent = 'Menyimpan...';

            try {
                const formData = new FormData(settingsForm);

                const response = await fetch(settingsForm.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (!response.ok) {
                    const firstError = data.message
                        || (data.errors ? Object.values(data.errors)[0][0] : null)
                        || 'Terjadi kesalahan, silakan periksa kembali isian Anda.';

                    showSettingsAlert('error', firstError);
                    return;
                }

                showSettingsAlert('success', data.message || 'Pengaturan berhasil disimpan.');

                if (data.logo_left_url && logoLeftPreview && logoLeftPlaceholder) {
                    logoLeftPreview.src = data.logo_left_url;
                    logoLeftPreview.classList.remove('hidden');
                    logoLeftPlaceholder.classList.add('hidden');
                }
                if (data.logo_right_url && logoRightPreview && logoRightPlaceholder) {
                    logoRightPreview.src = data.logo_right_url;
                    logoRightPreview.classList.remove('hidden');
                    logoRightPlaceholder.classList.add('hidden');
                }
                if (logoLeftInput) logoLeftInput.value = '';
                if (logoRightInput) logoRightInput.value = '';

            } catch (err) {
                showSettingsAlert('error', 'Gagal terhubung ke server. Silakan coba lagi.');
            } finally {
                if (settingsSubmitBtn) settingsSubmitBtn.disabled = false;
                if (settingsSubmitText) settingsSubmitText.textContent = 'Simpan Pengaturan';
            }
        });
    }
</script>
@endsection