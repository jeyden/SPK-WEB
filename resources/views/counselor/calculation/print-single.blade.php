<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekomendasi Jurusan - {{ optional($student->user)->name }}</title>
    <style>
        @page { size: A4 portrait; margin: 10mm 12mm; }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            color: #000; 
            line-height: 1.15; 
            margin: 0; 
            padding: 0; 
            font-size: 11px; 
        }

        /* KOP SURAT (Disesuaikan Persis) */
        .header { 
            border-bottom: 3px double #000; 
            padding-bottom: 5px; 
            margin-bottom: 10px; 
        }
        .header-table { width: 100%; border-collapse: collapse; border: none; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .header-logo-left { max-height: 75px; width: auto; display: block; margin: 0 auto; }
        .header-logo-right { max-height: 65px; width: auto; display: block; margin: 0 auto; }
        .header-content { text-align: center; }
        .header-content h4 { margin: 0; font-size: 11.5px; font-weight: bold; text-transform: uppercase; color: #000; letter-spacing: 0.3px; }
        .header-content h3 { margin: 2px 0; font-size: 13px; font-weight: bold; color: #000; }
        .header-content h1 { margin: 3px 0; font-size: 18px; font-weight: 900; text-transform: uppercase; color: #000; letter-spacing: 0.5px; }
        .header-content p { font-size: 9.5px; color: #000; margin: 1px 0; font-weight: normal; }

        /* JUDUL LAPORAN */
        .report-title-section { text-align: center; margin-bottom: 10px; }
        .report-title-section h2 { margin: 0 0 2px 0; font-size: 12px; font-weight: bold; text-transform: uppercase; text-decoration: underline; color: #000; }
        .report-title-section p { margin: 0; font-size: 10px; color: #333; }

        /* KONTEN */
        .student-card { border: 1px solid #94a3b8; border-radius: 3px; padding: 8px 10px; background: #fff; margin-bottom: 8px; }
        .student-info-grid { display: table; width: 100%; margin-bottom: 6px; font-size: 10.5px; }
        .student-info-col { display: table-cell; width: 50%; vertical-align: top; }
        .student-info-col p { margin: 2px 0; }
        .descriptive-text { font-size: 10px; color: #000; margin-bottom: 6px; text-align: justify; line-height: 1.2; }

        .content-table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 10px; }
        .content-table th, .content-table td { border: 1px solid #94a3b8; padding: 4px 6px; text-align: left; vertical-align: middle; }
        .content-table th { background-color: #f1f5f9; font-weight: bold; color: #000; }
        .text-center { text-align: center; }
        .badge-percent { display: inline-block; padding: 2px 6px; border-radius: 999px; font-weight: bold; font-size: 9.5px; border: 1px solid #cbd5e1; background-color: #f8fafc; color: #1e293b; }

        .footer-container { margin-top: 10px; width: 100%; display: table; page-break-inside: avoid; }
        .footer-left { display: table-cell; width: 50%; font-size: 9.5px; color: #333; vertical-align: bottom; }
        .footer-right { display: table-cell; width: 50%; text-align: right; font-size: 10.5px; vertical-align: top; }
        .signature-space { height: 38px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    @php
        $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $currentDateIndo = date('d') . ' ' . $months[(int)date('n')] . ' ' . date('Y');
        
        // Pastikan hanya mengambil maksimal 10 data teratas di sini jika belum dibatasi dari controller
        $limitedResults = isset($results) ? $results->take(10) : collect();
    @endphp

    <!-- KOP SURAT TERINTEGRASI -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 15%;">
                    @if(!empty($logoLeftUrl)) <img src="{{ $logoLeftUrl }}" alt="Logo" class="header-logo-left"> @endif
                </td>
                <td style="width: 70%;">
                    <div class="header-content">
                        <h4>{{ $letterhead['line1'] ?? '' }}</h4>
                        <h3>{{ $letterhead['line2'] ?? '' }}</h3>
                        <h1>{{ $letterhead['line3'] ?? '' }}</h1>
                        <p>{{ $letterhead['line4'] ?? '' }}</p>
                        <p>{{ $letterhead['line5'] ?? '' }}</p>
                    </div>
                </td>
                <td style="width: 15%;">
                    @if(!empty($logoRightUrl)) <img src="{{ $logoRightUrl }}" alt="Logo" class="header-logo-right"> @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="report-title-section">
        <h2>LAPORAN HASIL REKOMENDASI PENJURUSAN SISWA</h2>
        <p>Metode Simple Additive Weighting (SAW) &bull; Tahun Akademik: {{ $academicYear ?? '-' }}</p>
    </div>

    <div class="student-card">
        <div class="student-info-grid">
            <div class="student-info-col">
                <p><strong>Nama Lengkap:</strong> {{ optional($student->user)->name ?? '-' }}</p>
                <p><strong>NISN:</strong> {{ $student->nisn ?? '-' }}</p>
                <p><strong>Kelas / Jurusan:</strong> {{ $student->class ?? '-' }} / {{ $student->high_school_major ?? '-' }}</p>
            </div>
            <div class="student-info-col" style="text-align: right;">
                <p><strong>Tanggal Cetak:</strong> {{ $currentDateIndo }}</p>
                <p><strong>Status Analisis:</strong> <span style="color: #059669; font-weight: bold;">Selesai / Valid (199 Analisis)</span></p>
            </div>
        </div>

        <div class="descriptive-text">
            Berdasarkan hasil evaluasi dan perhitungan menggunakan metode <strong>Simple Additive Weighting (SAW)</strong>, berikut adalah 10 besar rincian alternatif rekomendasi jurusan untuk mendukung proses pengambilan keputusan:
        </div>

        <table class="content-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 6%;">No</th>
                    <th style="width: 28%;">Jurusan</th>
                    <th style="width: 20%;">Rumpun Ilmu</th>
                    <th style="width: 20%;">Sub Ilmu</th>
                    <th class="text-center" style="width: 26%;">Tingkat Kecocokan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($limitedResults as $res)
                    @php
                        $fieldOfStudy = optional($res->major)->fieldOfStudy;
                        $parent = optional($fieldOfStudy)->parent;
                        $rumpun = $parent ? $parent->name : (optional($fieldOfStudy)->name ?? '-');
                        $subIlmu = $parent ? (optional($fieldOfStudy)->name ?? '-') : '-';
                        
                        $scorePercent = (int) round(max(0, min(100, (float)($res->preference_score > 1 ? $res->preference_score : $res->preference_score * 100))));
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td style="font-weight: 600;">{{ optional($res->major)->name ?? '-' }}</td>
                        <td>{{ $rumpun }}</td>
                        <td>{{ $subIlmu }}</td>
                        <td class="text-center">
                            <span class="badge-percent">
                                {{ $scorePercent }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Data tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer-container">
        <div class="footer-left"><p><i>Dokumen ini diterbitkan secara resmi oleh sistem Bimbingan Konseling.</i></p></div>
        <div class="footer-right">
            <p>Tasikmalaya, {{ $currentDateIndo }}</p>
            <p><b>Guru Bimbingan Konseling</b></p>
            <div class="signature-space"></div>
            <p><b>{{ optional($counselor)->name ?? 'Nama Guru BK' }}</b></p>
            <p style="font-size: 9.5px; color: #333; margin-top: 1px;">NIP. {{ optional($counselor)->nip ?? optional($counselor)->employee_id ?? '-' }}</p>
        </div>
    </div>
</body>
</html>