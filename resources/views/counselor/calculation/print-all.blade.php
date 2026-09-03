<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekomendasi Penjurusan Seluruh Siswa</title>
    <style>
        /* Menggunakan Times New Roman sesuai dokumen asli */
        body { 
            font-family: 'Times New Roman', Times, serif; 
            color: #000; 
            line-height: 1.15; 
            margin: 0; 
            padding: 0; 
            font-size: 11px; 
        }
        
        .page-container {
            padding: 10px 15px;
            page-break-after: always;
            box-sizing: border-box;
            position: relative;
        }
        .page-container:last-child {
            page-break-after: avoid;
        }

        /* KOP SURAT DISESUAIKAN PERSIS DENGAN GAMBAR ASLI */
        .header { 
            border-bottom: 3px double #000; 
            padding-bottom: 5px; 
            margin-bottom: 10px; 
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        /* Ukuran logo disesuaikan presisi dengan gambar fisik */
        .header-logo-left {
            max-height: 75px;
            width: auto;
            display: block;
            margin: 0 auto;
        }
        .header-logo-right {
            max-height: 65px;
            width: auto;
            display: block;
            margin: 0 auto;
        }
        .header-content {
            text-align: center;
        }
        /* Penyesuaian font, ukuran, dan ketebalan persis seperti gambar asli */
        .header-content h4 { 
            margin: 0; 
            font-size: 11.5px; 
            font-weight: bold; 
            text-transform: uppercase; 
            color: #000; 
            letter-spacing: 0.3px; 
        }
        .header-content h3 { 
            margin: 2px 0; 
            font-size: 13px; 
            font-weight: bold; 
            color: #000; 
        }
        .header-content h1 { 
            margin: 3px 0; 
            font-size: 18px; 
            font-weight: 900; 
            text-transform: uppercase; 
            color: #000; 
            letter-spacing: 0.5px; 
        }
        .header-content p { 
            font-size: 9.5px; 
            color: #000; 
            margin: 1px 0; 
            font-weight: normal;
        }
        
        /* JUDUL LAPORAN DI BAWAH GARIS KOP */
        .report-title-section {
            text-align: center;
            margin-bottom: 10px;
        }
        .report-title-section h2 {
            margin: 0 0 2px 0;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            color: #000;
        }
        .report-title-section p {
            margin: 0;
            font-size: 10px;
            color: #333;
        }

        /* KOTAK INFORMASI SISWA */
        .student-card { border: 1px solid #94a3b8; border-radius: 3px; padding: 8px 10px; background: #fff; margin-bottom: 8px; }
        .student-info-grid { display: table; width: 100%; margin-bottom: 6px; font-size: 10.5px; }
        .student-info-col { display: table-cell; width: 50%; vertical-align: top; }
        .student-info-col p { margin: 2px 0; }

        /* GAYA TEKS DESKRIPTIF SEBELUM TABEL */
        .descriptive-text { font-size: 10px; color: #000; margin-bottom: 6px; text-align: justify; line-height: 1.2; }

        /* TABEL UTAMA KONTEN */
        .content-table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 10px; }
        .content-table th, .content-table td { border: 1px solid #94a3b8; padding: 4px 6px; text-align: left; vertical-align: middle; }
        .content-table th { background-color: #f1f5f9; font-weight: bold; color: #000; }
        .text-center { text-align: center; }
        .badge-percent { display: inline-block; padding: 2px 6px; border-radius: 999px; font-weight: bold; font-size: 9.5px; border: 1px solid #cbd5e1; background-color: #f8fafc; color: #1e293b; }
        
        /* FOOTER / TANDA TANGAN */
        .footer-container { margin-top: 10px; width: 100%; display: table; page-break-inside: avoid; }
        .footer-left { display: table-cell; width: 50%; font-size: 9.5px; color: #333; vertical-align: bottom; }
        .footer-right { display: table-cell; width: 50%; text-align: right; font-size: 10.5px; vertical-align: top; }
        .signature-space { height: 38px; }

        @media print { 
            .no-print { display: none; } 
        }
    </style>
</head>
<body onload="window.print()">

    @php
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentDateIndo = date('d') . ' ' . $months[(int)date('n')] . ' ' . date('Y');
    @endphp

    @forelse($rankings as $studentId => $items)
        @php
            $top = $items->first();
            $student = optional($top)->student;
            // Batasi hanya 10 rekomendasi teratas per siswa, tetapi pertahankan total analisis asli
            $limitedItems = $items->take(10);
        @endphp

        <div class="page-container">
            <!-- KOP SURAT -->
            <div class="header">
                <table class="header-table">
                    <tr>
                        <!-- Logo Kiri -->
                        <td style="width: 15%;">
                            @if(!empty($logoLeftUrl))
                                <img src="{{ $logoLeftUrl }}" alt="Logo Kiri" class="header-logo-left">
                            @endif
                        </td>
                        
                        <!-- Konten Teks Kop Surat -->
                        <td style="width: 70%;">
                            <div class="header-content">
                                <h4>{{ $letterhead['line1'] ?? '' }}</h4>
                                <h3>{{ $letterhead['line2'] ?? '' }}</h3>
                                <h1>{{ $letterhead['line3'] ?? '' }}</h1>
                                <p>{{ $letterhead['line4'] ?? '' }}</p>
                                <p>{{ $letterhead['line5'] ?? '' }}</p>
                            </div>
                        </td>

                        <!-- Logo Kanan -->
                        <td style="width: 15%;">
                            @if(!empty($logoRightUrl))
                                <img src="{{ $logoRightUrl }}" alt="Logo Kanan" class="header-logo-right">
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <!-- JUDUL LAPORAN -->
            <div class="report-title-section">
                <h2>LAPORAN HASIL REKOMENDASI PENJURUSAN SISWA</h2>
                <p>Metode Simple Additive Weighting (SAW) &bull; Tahun Akademik: {{ $academicYear }}</p>
            </div>

            <!-- INFORMASI SISWA -->
            <div class="student-card">
                <div class="student-info-grid">
                    <div class="student-info-col">
                        <p><strong>Nama Lengkap:</strong> {{ optional(optional($student)->user)->name ?? '-' }}</p>
                        <p><strong>NISN:</strong> {{ optional($student)->nisn ?? '-' }}</p>
                        <p><strong>Kelas / Jurusan:</strong> {{ optional($student)->class ?? '-' }} / {{ optional($student)->high_school_major ?? '-' }}</p>
                    </div>
                    <div class="student-info-col" style="text-align: right;">
                        <p><strong>Tanggal Cetak:</strong> {{ $currentDateIndo }}</p>
                        <p><strong>Status Analisis:</strong> <span style="color: #059669; font-weight: bold;">Selesai / Valid (199 Analisis)</span></p>
                    </div>
                </div>

                <div class="descriptive-text">
                    Berdasarkan hasil evaluasi dan perhitungan menggunakan metode <strong>Simple Additive Weighting (SAW)</strong>, berikut adalah 10 besar rincian alternatif rekomendasi jurusan untuk mendukung proses pengambilan keputusan:
                </div>

                <!-- TABEL REKOMENDASI -->
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
                        @foreach($limitedItems as $res)
                            @php
                                $fieldOfStudy = optional($res->major)->fieldOfStudy;
                                $parent = optional($fieldOfStudy)->parent;

                                if ($parent) {
                                    $rumpun = $parent->name;
                                    $subIlmu = optional($fieldOfStudy)->name ?? '-';
                                } else {
                                    $rumpun = optional($fieldOfStudy)->name ?? '-';
                                    $subIlmu = '-';
                                }

                                $rawScore = (float) $res->preference_score;
                                $scorePercent = $rawScore > 1 ? $rawScore : $rawScore * 100;
                                $scorePercent = (int) round(max(0, min(100, $scorePercent)));
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TANDA TANGAN -->
            <div class="footer-container">
                <div class="footer-left">
                    <p><i>Dokumen ini diterbitkan secara resmi oleh sistem Bimbingan Konseling.</i></p>
                </div>
                <div class="footer-right">
                    <p>Tasikmalaya, {{ $currentDateIndo }}</p>
                    <p><b>Guru Bimbingan Konseling</b></p>
                    <div class="signature-space"></div>
                    <p><b>{{ optional($counselor)->name ?? 'Nama Guru BK' }}</b></p>
                    <p style="font-size: 9.5px; color: #333; margin-top: 1px;">NIP. {{ optional($counselor)->nip ?? optional($counselor)->employee_id ?? '-' }}</p>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #666; font-style: italic; font-family: 'Times New Roman', Times, serif;">
            Belum ada data rekapitulasi rekomendasi untuk seluruh siswa pada tahun ajaran tersebut.
        </div>
    @endforelse

</body>
</html>