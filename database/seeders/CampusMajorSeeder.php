<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FieldOfStudy;
use App\Models\Major;
use App\Models\MajorCriteria;
use App\Models\Campus;

/**
 * ============================================================
 * CAMPUS MAJOR SEEDER
 * ============================================================
 *
 * CATATAN KOMPATIBILITAS DENGAN MESIN REKOMENDASI BARU
 * (App\Services\SawRecommendationService):
 *
 * 1. Kolom `code` pada setiap kampus (UI, ITB, UGM, IPB, ITS) SUDAH
 *    sesuai dengan kode yang dipakai SawRecommendationService::
 *    determineCampusByTsk() untuk threshold TSK. Jangan mengubah nilai
 *    `code` ini tanpa menyesuaikan juga service tersebut.
 * 2. Bobot RIASEC ($riasecWeights) disimpan apa adanya ke MajorCriteria
 *    (r_std..c_std) — TIDAK dihasilkan random dan TIDAK diambil dari
 *    field/rumpun. Setiap program studi (kombinasi degree+name) wajib
 *    memiliki bobotnya sendiri.
 * 3. Validasi otomatis di akhir run(): total bobot tiap program studi
 *    harus 1.00, dan jumlah alternatif unik harus 199.
 * 4. Kolom `academic_std` pada MajorCriteria TIDAK diisi oleh seeder
 *    ini (dan memang tidak lagi dipakai oleh mesin SAW).
 *
 * Kampus tahap 1:
 * 1. Universitas Indonesia
 * 2. Institut Teknologi Bandung
 * 3. Universitas Gadjah Mada
 * 4. IPB University
 * 5. Institut Teknologi Sepuluh Nopember
 *
 * Jenjang:
 * - S1
 * - D4 / Sarjana Terapan
 *
 * RIASEC:
 * R = Realistic
 * I = Investigative
 * A = Artistic
 * S = Social
 * E = Enterprising
 * C = Conventional
 */
class CampusMajorSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================
        // 1. KAMPUS
        // =========================================================

        $campuses = [
            [
                'name' => 'Universitas Indonesia',
                'code' => 'UI',
                'type' => 'PTN',
                'city' => 'Depok',
            ],
            [
                'name' => 'Institut Teknologi Bandung',
                'code' => 'ITB',
                'type' => 'PTN',
                'city' => 'Bandung',
            ],
            [
                'name' => 'Universitas Gadjah Mada',
                'code' => 'UGM',
                'type' => 'PTN',
                'city' => 'Yogyakarta',
            ],
            [
                'name' => 'IPB University',
                'code' => 'IPB',
                'type' => 'PTN',
                'city' => 'Bogor',
            ],
            [
                'name' => 'Institut Teknologi Sepuluh Nopember',
                'code' => 'ITS',
                'type' => 'PTN',
                'city' => 'Surabaya',
            ],
        ];

        $createdCampuses = [];

        foreach ($campuses as $campus) {
            $createdCampuses[$campus['code']] = Campus::updateOrCreate(
                ['code' => $campus['code']],
                $campus
            );
        }


        // =========================================================
        // 2. RUMPUN ILMU
        // =========================================================

        $fields = [];

        $mainFields = [
            'sains' => 'Sains dan Matematika',
            'komputer' => 'Ilmu Komputer dan Teknologi Informasi',
            'teknik' => 'Teknik dan Rekayasa',
            'kesehatan' => 'Kesehatan dan Kedokteran',
            'farmasi' => 'Farmasi',
            'pertanian' => 'Pertanian dan Kehutanan',
            'pangan' => 'Pangan dan Agroindustri',
            'kelautan' => 'Kelautan dan Perikanan',
            'ekonomi' => 'Ekonomi, Bisnis dan Manajemen',
            'sosial' => 'Sosial dan Politik',
            'hukum' => 'Hukum',
            'humaniora' => 'Humaniora dan Bahasa',
            'psikologi' => 'Psikologi',
            'arsitektur' => 'Arsitektur dan Perencanaan',
            'seni' => 'Seni dan Desain',
            'pendidikan' => 'Pendidikan',
        ];

        foreach ($mainFields as $key => $name) {
            $fields[$key] = FieldOfStudy::updateOrCreate(
                [
                    'name' => $name,
                    'parent_id' => null,
                ],
                [
                    'parent_id' => null,
                ]
            );
        }


        // =========================================================
        // 3. SUB ILMU
        // =========================================================

        $subFields = [

            // -------------------------
            // SAINS
            // -------------------------
            'matematika' => ['sains', 'Matematika, Statistika dan Data'],
            'fisika' => ['sains', 'Fisika dan Astronomi'],
            'kimia' => ['sains', 'Kimia'],
            'biologi' => ['sains', 'Biologi dan Mikrobiologi'],
            'kebumian' => ['sains', 'Kebumian dan Geosains'],

            // -------------------------
            // KOMPUTER
            // -------------------------
            'informatika' => ['komputer', 'Informatika dan Ilmu Komputer'],
            'sistem_informasi' => ['komputer', 'Sistem Informasi dan Teknologi Informasi'],
            'data_ai' => ['komputer', 'Data Science dan Kecerdasan Buatan'],

            // -------------------------
            // TEKNIK
            // -------------------------
            'sipil' => ['teknik', 'Teknik Sipil dan Infrastruktur'],
            'mesin' => ['teknik', 'Teknik Mesin dan Manufaktur'],
            'elektro' => ['teknik', 'Teknik Elektro dan Telekomunikasi'],
            'kimia_teknik' => ['teknik', 'Teknik Kimia dan Proses'],
            'industri' => ['teknik', 'Teknik Industri dan Sistem'],
            'material' => ['teknik', 'Material dan Metalurgi'],
            'geologi_teknik' => ['teknik', 'Geologi dan Pertambangan'],
            'kelautan_teknik' => ['teknik', 'Teknik Kelautan dan Perkapalan'],
            'lingkungan' => ['teknik', 'Teknik Lingkungan'],
            'geodesi' => ['teknik', 'Geodesi dan Geomatika'],
            'instrumentasi' => ['teknik', 'Instrumentasi dan Otomasi'],
            'biomedis' => ['teknik', 'Teknik Biomedis'],
            'dirgantara' => ['teknik', 'Dirgantara dan Penerbangan'],
            'energi' => ['teknik', 'Energi dan Konversi Energi'],

            // -------------------------
            // KESEHATAN
            // -------------------------
            'kedokteran' => ['kesehatan', 'Kedokteran'],
            'kedokteran_gigi' => ['kesehatan', 'Kedokteran Gigi'],
            'keperawatan' => ['kesehatan', 'Keperawatan'],
            'kesehatan_masyarakat' => ['kesehatan', 'Kesehatan Masyarakat'],
            'gizi' => ['kesehatan', 'Gizi'],
            'kesehatan_lingkungan' => ['kesehatan', 'Kesehatan Lingkungan'],
            'k3' => ['kesehatan', 'Keselamatan dan Kesehatan Kerja'],

            // -------------------------
            // FARMASI
            // -------------------------
            'farmasi_ilmu' => ['farmasi', 'Farmasi dan Ilmu Kefarmasian'],

            // -------------------------
            // PERTANIAN
            // -------------------------
            'agronomi' => ['pertanian', 'Agronomi dan Tanaman'],
            'agroteknologi' => ['pertanian', 'Agroteknologi dan Ilmu Tanah'],
            'agribisnis' => ['pertanian', 'Agribisnis dan Ekonomi Pertanian'],
            'peternakan' => ['pertanian', 'Peternakan dan Teknologi Ternak'],
            'kehutanan' => ['pertanian', 'Kehutanan dan Konservasi'],
            'sumberdaya_lahan' => ['pertanian', 'Sumberdaya Lahan dan Lingkungan'],

            // -------------------------
            // PANGAN
            // -------------------------
            'teknologi_pangan' => ['pangan', 'Teknologi Pangan'],
            'agroindustri' => ['pangan', 'Teknologi Industri Pertanian dan Agroindustri'],

            // -------------------------
            // KELAUTAN
            // -------------------------
            'kelautan' => ['kelautan', 'Ilmu Kelautan'],
            'perikanan' => ['kelautan', 'Perikanan dan Akuakultur'],
            'sumberdaya_perairan' => ['kelautan', 'Sumberdaya Perairan'],

            // -------------------------
            // EKONOMI
            // -------------------------
            'ekonomi_ilmu' => ['ekonomi', 'Ilmu Ekonomi dan Pembangunan'],
            'manajemen' => ['ekonomi', 'Manajemen dan Bisnis'],
            'akuntansi' => ['ekonomi', 'Akuntansi dan Keuangan'],
            'bisnis' => ['ekonomi', 'Bisnis dan Kewirausahaan'],
            'ekonomi_syariah' => ['ekonomi', 'Ekonomi dan Bisnis Syariah'],

            // -------------------------
            // SOSIAL
            // -------------------------
            'politik' => ['sosial', 'Ilmu Politik dan Pemerintahan'],
            'hubungan_internasional' => ['sosial', 'Hubungan Internasional'],
            'administrasi' => ['sosial', 'Administrasi Publik dan Negara'],
            'komunikasi' => ['sosial', 'Komunikasi dan Media'],
            'sosiologi' => ['sosial', 'Sosiologi dan Masyarakat'],
            'antropologi' => ['sosial', 'Antropologi'],
            'perpustakaan' => ['sosial', 'Perpustakaan dan Informasi'],

            // -------------------------
            // HUKUM
            // -------------------------
            'hukum_ilmu' => ['hukum', 'Ilmu Hukum'],

            // -------------------------
            // HUMANIORA
            // -------------------------
            'sejarah' => ['humaniora', 'Sejarah dan Arkeologi'],
            'filsafat' => ['humaniora', 'Filsafat'],
            'bahasa_sastra' => ['humaniora', 'Bahasa dan Sastra'],

            // -------------------------
            // PSIKOLOGI
            // -------------------------
            'psikologi_ilmu' => ['psikologi', 'Psikologi'],

            // -------------------------
            // ARSITEKTUR
            // -------------------------
            'arsitektur_ilmu' => ['arsitektur', 'Arsitektur'],
            'perencanaan' => ['arsitektur', 'Perencanaan Wilayah dan Kota'],

            // -------------------------
            // SENI
            // -------------------------
            'desain' => ['seni', 'Desain'],
            'seni_rupa' => ['seni', 'Seni Rupa'],

            // -------------------------
            // PENDIDIKAN
            // -------------------------
            'pendidikan_sains' => ['pendidikan', 'Pendidikan Sains dan Matematika'],
            'pendidikan_bahasa' => ['pendidikan', 'Pendidikan Bahasa'],
            'pendidikan_sosial' => ['pendidikan', 'Pendidikan Sosial'],
            'pendidikan_dasar' => ['pendidikan', 'Pendidikan Dasar dan Anak'],
            'pendidikan_olahraga' => ['pendidikan', 'Pendidikan Jasmani dan Olahraga'],
        ];

        foreach ($subFields as $key => [$parentKey, $name]) {
            $fields[$key] = FieldOfStudy::updateOrCreate(
                [
                    'name' => $name,
                    'parent_id' => $fields[$parentKey]->id,
                ],
                [
                    'parent_id' => $fields[$parentKey]->id,
                ]
            );
        }


        // =========================================================
        // 4. BOBOT RIASEC PER ALTERNATIF / PROGRAM STUDI
        // =========================================================
        // Setiap program studi memiliki profil RIASEC sendiri.
        // Total R + I + A + S + E + C wajib = 1.00.
        $riasecWeights = [
    'S1|Matematika' => ['R' => 0.11, 'I' => 0.34, 'A' => 0.07, 'S' => 0.10, 'E' => 0.10, 'C' => 0.28],
    'S1|Fisika' => ['R' => 0.18, 'I' => 0.34, 'A' => 0.06, 'S' => 0.08, 'E' => 0.11, 'C' => 0.23],
    'S1|Kimia' => ['R' => 0.19, 'I' => 0.32, 'A' => 0.07, 'S' => 0.10, 'E' => 0.10, 'C' => 0.22],
    'S1|Biologi' => ['R' => 0.15, 'I' => 0.32, 'A' => 0.10, 'S' => 0.17, 'E' => 0.09, 'C' => 0.17],
    'S1|Geofisika' => ['R' => 0.29, 'I' => 0.31, 'A' => 0.06, 'S' => 0.08, 'E' => 0.10, 'C' => 0.16],
    'S1|Geografi' => ['R' => 0.27, 'I' => 0.28, 'A' => 0.09, 'S' => 0.09, 'E' => 0.11, 'C' => 0.16],
    'S1|Ilmu Komputer' => ['R' => 0.17, 'I' => 0.29, 'A' => 0.09, 'S' => 0.10, 'E' => 0.13, 'C' => 0.22],
    'S1|Sistem Informasi' => ['R' => 0.12, 'I' => 0.22, 'A' => 0.10, 'S' => 0.15, 'E' => 0.19, 'C' => 0.22],
    'S1|Teknik Sipil' => ['R' => 0.28, 'I' => 0.22, 'A' => 0.06, 'S' => 0.10, 'E' => 0.15, 'C' => 0.19],
    'S1|Teknik Lingkungan' => ['R' => 0.23, 'I' => 0.25, 'A' => 0.09, 'S' => 0.18, 'E' => 0.11, 'C' => 0.14],
    'S1|Teknik Mesin' => ['R' => 0.29, 'I' => 0.26, 'A' => 0.05, 'S' => 0.08, 'E' => 0.12, 'C' => 0.20],
    'S1|Teknik Industri' => ['R' => 0.18, 'I' => 0.22, 'A' => 0.07, 'S' => 0.14, 'E' => 0.22, 'C' => 0.17],
    'S1|Teknik Elektro' => ['R' => 0.26, 'I' => 0.27, 'A' => 0.06, 'S' => 0.09, 'E' => 0.14, 'C' => 0.18],
    'S1|Teknik Komputer' => ['R' => 0.20, 'I' => 0.27, 'A' => 0.08, 'S' => 0.10, 'E' => 0.12, 'C' => 0.23],
    'S1|Teknik Kimia' => ['R' => 0.24, 'I' => 0.30, 'A' => 0.05, 'S' => 0.09, 'E' => 0.12, 'C' => 0.20],
    'S1|Teknik Metalurgi dan Material' => ['R' => 0.30, 'I' => 0.28, 'A' => 0.06, 'S' => 0.08, 'E' => 0.11, 'C' => 0.17],
    'S1|Teknik Bioproses' => ['R' => 0.25, 'I' => 0.26, 'A' => 0.06, 'S' => 0.10, 'E' => 0.13, 'C' => 0.20],
    'S1|Teknik Biomedik' => ['R' => 0.22, 'I' => 0.29, 'A' => 0.08, 'S' => 0.14, 'E' => 0.10, 'C' => 0.17],
    'S1|Arsitektur' => ['R' => 0.18, 'I' => 0.19, 'A' => 0.27, 'S' => 0.12, 'E' => 0.12, 'C' => 0.12],
    'S1|Arsitektur Interior' => ['R' => 0.15, 'I' => 0.18, 'A' => 0.31, 'S' => 0.15, 'E' => 0.12, 'C' => 0.09],
    'S1|Pendidikan Dokter' => ['R' => 0.13, 'I' => 0.24, 'A' => 0.07, 'S' => 0.23, 'E' => 0.13, 'C' => 0.20],
    'S1|Pendidikan Dokter Gigi' => ['R' => 0.15, 'I' => 0.23, 'A' => 0.07, 'S' => 0.24, 'E' => 0.12, 'C' => 0.19],
    'S1|Ilmu Keperawatan' => ['R' => 0.12, 'I' => 0.20, 'A' => 0.09, 'S' => 0.26, 'E' => 0.13, 'C' => 0.20],
    'S1|Kesehatan Masyarakat' => ['R' => 0.08, 'I' => 0.20, 'A' => 0.08, 'S' => 0.27, 'E' => 0.17, 'C' => 0.20],
    'S1|Gizi' => ['R' => 0.10, 'I' => 0.25, 'A' => 0.10, 'S' => 0.26, 'E' => 0.10, 'C' => 0.19],
    'S1|Kesehatan Lingkungan' => ['R' => 0.17, 'I' => 0.24, 'A' => 0.07, 'S' => 0.22, 'E' => 0.10, 'C' => 0.20],
    'S1|Keselamatan dan Kesehatan Kerja' => ['R' => 0.19, 'I' => 0.21, 'A' => 0.06, 'S' => 0.18, 'E' => 0.13, 'C' => 0.23],
    'S1|Farmasi' => ['R' => 0.14, 'I' => 0.27, 'A' => 0.08, 'S' => 0.18, 'E' => 0.10, 'C' => 0.23],
    'S1|Ilmu Politik' => ['R' => 0.06, 'I' => 0.21, 'A' => 0.09, 'S' => 0.21, 'E' => 0.27, 'C' => 0.16],
    'S1|Ilmu Administrasi Negara' => ['R' => 0.07, 'I' => 0.18, 'A' => 0.08, 'S' => 0.20, 'E' => 0.24, 'C' => 0.23],
    'S1|Ilmu Administrasi Niaga' => ['R' => 0.08, 'I' => 0.17, 'A' => 0.09, 'S' => 0.18, 'E' => 0.27, 'C' => 0.21],
    'S1|Ilmu Administrasi Fiskal' => ['R' => 0.07, 'I' => 0.17, 'A' => 0.07, 'S' => 0.20, 'E' => 0.23, 'C' => 0.26],
    'S1|Ilmu Hubungan Internasional' => ['R' => 0.06, 'I' => 0.19, 'A' => 0.11, 'S' => 0.21, 'E' => 0.26, 'C' => 0.17],
    'S1|Ilmu Komunikasi' => ['R' => 0.06, 'I' => 0.15, 'A' => 0.24, 'S' => 0.26, 'E' => 0.19, 'C' => 0.10],
    'S1|Sosiologi' => ['R' => 0.06, 'I' => 0.27, 'A' => 0.11, 'S' => 0.29, 'E' => 0.13, 'C' => 0.14],
    'S1|Antropologi Sosial' => ['R' => 0.08, 'I' => 0.24, 'A' => 0.15, 'S' => 0.25, 'E' => 0.14, 'C' => 0.14],
    'S1|Ilmu Kesejahteraan Sosial' => ['R' => 0.06, 'I' => 0.24, 'A' => 0.12, 'S' => 0.31, 'E' => 0.15, 'C' => 0.12],
    'S1|Kriminologi' => ['R' => 0.08, 'I' => 0.29, 'A' => 0.10, 'S' => 0.24, 'E' => 0.15, 'C' => 0.14],
    'S1|Ilmu Ekonomi' => ['R' => 0.08, 'I' => 0.25, 'A' => 0.08, 'S' => 0.15, 'E' => 0.22, 'C' => 0.22],
    'S1|Manajemen' => ['R' => 0.08, 'I' => 0.16, 'A' => 0.09, 'S' => 0.19, 'E' => 0.26, 'C' => 0.22],
    'S1|Akuntansi' => ['R' => 0.08, 'I' => 0.19, 'A' => 0.07, 'S' => 0.14, 'E' => 0.23, 'C' => 0.29],
    'S1|Ilmu Ekonomi Islam' => ['R' => 0.07, 'I' => 0.18, 'A' => 0.10, 'S' => 0.19, 'E' => 0.24, 'C' => 0.22],
    'S1|Ilmu Hukum' => ['R' => 0.06, 'I' => 0.21, 'A' => 0.08, 'S' => 0.18, 'E' => 0.28, 'C' => 0.19],
    'S1|Psikologi' => ['R' => 0.06, 'I' => 0.25, 'A' => 0.11, 'S' => 0.25, 'E' => 0.16, 'C' => 0.17],
    'S1|Arkeologi' => ['R' => 0.08, 'I' => 0.27, 'A' => 0.25, 'S' => 0.17, 'E' => 0.10, 'C' => 0.13],
    'S1|Ilmu Filsafat' => ['R' => 0.06, 'I' => 0.30, 'A' => 0.22, 'S' => 0.18, 'E' => 0.11, 'C' => 0.13],
    'S1|Ilmu Sejarah' => ['R' => 0.07, 'I' => 0.28, 'A' => 0.24, 'S' => 0.18, 'E' => 0.10, 'C' => 0.13],
    'S1|Ilmu Perpustakaan' => ['R' => 0.09, 'I' => 0.21, 'A' => 0.09, 'S' => 0.18, 'E' => 0.12, 'C' => 0.31],
    'S1|Sastra Arab' => ['R' => 0.05, 'I' => 0.20, 'A' => 0.28, 'S' => 0.22, 'E' => 0.15, 'C' => 0.10],
    'S1|Sastra Indonesia' => ['R' => 0.05, 'I' => 0.19, 'A' => 0.28, 'S' => 0.25, 'E' => 0.14, 'C' => 0.09],
    'S1|Sastra Jawa' => ['R' => 0.05, 'I' => 0.18, 'A' => 0.30, 'S' => 0.24, 'E' => 0.14, 'C' => 0.09],
    'S1|Sastra Cina' => ['R' => 0.05, 'I' => 0.21, 'A' => 0.25, 'S' => 0.20, 'E' => 0.18, 'C' => 0.11],
    'S1|Sastra Jepang' => ['R' => 0.05, 'I' => 0.21, 'A' => 0.29, 'S' => 0.19, 'E' => 0.16, 'C' => 0.10],
    'S1|Sastra Inggris' => ['R' => 0.05, 'I' => 0.18, 'A' => 0.27, 'S' => 0.24, 'E' => 0.17, 'C' => 0.09],
    'S1|Sastra Perancis' => ['R' => 0.05, 'I' => 0.18, 'A' => 0.28, 'S' => 0.24, 'E' => 0.16, 'C' => 0.09],
    'S1|Sastra Jerman' => ['R' => 0.05, 'I' => 0.20, 'A' => 0.24, 'S' => 0.18, 'E' => 0.17, 'C' => 0.16],
    'S1|Sastra Rusia' => ['R' => 0.05, 'I' => 0.21, 'A' => 0.28, 'S' => 0.20, 'E' => 0.15, 'C' => 0.11],
    'S1|Sastra Belanda' => ['R' => 0.05, 'I' => 0.20, 'A' => 0.26, 'S' => 0.20, 'E' => 0.16, 'C' => 0.13],
    'S1|Bahasa dan Kebudayaan Korea' => ['R' => 0.05, 'I' => 0.19, 'A' => 0.27, 'S' => 0.22, 'E' => 0.18, 'C' => 0.09],
    'S1|Aktuaria' => ['R' => 0.10, 'I' => 0.30, 'A' => 0.07, 'S' => 0.09, 'E' => 0.16, 'C' => 0.28],
    'S1|Astronomi' => ['R' => 0.20, 'I' => 0.36, 'A' => 0.06, 'S' => 0.07, 'E' => 0.09, 'C' => 0.22],
    'S1|Mikrobiologi' => ['R' => 0.13, 'I' => 0.34, 'A' => 0.09, 'S' => 0.16, 'E' => 0.09, 'C' => 0.19],
    'S1|Meteorologi' => ['R' => 0.25, 'I' => 0.32, 'A' => 0.07, 'S' => 0.09, 'E' => 0.11, 'C' => 0.16],
    'S1|Oseanografi' => ['R' => 0.28, 'I' => 0.29, 'A' => 0.08, 'S' => 0.10, 'E' => 0.11, 'C' => 0.14],
    'S1|Teknik Geodesi dan Geomatika' => ['R' => 0.26, 'I' => 0.27, 'A' => 0.08, 'S' => 0.09, 'E' => 0.12, 'C' => 0.18],
    'S1|Teknik Geologi' => ['R' => 0.30, 'I' => 0.29, 'A' => 0.07, 'S' => 0.08, 'E' => 0.10, 'C' => 0.16],
    'S1|Teknik Geofisika' => ['R' => 0.28, 'I' => 0.31, 'A' => 0.07, 'S' => 0.08, 'E' => 0.10, 'C' => 0.16],
    'S1|Informatika' => ['R' => 0.15, 'I' => 0.30, 'A' => 0.10, 'S' => 0.11, 'E' => 0.14, 'C' => 0.20],
    'S1|Sistem dan Teknologi Informasi' => ['R' => 0.13, 'I' => 0.22, 'A' => 0.10, 'S' => 0.15, 'E' => 0.18, 'C' => 0.22],
    'S1|Teknik Telekomunikasi' => ['R' => 0.23, 'I' => 0.23, 'A' => 0.10, 'S' => 0.12, 'E' => 0.14, 'C' => 0.18],
    'S1|Teknik Tenaga Listrik' => ['R' => 0.24, 'I' => 0.25, 'A' => 0.07, 'S' => 0.10, 'E' => 0.14, 'C' => 0.20],
    'S1|Teknik Biomedis' => ['R' => 0.20, 'I' => 0.28, 'A' => 0.09, 'S' => 0.16, 'E' => 0.10, 'C' => 0.17],
    'S1|Teknik Dirgantara' => ['R' => 0.30, 'I' => 0.27, 'A' => 0.05, 'S' => 0.07, 'E' => 0.12, 'C' => 0.19],
    'S1|Teknik Material' => ['R' => 0.27, 'I' => 0.30, 'A' => 0.07, 'S' => 0.08, 'E' => 0.12, 'C' => 0.16],
    'S1|Teknik Metalurgi' => ['R' => 0.31, 'I' => 0.27, 'A' => 0.05, 'S' => 0.08, 'E' => 0.12, 'C' => 0.17],
    'S1|Teknik Perminyakan' => ['R' => 0.29, 'I' => 0.27, 'A' => 0.05, 'S' => 0.08, 'E' => 0.13, 'C' => 0.18],
    'S1|Teknik Pertambangan' => ['R' => 0.29, 'I' => 0.27, 'A' => 0.07, 'S' => 0.08, 'E' => 0.11, 'C' => 0.18],
    'S1|Teknik Fisika' => ['R' => 0.24, 'I' => 0.29, 'A' => 0.07, 'S' => 0.09, 'E' => 0.12, 'C' => 0.19],
    'S1|Teknik Pangan' => ['R' => 0.21, 'I' => 0.26, 'A' => 0.08, 'S' => 0.12, 'E' => 0.12, 'C' => 0.21],
    'S1|Teknik dan Pengelolaan Sumber Daya Air' => ['R' => 0.27, 'I' => 0.23, 'A' => 0.07, 'S' => 0.11, 'E' => 0.16, 'C' => 0.16],
    'S1|Teknik Kelautan' => ['R' => 0.28, 'I' => 0.24, 'A' => 0.08, 'S' => 0.11, 'E' => 0.13, 'C' => 0.16],
    'S1|Rekayasa Infrastruktur Lingkungan' => ['R' => 0.21, 'I' => 0.26, 'A' => 0.10, 'S' => 0.18, 'E' => 0.12, 'C' => 0.13],
    'S1|Rekayasa Hayati' => ['R' => 0.17, 'I' => 0.28, 'A' => 0.11, 'S' => 0.17, 'E' => 0.09, 'C' => 0.18],
    'S1|Rekayasa Pertanian' => ['R' => 0.27, 'I' => 0.25, 'A' => 0.09, 'S' => 0.12, 'E' => 0.11, 'C' => 0.16],
    'S1|Rekayasa Kehutanan' => ['R' => 0.30, 'I' => 0.24, 'A' => 0.08, 'S' => 0.13, 'E' => 0.11, 'C' => 0.14],
    'S1|Teknologi Pascapanen' => ['R' => 0.21, 'I' => 0.25, 'A' => 0.08, 'S' => 0.12, 'E' => 0.12, 'C' => 0.22],
    'S1|Sains dan Teknologi Farmasi' => ['R' => 0.16, 'I' => 0.28, 'A' => 0.08, 'S' => 0.15, 'E' => 0.10, 'C' => 0.23],
    'S1|Farmasi Klinik dan Komunitas' => ['R' => 0.12, 'I' => 0.24, 'A' => 0.09, 'S' => 0.23, 'E' => 0.12, 'C' => 0.20],
    'S1|Perencanaan Wilayah dan Kota' => ['R' => 0.15, 'I' => 0.19, 'A' => 0.14, 'S' => 0.15, 'E' => 0.17, 'C' => 0.20],
    'S1|Desain Interior' => ['R' => 0.14, 'I' => 0.14, 'A' => 0.31, 'S' => 0.16, 'E' => 0.17, 'C' => 0.08],
    'S1|Desain Komunikasi Visual' => ['R' => 0.13, 'I' => 0.13, 'A' => 0.30, 'S' => 0.17, 'E' => 0.18, 'C' => 0.09],
    'S1|Desain Produk' => ['R' => 0.16, 'I' => 0.15, 'A' => 0.29, 'S' => 0.15, 'E' => 0.17, 'C' => 0.08],
    'S1|Kriya' => ['R' => 0.12, 'I' => 0.15, 'A' => 0.33, 'S' => 0.20, 'E' => 0.12, 'C' => 0.08],
    'S1|Seni Rupa' => ['R' => 0.13, 'I' => 0.14, 'A' => 0.32, 'S' => 0.21, 'E' => 0.12, 'C' => 0.08],
    'S1|Kewirausahaan' => ['R' => 0.07, 'I' => 0.14, 'A' => 0.12, 'S' => 0.17, 'E' => 0.29, 'C' => 0.21],
    'S1|Statistika' => ['R' => 0.12, 'I' => 0.32, 'A' => 0.07, 'S' => 0.10, 'E' => 0.13, 'C' => 0.26],
    'S1|Ilmu Aktuaria' => ['R' => 0.09, 'I' => 0.31, 'A' => 0.07, 'S' => 0.09, 'E' => 0.16, 'C' => 0.28],
    'S1|Geografi Lingkungan' => ['R' => 0.24, 'I' => 0.27, 'A' => 0.10, 'S' => 0.12, 'E' => 0.12, 'C' => 0.15],
    'S1|Teknologi Informasi' => ['R' => 0.14, 'I' => 0.25, 'A' => 0.11, 'S' => 0.13, 'E' => 0.16, 'C' => 0.21],
    'S1|Elektronika dan Instrumentasi' => ['R' => 0.26, 'I' => 0.25, 'A' => 0.06, 'S' => 0.09, 'E' => 0.12, 'C' => 0.22],
    'S1|Sistem Informasi Geografis' => ['R' => 0.22, 'I' => 0.27, 'A' => 0.07, 'S' => 0.09, 'E' => 0.13, 'C' => 0.22],
    'S1|Teknik Infrastruktur Lingkungan' => ['R' => 0.24, 'I' => 0.23, 'A' => 0.08, 'S' => 0.17, 'E' => 0.13, 'C' => 0.15],
    'S1|Teknik Sumber Daya Air' => ['R' => 0.25, 'I' => 0.24, 'A' => 0.06, 'S' => 0.12, 'E' => 0.16, 'C' => 0.17],
    'S1|Teknik Geodesi' => ['R' => 0.24, 'I' => 0.28, 'A' => 0.09, 'S' => 0.09, 'E' => 0.11, 'C' => 0.19],
    'S1|Teknik Nuklir' => ['R' => 0.25, 'I' => 0.34, 'A' => 0.05, 'S' => 0.08, 'E' => 0.10, 'C' => 0.18],
    'S1|Teknik Pertanian' => ['R' => 0.25, 'I' => 0.26, 'A' => 0.10, 'S' => 0.12, 'E' => 0.12, 'C' => 0.15],
    'S1|Agronomi' => ['R' => 0.30, 'I' => 0.25, 'A' => 0.09, 'S' => 0.12, 'E' => 0.09, 'C' => 0.15],
    'S1|Ilmu Tanah' => ['R' => 0.27, 'I' => 0.26, 'A' => 0.07, 'S' => 0.12, 'E' => 0.10, 'C' => 0.18],
    'S1|Proteksi Tanaman' => ['R' => 0.29, 'I' => 0.24, 'A' => 0.09, 'S' => 0.12, 'E' => 0.10, 'C' => 0.16],
    'S1|Mikrobiologi Pertanian' => ['R' => 0.18, 'I' => 0.31, 'A' => 0.10, 'S' => 0.15, 'E' => 0.08, 'C' => 0.18],
    'S1|Ekonomi Pertanian dan Agribisnis' => ['R' => 0.16, 'I' => 0.16, 'A' => 0.08, 'S' => 0.15, 'E' => 0.24, 'C' => 0.21],
    'S1|Ilmu dan Industri Peternakan' => ['R' => 0.24, 'I' => 0.22, 'A' => 0.07, 'S' => 0.17, 'E' => 0.13, 'C' => 0.17],
    'S1|Akuakultur' => ['R' => 0.27, 'I' => 0.25, 'A' => 0.09, 'S' => 0.14, 'E' => 0.10, 'C' => 0.15],
    'S1|Manajemen Sumberdaya Akuatik' => ['R' => 0.22, 'I' => 0.23, 'A' => 0.08, 'S' => 0.14, 'E' => 0.14, 'C' => 0.19],
    'S1|Teknologi Hasil Perikanan' => ['R' => 0.25, 'I' => 0.26, 'A' => 0.10, 'S' => 0.15, 'E' => 0.10, 'C' => 0.14],
    'S1|Kehutanan' => ['R' => 0.31, 'I' => 0.23, 'A' => 0.07, 'S' => 0.13, 'E' => 0.11, 'C' => 0.15],
    'S1|Teknologi Pangan dan Hasil Pertanian' => ['R' => 0.21, 'I' => 0.27, 'A' => 0.08, 'S' => 0.11, 'E' => 0.11, 'C' => 0.22],
    'S1|Teknologi Industri Pertanian' => ['R' => 0.20, 'I' => 0.21, 'A' => 0.08, 'S' => 0.12, 'E' => 0.20, 'C' => 0.19],
    'S1|Pengembangan Produk Agroindustri' => ['R' => 0.18, 'I' => 0.21, 'A' => 0.10, 'S' => 0.13, 'E' => 0.20, 'C' => 0.18],
    'S1|Kedokteran' => ['R' => 0.13, 'I' => 0.23, 'A' => 0.07, 'S' => 0.24, 'E' => 0.13, 'C' => 0.20],
    'S1|Kedokteran Gigi' => ['R' => 0.17, 'I' => 0.22, 'A' => 0.07, 'S' => 0.25, 'E' => 0.11, 'C' => 0.18],
    'S1|Higiene Gigi' => ['R' => 0.13, 'I' => 0.20, 'A' => 0.08, 'S' => 0.29, 'E' => 0.12, 'C' => 0.18],
    'S1|Kedokteran Hewan' => ['R' => 0.22, 'I' => 0.22, 'A' => 0.08, 'S' => 0.20, 'E' => 0.11, 'C' => 0.17],
    'S1|Manajemen Informasi Kesehatan' => ['R' => 0.08, 'I' => 0.19, 'A' => 0.08, 'S' => 0.25, 'E' => 0.19, 'C' => 0.21],
    'D4|Akuntansi Sektor Publik' => ['R' => 0.09, 'I' => 0.18, 'A' => 0.07, 'S' => 0.14, 'E' => 0.22, 'C' => 0.30],
    'D4|Perbankan' => ['R' => 0.09, 'I' => 0.19, 'A' => 0.07, 'S' => 0.14, 'E' => 0.23, 'C' => 0.28],
    'D4|Manajemen dan Penilaian Properti' => ['R' => 0.09, 'I' => 0.16, 'A' => 0.09, 'S' => 0.19, 'E' => 0.25, 'C' => 0.22],
    'D4|Pembangunan Ekonomi Kewilayahan' => ['R' => 0.08, 'I' => 0.22, 'A' => 0.07, 'S' => 0.15, 'E' => 0.23, 'C' => 0.25],
    'S1|Politik dan Pemerintahan' => ['R' => 0.06, 'I' => 0.19, 'A' => 0.09, 'S' => 0.22, 'E' => 0.27, 'C' => 0.17],
    'S1|Manajemen dan Kebijakan Publik' => ['R' => 0.07, 'I' => 0.16, 'A' => 0.07, 'S' => 0.19, 'E' => 0.23, 'C' => 0.28],
    'S1|Pembangunan Sosial dan Kesejahteraan' => ['R' => 0.06, 'I' => 0.26, 'A' => 0.10, 'S' => 0.30, 'E' => 0.16, 'C' => 0.12],
    'S1|Hukum' => ['R' => 0.06, 'I' => 0.19, 'A' => 0.09, 'S' => 0.20, 'E' => 0.27, 'C' => 0.19],
    'S1|Sejarah' => ['R' => 0.07, 'I' => 0.25, 'A' => 0.26, 'S' => 0.19, 'E' => 0.10, 'C' => 0.13],
    'S1|Filsafat' => ['R' => 0.06, 'I' => 0.28, 'A' => 0.24, 'S' => 0.19, 'E' => 0.11, 'C' => 0.12],
    'S1|Bahasa dan Sastra Indonesia' => ['R' => 0.05, 'I' => 0.18, 'A' => 0.29, 'S' => 0.24, 'E' => 0.14, 'C' => 0.10],
    'S1|Bahasa dan Sastra Jawa' => ['R' => 0.05, 'I' => 0.17, 'A' => 0.31, 'S' => 0.24, 'E' => 0.13, 'C' => 0.10],
    'S1|Bahasa dan Kebudayaan Jepang' => ['R' => 0.05, 'I' => 0.20, 'A' => 0.28, 'S' => 0.21, 'E' => 0.17, 'C' => 0.09],
    'S1|Bahasa dan Sastra Prancis' => ['R' => 0.05, 'I' => 0.18, 'A' => 0.29, 'S' => 0.24, 'E' => 0.15, 'C' => 0.09],
    'S1|Pariwisata' => ['R' => 0.08, 'I' => 0.13, 'A' => 0.14, 'S' => 0.21, 'E' => 0.28, 'C' => 0.16],
    'D4|Bisnis Perjalanan Wisata' => ['R' => 0.09, 'I' => 0.14, 'A' => 0.12, 'S' => 0.17, 'E' => 0.27, 'C' => 0.21],
    'S1|Statistika dan Sains Data' => ['R' => 0.13, 'I' => 0.31, 'A' => 0.07, 'S' => 0.10, 'E' => 0.13, 'C' => 0.26],
    'S1|Biokimia' => ['R' => 0.14, 'I' => 0.33, 'A' => 0.11, 'S' => 0.15, 'E' => 0.10, 'C' => 0.17],
    'S1|Meteorologi Terapan' => ['R' => 0.24, 'I' => 0.31, 'A' => 0.07, 'S' => 0.10, 'E' => 0.12, 'C' => 0.16],
    'S1|Kecerdasan Buatan' => ['R' => 0.12, 'I' => 0.34, 'A' => 0.08, 'S' => 0.09, 'E' => 0.14, 'C' => 0.23],
    'S1|Bioinformatika' => ['R' => 0.14, 'I' => 0.31, 'A' => 0.09, 'S' => 0.11, 'E' => 0.12, 'C' => 0.23],
    'S1|Agronomi dan Hortikultura' => ['R' => 0.27, 'I' => 0.27, 'A' => 0.09, 'S' => 0.12, 'E' => 0.10, 'C' => 0.15],
    'S1|Manajemen Sumberdaya Lahan' => ['R' => 0.24, 'I' => 0.24, 'A' => 0.07, 'S' => 0.13, 'E' => 0.13, 'C' => 0.19],
    'S1|Smart Agriculture' => ['R' => 0.24, 'I' => 0.25, 'A' => 0.10, 'S' => 0.12, 'E' => 0.11, 'C' => 0.18],
    'S1|Agribisnis' => ['R' => 0.15, 'I' => 0.16, 'A' => 0.08, 'S' => 0.16, 'E' => 0.23, 'C' => 0.22],
    'S1|Teknologi Produksi Ternak' => ['R' => 0.27, 'I' => 0.22, 'A' => 0.07, 'S' => 0.18, 'E' => 0.11, 'C' => 0.15],
    'S1|Nutrisi dan Teknologi Pakan' => ['R' => 0.24, 'I' => 0.25, 'A' => 0.08, 'S' => 0.17, 'E' => 0.11, 'C' => 0.15],
    'S1|Teknologi Hasil Ternak' => ['R' => 0.23, 'I' => 0.23, 'A' => 0.09, 'S' => 0.17, 'E' => 0.14, 'C' => 0.14],
    'S1|Manajemen Hutan' => ['R' => 0.27, 'I' => 0.23, 'A' => 0.08, 'S' => 0.13, 'E' => 0.13, 'C' => 0.16],
    'S1|Teknologi Hasil Hutan' => ['R' => 0.28, 'I' => 0.25, 'A' => 0.09, 'S' => 0.12, 'E' => 0.12, 'C' => 0.14],
    'S1|Konservasi Sumberdaya Hutan dan Ekowisata' => ['R' => 0.27, 'I' => 0.24, 'A' => 0.10, 'S' => 0.16, 'E' => 0.12, 'C' => 0.11],
    'S1|Silvikultur' => ['R' => 0.32, 'I' => 0.23, 'A' => 0.07, 'S' => 0.12, 'E' => 0.10, 'C' => 0.16],
    'S1|Teknologi dan Manajemen Perikanan Budidaya' => ['R' => 0.26, 'I' => 0.24, 'A' => 0.09, 'S' => 0.16, 'E' => 0.12, 'C' => 0.13],
    'S1|Manajemen Sumberdaya Perairan' => ['R' => 0.23, 'I' => 0.24, 'A' => 0.08, 'S' => 0.14, 'E' => 0.13, 'C' => 0.18],
    'S1|Teknologi Hasil Perairan' => ['R' => 0.28, 'I' => 0.24, 'A' => 0.09, 'S' => 0.14, 'E' => 0.11, 'C' => 0.14],
    'S1|Teknologi dan Manajemen Perikanan Tangkap' => ['R' => 0.28, 'I' => 0.23, 'A' => 0.09, 'S' => 0.15, 'E' => 0.13, 'C' => 0.12],
    'S1|Ilmu dan Teknologi Kelautan' => ['R' => 0.27, 'I' => 0.27, 'A' => 0.10, 'S' => 0.11, 'E' => 0.10, 'C' => 0.15],
    'S1|Teknik Pertanian dan Biosistem' => ['R' => 0.24, 'I' => 0.27, 'A' => 0.10, 'S' => 0.13, 'E' => 0.11, 'C' => 0.15],
    'S1|Teknik Industri Pertanian' => ['R' => 0.21, 'I' => 0.19, 'A' => 0.07, 'S' => 0.12, 'E' => 0.21, 'C' => 0.20],
    'S1|Teknik Sipil dan Lingkungan' => ['R' => 0.24, 'I' => 0.23, 'A' => 0.08, 'S' => 0.13, 'E' => 0.15, 'C' => 0.17],
    'S1|Teknologi Pangan' => ['R' => 0.19, 'I' => 0.27, 'A' => 0.09, 'S' => 0.13, 'E' => 0.12, 'C' => 0.20],
    'S1|Ekonomi Pembangunan' => ['R' => 0.07, 'I' => 0.27, 'A' => 0.08, 'S' => 0.15, 'E' => 0.23, 'C' => 0.20],
    'S1|Ekonomi Sumberdaya dan Lingkungan' => ['R' => 0.08, 'I' => 0.24, 'A' => 0.09, 'S' => 0.16, 'E' => 0.21, 'C' => 0.22],
    'S1|Ilmu Ekonomi Syariah' => ['R' => 0.07, 'I' => 0.16, 'A' => 0.11, 'S' => 0.18, 'E' => 0.25, 'C' => 0.23],
    'S1|Bisnis' => ['R' => 0.08, 'I' => 0.15, 'A' => 0.11, 'S' => 0.18, 'E' => 0.28, 'C' => 0.20],
    'S1|Ilmu Keluarga dan Konsumen' => ['R' => 0.06, 'I' => 0.23, 'A' => 0.11, 'S' => 0.31, 'E' => 0.14, 'C' => 0.15],
    'S1|Komunikasi dan Pengembangan Masyarakat' => ['R' => 0.06, 'I' => 0.14, 'A' => 0.22, 'S' => 0.28, 'E' => 0.20, 'C' => 0.10],
    'S1|Ilmu Gizi' => ['R' => 0.09, 'I' => 0.23, 'A' => 0.10, 'S' => 0.28, 'E' => 0.11, 'C' => 0.19],
    'S1|Sains Biomedis' => ['R' => 0.18, 'I' => 0.31, 'A' => 0.10, 'S' => 0.15, 'E' => 0.09, 'C' => 0.17],
    'S1|Arsitektur Lanskap' => ['R' => 0.21, 'I' => 0.18, 'A' => 0.25, 'S' => 0.12, 'E' => 0.12, 'C' => 0.12],
    'D4|Teknologi Rekayasa Perangkat Lunak' => ['R' => 0.18, 'I' => 0.27, 'A' => 0.09, 'S' => 0.10, 'E' => 0.14, 'C' => 0.22],
    'D4|Teknologi Rekayasa Komputer' => ['R' => 0.21, 'I' => 0.25, 'A' => 0.08, 'S' => 0.11, 'E' => 0.13, 'C' => 0.22],
    'D4|Teknologi dan Manajemen Produksi Perkebunan' => ['R' => 0.25, 'I' => 0.23, 'A' => 0.09, 'S' => 0.12, 'E' => 0.14, 'C' => 0.17],
    'D4|Teknologi dan Manajemen Pembenihan Ikan' => ['R' => 0.24, 'I' => 0.21, 'A' => 0.09, 'S' => 0.15, 'E' => 0.14, 'C' => 0.17],
    'D4|Teknologi Produksi dan Manajemen Peternakan' => ['R' => 0.25, 'I' => 0.20, 'A' => 0.07, 'S' => 0.17, 'E' => 0.15, 'C' => 0.16],
    'D4|Manajemen Industri Jasa Makanan dan Gizi' => ['R' => 0.18, 'I' => 0.22, 'A' => 0.07, 'S' => 0.15, 'E' => 0.16, 'C' => 0.22],
    'D4|Ekowisata' => ['R' => 0.29, 'I' => 0.24, 'A' => 0.08, 'S' => 0.12, 'E' => 0.12, 'C' => 0.15],
    'S1|Sains Data' => ['R' => 0.11, 'I' => 0.33, 'A' => 0.08, 'S' => 0.10, 'E' => 0.15, 'C' => 0.23],
    'S1|Sains Analitik dan Instrumentasi Kimia' => ['R' => 0.21, 'I' => 0.30, 'A' => 0.07, 'S' => 0.10, 'E' => 0.10, 'C' => 0.22],
    'S1|Bioteknologi' => ['R' => 0.17, 'I' => 0.31, 'A' => 0.10, 'S' => 0.16, 'E' => 0.09, 'C' => 0.17],
    'S1|Sains Aktuaria' => ['R' => 0.10, 'I' => 0.33, 'A' => 0.07, 'S' => 0.09, 'E' => 0.14, 'C' => 0.27],
    'S1|Rekayasa Keselamatan Proses' => ['R' => 0.20, 'I' => 0.21, 'A' => 0.07, 'S' => 0.15, 'E' => 0.21, 'C' => 0.16],
    'S1|Teknik Geomatika' => ['R' => 0.23, 'I' => 0.25, 'A' => 0.10, 'S' => 0.10, 'E' => 0.14, 'C' => 0.18],
    'S1|Teknik Perkapalan' => ['R' => 0.30, 'I' => 0.24, 'A' => 0.07, 'S' => 0.09, 'E' => 0.13, 'C' => 0.17],
    'S1|Teknik Sistem Perkapalan' => ['R' => 0.27, 'I' => 0.25, 'A' => 0.08, 'S' => 0.11, 'E' => 0.13, 'C' => 0.16],
    'S1|Rekayasa Kecerdasan Artifisial' => ['R' => 0.13, 'I' => 0.32, 'A' => 0.09, 'S' => 0.09, 'E' => 0.14, 'C' => 0.23],
    'S1|Teknologi Informasi dan Komunikasi' => ['R' => 0.15, 'I' => 0.24, 'A' => 0.12, 'S' => 0.13, 'E' => 0.14, 'C' => 0.22],
    'S1|Desain Produk Industri' => ['R' => 0.13, 'I' => 0.15, 'A' => 0.27, 'S' => 0.16, 'E' => 0.18, 'C' => 0.11],
    'D4|Teknologi Rekayasa Otomasi' => ['R' => 0.27, 'I' => 0.27, 'A' => 0.06, 'S' => 0.09, 'E' => 0.12, 'C' => 0.19],
    'D4|Teknologi Rekayasa Instrumentasi' => ['R' => 0.23, 'I' => 0.28, 'A' => 0.07, 'S' => 0.10, 'E' => 0.13, 'C' => 0.19],
    'D4|Teknik Sipil' => ['R' => 0.28, 'I' => 0.22, 'A' => 0.06, 'S' => 0.10, 'E' => 0.16, 'C' => 0.18],
    'D4|Teknologi Rekayasa Konstruksi Bangunan Air' => ['R' => 0.29, 'I' => 0.21, 'A' => 0.06, 'S' => 0.12, 'E' => 0.15, 'C' => 0.17],
    'D4|Teknologi Rekayasa Konversi Energi' => ['R' => 0.29, 'I' => 0.26, 'A' => 0.05, 'S' => 0.08, 'E' => 0.14, 'C' => 0.18],
    'D4|Teknologi Rekayasa Manufaktur' => ['R' => 0.30, 'I' => 0.25, 'A' => 0.05, 'S' => 0.08, 'E' => 0.13, 'C' => 0.19],
    'D4|Statistika Bisnis' => ['R' => 0.13, 'I' => 0.27, 'A' => 0.07, 'S' => 0.10, 'E' => 0.15, 'C' => 0.28],
];

        // =========================================================
        // 5. DATABASE PROGRAM STUDI
        // =========================================================
        //
        // Format:
        //
        // [
        //     'name' => 'Nama Prodi',
        //     'degree' => 'S1',
        //     'field' => 'kode_sub_ilmu',
        //     'school_major' => ['IPA'],
        // ]
        //
        // school_major:
        // IPA
        // IPS
        // BAHASA
        // SMK
        // BEBAS
        // IPA/IPS
        // IPA/SMK
        // SEMUA

        $programs = [

            // =====================================================
            // UNIVERSITAS INDONESIA
            // =====================================================

            'UI' => [

                // SAINS
                ['name' => 'Matematika', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],
                ['name' => 'Fisika', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Kimia', 'degree' => 'S1', 'field' => 'kimia', 'school_major' => ['IPA']],
                ['name' => 'Biologi', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Geofisika', 'degree' => 'S1', 'field' => 'kebumian', 'school_major' => ['IPA']],
                ['name' => 'Geografi', 'degree' => 'S1', 'field' => 'kebumian', 'school_major' => ['IPA', 'IPS']],

                // KOMPUTER
                ['name' => 'Ilmu Komputer', 'degree' => 'S1', 'field' => 'informatika', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Sistem Informasi', 'degree' => 'S1', 'field' => 'sistem_informasi', 'school_major' => ['IPA', 'IPS', 'SMK']],

                // TEKNIK
                ['name' => 'Teknik Sipil', 'degree' => 'S1', 'field' => 'sipil', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknik Lingkungan', 'degree' => 'S1', 'field' => 'lingkungan', 'school_major' => ['IPA']],
                ['name' => 'Teknik Mesin', 'degree' => 'S1', 'field' => 'mesin', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknik Industri', 'degree' => 'S1', 'field' => 'industri', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Teknik Elektro', 'degree' => 'S1', 'field' => 'elektro', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknik Komputer', 'degree' => 'S1', 'field' => 'informatika', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknik Kimia', 'degree' => 'S1', 'field' => 'kimia_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Metalurgi dan Material', 'degree' => 'S1', 'field' => 'material', 'school_major' => ['IPA']],
                ['name' => 'Teknik Bioproses', 'degree' => 'S1', 'field' => 'kimia_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Biomedik', 'degree' => 'S1', 'field' => 'biomedis', 'school_major' => ['IPA']],
                ['name' => 'Arsitektur', 'degree' => 'S1', 'field' => 'arsitektur_ilmu', 'school_major' => ['IPA']],
                ['name' => 'Arsitektur Interior', 'degree' => 'S1', 'field' => 'arsitektur_ilmu', 'school_major' => ['IPA', 'IPS', 'SMK']],

                // KESEHATAN
                ['name' => 'Pendidikan Dokter', 'degree' => 'S1', 'field' => 'kedokteran', 'school_major' => ['IPA']],
                ['name' => 'Pendidikan Dokter Gigi', 'degree' => 'S1', 'field' => 'kedokteran_gigi', 'school_major' => ['IPA']],
                ['name' => 'Ilmu Keperawatan', 'degree' => 'S1', 'field' => 'keperawatan', 'school_major' => ['IPA']],
                ['name' => 'Kesehatan Masyarakat', 'degree' => 'S1', 'field' => 'kesehatan_masyarakat', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Gizi', 'degree' => 'S1', 'field' => 'gizi', 'school_major' => ['IPA']],
                ['name' => 'Kesehatan Lingkungan', 'degree' => 'S1', 'field' => 'kesehatan_lingkungan', 'school_major' => ['IPA']],
                ['name' => 'Keselamatan dan Kesehatan Kerja', 'degree' => 'S1', 'field' => 'k3', 'school_major' => ['IPA']],

                // FARMASI
                ['name' => 'Farmasi', 'degree' => 'S1', 'field' => 'farmasi_ilmu', 'school_major' => ['IPA']],

                // SOSIAL
                ['name' => 'Ilmu Politik', 'degree' => 'S1', 'field' => 'politik', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Ilmu Administrasi Negara', 'degree' => 'S1', 'field' => 'administrasi', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Ilmu Administrasi Niaga', 'degree' => 'S1', 'field' => 'administrasi', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Ilmu Administrasi Fiskal', 'degree' => 'S1', 'field' => 'administrasi', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Ilmu Hubungan Internasional', 'degree' => 'S1', 'field' => 'hubungan_internasional', 'school_major' => ['IPS', 'IPA', 'BAHASA']],
                ['name' => 'Ilmu Komunikasi', 'degree' => 'S1', 'field' => 'komunikasi', 'school_major' => ['IPS', 'IPA', 'BAHASA']],
                ['name' => 'Sosiologi', 'degree' => 'S1', 'field' => 'sosiologi', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Antropologi Sosial', 'degree' => 'S1', 'field' => 'antropologi', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Ilmu Kesejahteraan Sosial', 'degree' => 'S1', 'field' => 'sosiologi', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Kriminologi', 'degree' => 'S1', 'field' => 'sosiologi', 'school_major' => ['IPS', 'IPA']],

                // EKONOMI
                ['name' => 'Ilmu Ekonomi', 'degree' => 'S1', 'field' => 'ekonomi_ilmu', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Manajemen', 'degree' => 'S1', 'field' => 'manajemen', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Akuntansi', 'degree' => 'S1', 'field' => 'akuntansi', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Ilmu Ekonomi Islam', 'degree' => 'S1', 'field' => 'ekonomi_syariah', 'school_major' => ['IPS', 'IPA']],

                // HUKUM
                ['name' => 'Ilmu Hukum', 'degree' => 'S1', 'field' => 'hukum_ilmu', 'school_major' => ['IPS', 'IPA', 'BAHASA']],

                // PSIKOLOGI
                ['name' => 'Psikologi', 'degree' => 'S1', 'field' => 'psikologi_ilmu', 'school_major' => ['IPA', 'IPS']],

                // HUMANIORA
                ['name' => 'Arkeologi', 'degree' => 'S1', 'field' => 'sejarah', 'school_major' => ['IPA', 'IPS', 'BAHASA']],
                ['name' => 'Ilmu Filsafat', 'degree' => 'S1', 'field' => 'filsafat', 'school_major' => ['IPA', 'IPS', 'BAHASA']],
                ['name' => 'Ilmu Sejarah', 'degree' => 'S1', 'field' => 'sejarah', 'school_major' => ['IPA', 'IPS', 'BAHASA']],
                ['name' => 'Ilmu Perpustakaan', 'degree' => 'S1', 'field' => 'perpustakaan', 'school_major' => ['IPA', 'IPS', 'BAHASA']],
                ['name' => 'Sastra Arab', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Indonesia', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Jawa', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Cina', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Jepang', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Inggris', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Perancis', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Jerman', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Rusia', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Belanda', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Bahasa dan Kebudayaan Korea', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
            ],


            // =====================================================
            // ITB
            // =====================================================

            'ITB' => [

                // SAINS
                ['name' => 'Matematika', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],
                ['name' => 'Aktuaria', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],
                ['name' => 'Fisika', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Astronomi', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Kimia', 'degree' => 'S1', 'field' => 'kimia', 'school_major' => ['IPA']],
                ['name' => 'Biologi', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Mikrobiologi', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Meteorologi', 'degree' => 'S1', 'field' => 'kebumian', 'school_major' => ['IPA']],
                ['name' => 'Oseanografi', 'degree' => 'S1', 'field' => 'kebumian', 'school_major' => ['IPA']],
                ['name' => 'Teknik Geodesi dan Geomatika', 'degree' => 'S1', 'field' => 'geodesi', 'school_major' => ['IPA']],
                ['name' => 'Teknik Geologi', 'degree' => 'S1', 'field' => 'geologi_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Geofisika', 'degree' => 'S1', 'field' => 'geologi_teknik', 'school_major' => ['IPA']],

                // KOMPUTER
                ['name' => 'Informatika', 'degree' => 'S1', 'field' => 'informatika', 'school_major' => ['IPA']],
                ['name' => 'Sistem dan Teknologi Informasi', 'degree' => 'S1', 'field' => 'sistem_informasi', 'school_major' => ['IPA']],

                // TEKNIK
                ['name' => 'Teknik Elektro', 'degree' => 'S1', 'field' => 'elektro', 'school_major' => ['IPA']],
                ['name' => 'Teknik Telekomunikasi', 'degree' => 'S1', 'field' => 'elektro', 'school_major' => ['IPA']],
                ['name' => 'Teknik Tenaga Listrik', 'degree' => 'S1', 'field' => 'elektro', 'school_major' => ['IPA']],
                ['name' => 'Teknik Biomedis', 'degree' => 'S1', 'field' => 'biomedis', 'school_major' => ['IPA']],
                ['name' => 'Teknik Mesin', 'degree' => 'S1', 'field' => 'mesin', 'school_major' => ['IPA']],
                ['name' => 'Teknik Dirgantara', 'degree' => 'S1', 'field' => 'dirgantara', 'school_major' => ['IPA']],
                ['name' => 'Teknik Material', 'degree' => 'S1', 'field' => 'material', 'school_major' => ['IPA']],
                ['name' => 'Teknik Metalurgi', 'degree' => 'S1', 'field' => 'material', 'school_major' => ['IPA']],
                ['name' => 'Teknik Perminyakan', 'degree' => 'S1', 'field' => 'energi', 'school_major' => ['IPA']],
                ['name' => 'Teknik Pertambangan', 'degree' => 'S1', 'field' => 'geologi_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Kimia', 'degree' => 'S1', 'field' => 'kimia_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Industri', 'degree' => 'S1', 'field' => 'industri', 'school_major' => ['IPA']],
                ['name' => 'Teknik Fisika', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Teknik Pangan', 'degree' => 'S1', 'field' => 'teknologi_pangan', 'school_major' => ['IPA']],
                ['name' => 'Teknik Sipil', 'degree' => 'S1', 'field' => 'sipil', 'school_major' => ['IPA']],
                ['name' => 'Teknik Lingkungan', 'degree' => 'S1', 'field' => 'lingkungan', 'school_major' => ['IPA']],
                ['name' => 'Teknik dan Pengelolaan Sumber Daya Air', 'degree' => 'S1', 'field' => 'sipil', 'school_major' => ['IPA']],
                ['name' => 'Teknik Kelautan', 'degree' => 'S1', 'field' => 'kelautan_teknik', 'school_major' => ['IPA']],
                ['name' => 'Rekayasa Infrastruktur Lingkungan', 'degree' => 'S1', 'field' => 'lingkungan', 'school_major' => ['IPA']],

                // HAYATI
                ['name' => 'Rekayasa Hayati', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Rekayasa Pertanian', 'degree' => 'S1', 'field' => 'agroteknologi', 'school_major' => ['IPA']],
                ['name' => 'Rekayasa Kehutanan', 'degree' => 'S1', 'field' => 'kehutanan', 'school_major' => ['IPA']],
                ['name' => 'Teknologi Pascapanen', 'degree' => 'S1', 'field' => 'teknologi_pangan', 'school_major' => ['IPA']],

                // FARMASI
                ['name' => 'Sains dan Teknologi Farmasi', 'degree' => 'S1', 'field' => 'farmasi_ilmu', 'school_major' => ['IPA']],
                ['name' => 'Farmasi Klinik dan Komunitas', 'degree' => 'S1', 'field' => 'farmasi_ilmu', 'school_major' => ['IPA']],

                // ARSITEKTUR
                ['name' => 'Arsitektur', 'degree' => 'S1', 'field' => 'arsitektur_ilmu', 'school_major' => ['IPA']],
                ['name' => 'Perencanaan Wilayah dan Kota', 'degree' => 'S1', 'field' => 'perencanaan', 'school_major' => ['IPA']],

                // SENI
                ['name' => 'Desain Interior', 'degree' => 'S1', 'field' => 'desain', 'school_major' => ['IPA', 'IPS', 'SMK']],
                ['name' => 'Desain Komunikasi Visual', 'degree' => 'S1', 'field' => 'desain', 'school_major' => ['IPA', 'IPS', 'BAHASA', 'SMK']],
                ['name' => 'Desain Produk', 'degree' => 'S1', 'field' => 'desain', 'school_major' => ['IPA', 'IPS', 'SMK']],
                ['name' => 'Kriya', 'degree' => 'S1', 'field' => 'seni_rupa', 'school_major' => ['IPA', 'IPS', 'BAHASA', 'SMK']],
                ['name' => 'Seni Rupa', 'degree' => 'S1', 'field' => 'seni_rupa', 'school_major' => ['IPA', 'IPS', 'BAHASA', 'SMK']],

                // BISNIS
                ['name' => 'Manajemen', 'degree' => 'S1', 'field' => 'manajemen', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Kewirausahaan', 'degree' => 'S1', 'field' => 'bisnis', 'school_major' => ['IPA', 'IPS']],
            ],


            // =====================================================
            // UGM
            // =====================================================

            'UGM' => [

                // SAINS
                ['name' => 'Matematika', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],
                ['name' => 'Statistika', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],
                ['name' => 'Ilmu Aktuaria', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],
                ['name' => 'Fisika', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Kimia', 'degree' => 'S1', 'field' => 'kimia', 'school_major' => ['IPA']],
                ['name' => 'Biologi', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Geofisika', 'degree' => 'S1', 'field' => 'kebumian', 'school_major' => ['IPA']],
                ['name' => 'Geografi Lingkungan', 'degree' => 'S1', 'field' => 'kebumian', 'school_major' => ['IPA', 'IPS']],

                // KOMPUTER
                ['name' => 'Ilmu Komputer', 'degree' => 'S1', 'field' => 'informatika', 'school_major' => ['IPA']],
                ['name' => 'Teknologi Informasi', 'degree' => 'S1', 'field' => 'informatika', 'school_major' => ['IPA']],
                ['name' => 'Elektronika dan Instrumentasi', 'degree' => 'S1', 'field' => 'instrumentasi', 'school_major' => ['IPA']],
                ['name' => 'Sistem Informasi Geografis', 'degree' => 'S1', 'field' => 'geodesi', 'school_major' => ['IPA', 'IPS']],

                // TEKNIK
                ['name' => 'Teknik Sipil', 'degree' => 'S1', 'field' => 'sipil', 'school_major' => ['IPA']],
                ['name' => 'Teknik Infrastruktur Lingkungan', 'degree' => 'S1', 'field' => 'lingkungan', 'school_major' => ['IPA']],
                ['name' => 'Teknik Sumber Daya Air', 'degree' => 'S1', 'field' => 'sipil', 'school_major' => ['IPA']],
                ['name' => 'Teknik Mesin', 'degree' => 'S1', 'field' => 'mesin', 'school_major' => ['IPA']],
                ['name' => 'Teknik Elektro', 'degree' => 'S1', 'field' => 'elektro', 'school_major' => ['IPA']],
                ['name' => 'Teknik Kimia', 'degree' => 'S1', 'field' => 'kimia_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Industri', 'degree' => 'S1', 'field' => 'industri', 'school_major' => ['IPA']],
                ['name' => 'Teknik Fisika', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Teknik Geodesi', 'degree' => 'S1', 'field' => 'geodesi', 'school_major' => ['IPA']],
                ['name' => 'Teknik Geologi', 'degree' => 'S1', 'field' => 'geologi_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Nuklir', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Teknik Pertanian', 'degree' => 'S1', 'field' => 'agroteknologi', 'school_major' => ['IPA']],

                // PERTANIAN
                ['name' => 'Agronomi', 'degree' => 'S1', 'field' => 'agronomi', 'school_major' => ['IPA']],
                ['name' => 'Ilmu Tanah', 'degree' => 'S1', 'field' => 'sumberdaya_lahan', 'school_major' => ['IPA']],
                ['name' => 'Proteksi Tanaman', 'degree' => 'S1', 'field' => 'agroteknologi', 'school_major' => ['IPA']],
                ['name' => 'Mikrobiologi Pertanian', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Ekonomi Pertanian dan Agribisnis', 'degree' => 'S1', 'field' => 'agribisnis', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Ilmu dan Industri Peternakan', 'degree' => 'S1', 'field' => 'peternakan', 'school_major' => ['IPA']],
                ['name' => 'Akuakultur', 'degree' => 'S1', 'field' => 'perikanan', 'school_major' => ['IPA']],
                ['name' => 'Manajemen Sumberdaya Akuatik', 'degree' => 'S1', 'field' => 'sumberdaya_perairan', 'school_major' => ['IPA']],
                ['name' => 'Teknologi Hasil Perikanan', 'degree' => 'S1', 'field' => 'perikanan', 'school_major' => ['IPA']],
                ['name' => 'Kehutanan', 'degree' => 'S1', 'field' => 'kehutanan', 'school_major' => ['IPA']],

                // PANGAN
                ['name' => 'Teknologi Pangan dan Hasil Pertanian', 'degree' => 'S1', 'field' => 'teknologi_pangan', 'school_major' => ['IPA']],
                ['name' => 'Teknologi Industri Pertanian', 'degree' => 'S1', 'field' => 'agroindustri', 'school_major' => ['IPA']],
                ['name' => 'Pengembangan Produk Agroindustri', 'degree' => 'S1', 'field' => 'agroindustri', 'school_major' => ['IPA']],

                // KESEHATAN
                ['name' => 'Kedokteran', 'degree' => 'S1', 'field' => 'kedokteran', 'school_major' => ['IPA']],
                ['name' => 'Kedokteran Gigi', 'degree' => 'S1', 'field' => 'kedokteran_gigi', 'school_major' => ['IPA']],
                ['name' => 'Higiene Gigi', 'degree' => 'S1', 'field' => 'kedokteran_gigi', 'school_major' => ['IPA']],
                ['name' => 'Kedokteran Hewan', 'degree' => 'S1', 'field' => 'peternakan', 'school_major' => ['IPA']],
                ['name' => 'Ilmu Keperawatan', 'degree' => 'S1', 'field' => 'keperawatan', 'school_major' => ['IPA']],
                ['name' => 'Gizi', 'degree' => 'S1', 'field' => 'gizi', 'school_major' => ['IPA']],
                ['name' => 'Manajemen Informasi Kesehatan', 'degree' => 'S1', 'field' => 'kesehatan_masyarakat', 'school_major' => ['IPA', 'IPS']],

                // FARMASI
                ['name' => 'Farmasi', 'degree' => 'S1', 'field' => 'farmasi_ilmu', 'school_major' => ['IPA']],

                // EKONOMI
                ['name' => 'Ilmu Ekonomi', 'degree' => 'S1', 'field' => 'ekonomi_ilmu', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Manajemen', 'degree' => 'S1', 'field' => 'manajemen', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Akuntansi', 'degree' => 'S1', 'field' => 'akuntansi', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Akuntansi Sektor Publik', 'degree' => 'D4', 'field' => 'akuntansi', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Perbankan', 'degree' => 'D4', 'field' => 'akuntansi', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Manajemen dan Penilaian Properti', 'degree' => 'D4', 'field' => 'manajemen', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Pembangunan Ekonomi Kewilayahan', 'degree' => 'D4', 'field' => 'ekonomi_ilmu', 'school_major' => ['IPA', 'IPS']],

                // SOSIAL
                ['name' => 'Politik dan Pemerintahan', 'degree' => 'S1', 'field' => 'politik', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Ilmu Hubungan Internasional', 'degree' => 'S1', 'field' => 'hubungan_internasional', 'school_major' => ['IPS', 'IPA', 'BAHASA']],
                ['name' => 'Manajemen dan Kebijakan Publik', 'degree' => 'S1', 'field' => 'administrasi', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Sosiologi', 'degree' => 'S1', 'field' => 'sosiologi', 'school_major' => ['IPS', 'IPA']],
                ['name' => 'Pembangunan Sosial dan Kesejahteraan', 'degree' => 'S1', 'field' => 'sosiologi', 'school_major' => ['IPS', 'IPA']],

                // HUKUM
                ['name' => 'Hukum', 'degree' => 'S1', 'field' => 'hukum_ilmu', 'school_major' => ['IPA', 'IPS', 'BAHASA']],

                // PSIKOLOGI
                ['name' => 'Psikologi', 'degree' => 'S1', 'field' => 'psikologi_ilmu', 'school_major' => ['IPA', 'IPS']],

                // HUMANIORA
                ['name' => 'Sejarah', 'degree' => 'S1', 'field' => 'sejarah', 'school_major' => ['IPA', 'IPS', 'BAHASA']],
                ['name' => 'Filsafat', 'degree' => 'S1', 'field' => 'filsafat', 'school_major' => ['IPA', 'IPS', 'BAHASA']],
                ['name' => 'Arkeologi', 'degree' => 'S1', 'field' => 'sejarah', 'school_major' => ['IPA', 'IPS', 'BAHASA']],
                ['name' => 'Sastra Arab', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Sastra Inggris', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Bahasa dan Sastra Indonesia', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Bahasa dan Sastra Jawa', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Bahasa dan Kebudayaan Jepang', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Bahasa dan Kebudayaan Korea', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],
                ['name' => 'Bahasa dan Sastra Prancis', 'degree' => 'S1', 'field' => 'bahasa_sastra', 'school_major' => ['BAHASA', 'IPS']],

                // PARIWISATA
                ['name' => 'Pariwisata', 'degree' => 'S1', 'field' => 'bisnis', 'school_major' => ['IPS', 'IPA', 'BAHASA']],
                ['name' => 'Bisnis Perjalanan Wisata', 'degree' => 'D4', 'field' => 'bisnis', 'school_major' => ['IPS', 'IPA', 'BAHASA']],
            ],


            // =====================================================
            // IPB UNIVERSITY
            // =====================================================

            'IPB' => [

                // SAINS
                ['name' => 'Matematika', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],
                ['name' => 'Statistika dan Sains Data', 'degree' => 'S1', 'field' => 'data_ai', 'school_major' => ['IPA']],
                ['name' => 'Fisika', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Kimia', 'degree' => 'S1', 'field' => 'kimia', 'school_major' => ['IPA']],
                ['name' => 'Biologi', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Biokimia', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Meteorologi Terapan', 'degree' => 'S1', 'field' => 'kebumian', 'school_major' => ['IPA']],

                // KOMPUTER
                ['name' => 'Ilmu Komputer', 'degree' => 'S1', 'field' => 'informatika', 'school_major' => ['IPA']],
                ['name' => 'Kecerdasan Buatan', 'degree' => 'S1', 'field' => 'data_ai', 'school_major' => ['IPA']],
                ['name' => 'Bioinformatika', 'degree' => 'S1', 'field' => 'data_ai', 'school_major' => ['IPA']],
                ['name' => 'Aktuaria', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],

                // PERTANIAN
                ['name' => 'Agronomi dan Hortikultura', 'degree' => 'S1', 'field' => 'agronomi', 'school_major' => ['IPA']],
                ['name' => 'Proteksi Tanaman', 'degree' => 'S1', 'field' => 'agroteknologi', 'school_major' => ['IPA']],
                ['name' => 'Manajemen Sumberdaya Lahan', 'degree' => 'S1', 'field' => 'sumberdaya_lahan', 'school_major' => ['IPA']],
                ['name' => 'Smart Agriculture', 'degree' => 'S1', 'field' => 'agroteknologi', 'school_major' => ['IPA']],
                ['name' => 'Agribisnis', 'degree' => 'S1', 'field' => 'agribisnis', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Teknologi Produksi Ternak', 'degree' => 'S1', 'field' => 'peternakan', 'school_major' => ['IPA']],
                ['name' => 'Nutrisi dan Teknologi Pakan', 'degree' => 'S1', 'field' => 'peternakan', 'school_major' => ['IPA']],
                ['name' => 'Teknologi Hasil Ternak', 'degree' => 'S1', 'field' => 'peternakan', 'school_major' => ['IPA']],
                ['name' => 'Manajemen Hutan', 'degree' => 'S1', 'field' => 'kehutanan', 'school_major' => ['IPA']],
                ['name' => 'Teknologi Hasil Hutan', 'degree' => 'S1', 'field' => 'kehutanan', 'school_major' => ['IPA']],
                ['name' => 'Konservasi Sumberdaya Hutan dan Ekowisata', 'degree' => 'S1', 'field' => 'kehutanan', 'school_major' => ['IPA']],
                ['name' => 'Silvikultur', 'degree' => 'S1', 'field' => 'kehutanan', 'school_major' => ['IPA']],

                // PERIKANAN
                ['name' => 'Teknologi dan Manajemen Perikanan Budidaya', 'degree' => 'S1', 'field' => 'perikanan', 'school_major' => ['IPA']],
                ['name' => 'Manajemen Sumberdaya Perairan', 'degree' => 'S1', 'field' => 'sumberdaya_perairan', 'school_major' => ['IPA']],
                ['name' => 'Teknologi Hasil Perairan', 'degree' => 'S1', 'field' => 'perikanan', 'school_major' => ['IPA']],
                ['name' => 'Teknologi dan Manajemen Perikanan Tangkap', 'degree' => 'S1', 'field' => 'perikanan', 'school_major' => ['IPA']],
                ['name' => 'Ilmu dan Teknologi Kelautan', 'degree' => 'S1', 'field' => 'kelautan', 'school_major' => ['IPA']],

                // TEKNIK
                ['name' => 'Teknik Pertanian dan Biosistem', 'degree' => 'S1', 'field' => 'agroteknologi', 'school_major' => ['IPA']],
                ['name' => 'Teknik Industri Pertanian', 'degree' => 'S1', 'field' => 'agroindustri', 'school_major' => ['IPA']],
                ['name' => 'Teknik Sipil dan Lingkungan', 'degree' => 'S1', 'field' => 'sipil', 'school_major' => ['IPA']],
                ['name' => 'Teknik Mesin', 'degree' => 'S1', 'field' => 'mesin', 'school_major' => ['IPA']],
                ['name' => 'Teknik Kimia', 'degree' => 'S1', 'field' => 'kimia_teknik', 'school_major' => ['IPA']],

                // PANGAN
                ['name' => 'Teknologi Pangan', 'degree' => 'S1', 'field' => 'teknologi_pangan', 'school_major' => ['IPA']],

                // EKONOMI
                ['name' => 'Ekonomi Pembangunan', 'degree' => 'S1', 'field' => 'ekonomi_ilmu', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Ekonomi Sumberdaya dan Lingkungan', 'degree' => 'S1', 'field' => 'ekonomi_ilmu', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Manajemen', 'degree' => 'S1', 'field' => 'manajemen', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Ilmu Ekonomi Syariah', 'degree' => 'S1', 'field' => 'ekonomi_syariah', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Bisnis', 'degree' => 'S1', 'field' => 'bisnis', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Agribisnis', 'degree' => 'S1', 'field' => 'agribisnis', 'school_major' => ['IPA', 'IPS']],

                // SOSIAL
                ['name' => 'Ilmu Keluarga dan Konsumen', 'degree' => 'S1', 'field' => 'sosiologi', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Komunikasi dan Pengembangan Masyarakat', 'degree' => 'S1', 'field' => 'komunikasi', 'school_major' => ['IPA', 'IPS']],

                // KESEHATAN
                ['name' => 'Kedokteran', 'degree' => 'S1', 'field' => 'kedokteran', 'school_major' => ['IPA']],
                ['name' => 'Ilmu Gizi', 'degree' => 'S1', 'field' => 'gizi', 'school_major' => ['IPA']],
                ['name' => 'Kedokteran Hewan', 'degree' => 'S1', 'field' => 'peternakan', 'school_major' => ['IPA']],
                ['name' => 'Sains Biomedis', 'degree' => 'S1', 'field' => 'biomedis', 'school_major' => ['IPA']],
                ['name' => 'Arsitektur Lanskap', 'degree' => 'S1', 'field' => 'arsitektur_ilmu', 'school_major' => ['IPA', 'IPS']],

                // D4 IPB
                ['name' => 'Teknologi Rekayasa Perangkat Lunak', 'degree' => 'D4', 'field' => 'informatika', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknologi Rekayasa Komputer', 'degree' => 'D4', 'field' => 'informatika', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknologi dan Manajemen Produksi Perkebunan', 'degree' => 'D4', 'field' => 'agroteknologi', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknologi dan Manajemen Pembenihan Ikan', 'degree' => 'D4', 'field' => 'perikanan', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknologi Produksi dan Manajemen Peternakan', 'degree' => 'D4', 'field' => 'peternakan', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Manajemen Industri Jasa Makanan dan Gizi', 'degree' => 'D4', 'field' => 'teknologi_pangan', 'school_major' => ['IPA', 'IPS', 'SMK']],
                ['name' => 'Ekowisata', 'degree' => 'D4', 'field' => 'kehutanan', 'school_major' => ['IPA', 'IPS', 'SMK']],
            ],


            // =====================================================
            // ITS
            // =====================================================

            'ITS' => [

                // SAINS
                ['name' => 'Matematika', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],
                ['name' => 'Statistika', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],
                ['name' => 'Sains Data', 'degree' => 'S1', 'field' => 'data_ai', 'school_major' => ['IPA']],
                ['name' => 'Fisika', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Kimia', 'degree' => 'S1', 'field' => 'kimia', 'school_major' => ['IPA']],
                ['name' => 'Sains Analitik dan Instrumentasi Kimia', 'degree' => 'S1', 'field' => 'kimia', 'school_major' => ['IPA']],
                ['name' => 'Biologi', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Bioteknologi', 'degree' => 'S1', 'field' => 'biologi', 'school_major' => ['IPA']],
                ['name' => 'Sains Aktuaria', 'degree' => 'S1', 'field' => 'matematika', 'school_major' => ['IPA']],

                // TEKNIK
                ['name' => 'Teknik Mesin', 'degree' => 'S1', 'field' => 'mesin', 'school_major' => ['IPA']],
                ['name' => 'Rekayasa Keselamatan Proses', 'degree' => 'S1', 'field' => 'industri', 'school_major' => ['IPA']],
                ['name' => 'Teknik Kimia', 'degree' => 'S1', 'field' => 'kimia_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Pangan', 'degree' => 'S1', 'field' => 'teknologi_pangan', 'school_major' => ['IPA']],
                ['name' => 'Teknik Fisika', 'degree' => 'S1', 'field' => 'fisika', 'school_major' => ['IPA']],
                ['name' => 'Teknik Industri', 'degree' => 'S1', 'field' => 'industri', 'school_major' => ['IPA']],
                ['name' => 'Teknik Material', 'degree' => 'S1', 'field' => 'material', 'school_major' => ['IPA']],
                ['name' => 'Teknik Sipil', 'degree' => 'S1', 'field' => 'sipil', 'school_major' => ['IPA']],
                ['name' => 'Teknik Lingkungan', 'degree' => 'S1', 'field' => 'lingkungan', 'school_major' => ['IPA']],
                ['name' => 'Teknik Geomatika', 'degree' => 'S1', 'field' => 'geodesi', 'school_major' => ['IPA']],
                ['name' => 'Perencanaan Wilayah dan Kota', 'degree' => 'S1', 'field' => 'perencanaan', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Arsitektur', 'degree' => 'S1', 'field' => 'arsitektur_ilmu', 'school_major' => ['IPA']],
                ['name' => 'Teknik Kelautan', 'degree' => 'S1', 'field' => 'kelautan_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Perkapalan', 'degree' => 'S1', 'field' => 'kelautan_teknik', 'school_major' => ['IPA']],
                ['name' => 'Teknik Sistem Perkapalan', 'degree' => 'S1', 'field' => 'kelautan_teknik', 'school_major' => ['IPA']],

                // KOMPUTER
                ['name' => 'Informatika', 'degree' => 'S1', 'field' => 'informatika', 'school_major' => ['IPA']],
                ['name' => 'Sistem Informasi', 'degree' => 'S1', 'field' => 'sistem_informasi', 'school_major' => ['IPA', 'IPS']],
                ['name' => 'Teknologi Informasi', 'degree' => 'S1', 'field' => 'informatika', 'school_major' => ['IPA']],
                ['name' => 'Rekayasa Kecerdasan Artifisial', 'degree' => 'S1', 'field' => 'data_ai', 'school_major' => ['IPA']],
                ['name' => 'Teknologi Informasi dan Komunikasi', 'degree' => 'S1', 'field' => 'informatika', 'school_major' => ['IPA']],

                // ELEKTRO
                ['name' => 'Teknik Elektro', 'degree' => 'S1', 'field' => 'elektro', 'school_major' => ['IPA']],
                ['name' => 'Teknik Telekomunikasi', 'degree' => 'S1', 'field' => 'elektro', 'school_major' => ['IPA']],
                ['name' => 'Teknik Biomedik', 'degree' => 'S1', 'field' => 'biomedis', 'school_major' => ['IPA']],

                // DESAIN
                ['name' => 'Desain Produk Industri', 'degree' => 'S1', 'field' => 'desain', 'school_major' => ['IPA', 'IPS', 'SMK']],
                ['name' => 'Desain Komunikasi Visual', 'degree' => 'S1', 'field' => 'desain', 'school_major' => ['IPA', 'IPS', 'BAHASA', 'SMK']],
                ['name' => 'Seni Rupa', 'degree' => 'S1', 'field' => 'seni_rupa', 'school_major' => ['IPA', 'IPS', 'BAHASA', 'SMK']],

                // D4 ITS
                ['name' => 'Teknologi Rekayasa Otomasi', 'degree' => 'D4', 'field' => 'instrumentasi', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknologi Rekayasa Instrumentasi', 'degree' => 'D4', 'field' => 'instrumentasi', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknik Sipil', 'degree' => 'D4', 'field' => 'sipil', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknologi Rekayasa Konstruksi Bangunan Air', 'degree' => 'D4', 'field' => 'sipil', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknologi Rekayasa Konversi Energi', 'degree' => 'D4', 'field' => 'energi', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Teknologi Rekayasa Manufaktur', 'degree' => 'D4', 'field' => 'mesin', 'school_major' => ['IPA', 'SMK']],
                ['name' => 'Statistika Bisnis', 'degree' => 'D4', 'field' => 'matematika', 'school_major' => ['IPA', 'IPS', 'SMK']],
            ],
        ];


        // =========================================================
        // 6. INSERT / SYNC PROGRAM STUDI + BOBOT RIASEC + KAMPUS
        // =========================================================
        $campusScores = ['UI' => 100, 'ITB' => 95, 'UGM' => 90, 'IPB' => 85, 'ITS' => 80];
        $accreditation = 'Unggul';
        $uniqueAlternatives = [];

        foreach ($programs as $campusCode => $campusPrograms) {
            $campus = $createdCampuses[$campusCode];

            foreach ($campusPrograms as $program) {
                $field = $fields[$program['field']];
                $weightKey = $program['degree'] . '|' . $program['name'];

                if (!isset($riasecWeights[$weightKey])) {
                    throw new \RuntimeException("Bobot RIASEC belum tersedia untuk: {$weightKey}");
                }

                $w = $riasecWeights[$weightKey];
                $sum = round($w['R'] + $w['I'] + $w['A'] + $w['S'] + $w['E'] + $w['C'], 2);

                if ($sum !== 1.00) {
                    throw new \RuntimeException("Total bobot RIASEC harus 1.00 untuk: {$weightKey}, hasil: {$sum}");
                }

                $uniqueAlternatives[$program['name'] . '|' . $program['degree']] = true;

                $major = Major::updateOrCreate(
                    ['name' => $program['name'], 'degree' => $program['degree']],
                    [
                        'field_of_study_id' => $field->id,
                        'description' => 'Program ' . $program['degree'] . ' ' . $program['name'] . ' pada bidang ' . $field->name . '.',
                        'prospects' => 'Peluang karier disesuaikan dengan kompetensi dan bidang keilmuan program studi ' . $program['name'] . '.',
                    ]
                );

                // Bobot RIASEC SPESIFIK per program studi — sumber utama untuk
                // App\Services\SawRecommendationService::buildMajorWeightMatrix().
                MajorCriteria::updateOrCreate(
                    ['major_id' => $major->id],
                    [
                        'r_std' => $w['R'],
                        'i_std' => $w['I'],
                        'a_std' => $w['A'],
                        's_std' => $w['S'],
                        'e_std' => $w['E'],
                        'c_std' => $w['C'],
                    ]
                );

                $major->campuses()->syncWithoutDetaching([
                    $campus->id => [
                        'required_school_major' => implode('/', $program['school_major']),
                        'weight_score' => $campusScores[$campusCode],
                        'accreditation' => $accreditation,
                    ],
                ]);
            }
        }

        if (count($uniqueAlternatives) !== 199) {
            throw new \RuntimeException('Jumlah alternatif unik harus 199, ditemukan: ' . count($uniqueAlternatives));
        }

        $this->command?->info('CampusMajorSeeder selesai: 199 alternatif unik dengan bobot RIASEC per program studi (siap dipakai SawRecommendationService).');
    }
}