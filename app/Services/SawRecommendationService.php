<?php

namespace App\Services;

use App\Models\Major;
use App\Models\RecommendationResult;
use App\Models\RiasecScore;
use App\Models\Student;

class SawRecommendationService
{
    /**
     * Enam kode dimensi RIASEC yang dipakai di seluruh perhitungan.
     */
    protected array $riasecKeys = ['R', 'I', 'A', 'S', 'E', 'C'];

    /**
     * Mapping kode huruf RIASEC ke nama dimensi lengkap (untuk teks laporan).
     */
    protected array $riasecNames = [
        'R' => 'Realistic',
        'I' => 'Investigative',
        'A' => 'Artistic',
        'S' => 'Social',
        'E' => 'Enterprising',
        'C' => 'Conventional',
    ];

    /**
     * Terjemahan tiap dimensi RIASEC menjadi aktivitas/karakteristik yang
     * mudah dipahami siswa, dipakai untuk narasi "Analisis Kecocokan Minat".
     * Sengaja tidak menampilkan kode huruf (R/I/A/S/E/C) di dalam narasi.
     */
    protected array $riasecActivityPhrases = [
        'R' => 'bekerja secara praktis, menggunakan alat atau teknologi secara langsung, dan menyelesaikan tugas secara nyata',
        'I' => 'mencari penyebab suatu permasalahan, memahami cara kerja suatu sistem, dan menemukan solusi secara logis',
        'A' => 'berekspresi secara kreatif serta menciptakan ide maupun karya yang orisinal',
        'S' => 'berinteraksi, membantu, dan bekerja sama dengan orang lain',
        'E' => 'memimpin, memengaruhi, dan mengambil inisiatif untuk mencapai suatu tujuan',
        'C' => 'bekerja secara terstruktur, teliti, dan mengelola data maupun administrasi dengan rapi',
    ];

    /**
     * Skor maksimum tiap dimensi (4 pernyataan x skala 1-5 = max 20).
     */
    protected int $maxDimensionScore = 20;


    /**
     * Metadata lengkap setiap dimensi RIASEC untuk kebutuhan presentasi,
     * analisis, dan narasi. Ini menjadi satu-satunya sumber metadata RIASEC.
     */
    protected function riasecDimensionMeta(): array
    {
        return [
            'R' => [
                'name' => 'Realistic',
                'short' => 'Praktis & teknis',
                'interest' => 'aktivitas praktis, penggunaan perangkat, perakitan, dan penyelesaian masalah yang berhubungan dengan objek nyata',
                'major' => 'penerapan teknologi, penggunaan perangkat, sistem fisik, dan pekerjaan teknis',
            ],
            'I' => [
                'name' => 'Investigative',
                'short' => 'Analitis & riset',
                'interest' => 'mencari penyebab permasalahan, memahami cara kerja suatu sistem, dan menemukan solusi secara logis',
                'major' => 'analisis, pemrograman, penelitian, pemecahan masalah, dan pengembangan solusi',
            ],
            'A' => [
                'name' => 'Artistic',
                'short' => 'Kreatif & ekspresif',
                'interest' => 'mengembangkan kreativitas, menghasilkan gagasan, memperhatikan tampilan, serta menciptakan sesuatu yang memiliki nilai keunikan',
                'major' => 'desain, kreativitas, pengembangan konsep, komunikasi visual, dan penciptaan karya',
            ],
            'S' => [
                'name' => 'Social',
                'short' => 'Sosial & membantu',
                'interest' => 'berkomunikasi, bekerja sama, membimbing orang lain, dan membantu menyelesaikan kebutuhan atau permasalahan pengguna',
                'major' => 'interaksi dengan pengguna, komunikasi, edukasi, pelayanan, dan kerja kolaboratif',
            ],
            'E' => [
                'name' => 'Enterprising',
                'short' => 'Memimpin & bisnis',
                'interest' => 'memimpin kegiatan, mengambil keputusan, menyampaikan gagasan, mengelola kegiatan, dan melihat peluang',
                'major' => 'kepemimpinan, pengelolaan proyek, strategi, bisnis, dan pengambilan keputusan',
            ],
            'C' => [
                'name' => 'Conventional',
                'short' => 'Terstruktur & teliti',
                'interest' => 'mengelola informasi secara rapi, bekerja teliti, mengikuti prosedur, dan memastikan data tetap terorganisasi',
                'major' => 'pengelolaan data, dokumentasi, administrasi, sistem informasi, dan pekerjaan yang membutuhkan ketelitian',
            ],
        ];
    }

    /**
     * Menentukan N dimensi RIASEC teratas.
     * K tetap diterima sebagai alias C untuk kompatibilitas data lama.
     */
    public function topRiasecCodes(array $scores, int $limit = 3): array
    {
        $normalized = [
            'R' => (float) ($scores['R'] ?? 0),
            'I' => (float) ($scores['I'] ?? 0),
            'A' => (float) ($scores['A'] ?? 0),
            'S' => (float) ($scores['S'] ?? 0),
            'E' => (float) ($scores['E'] ?? 0),
            'C' => (float) ($scores['C'] ?? $scores['K'] ?? 0),
        ];

        arsort($normalized);

        return array_slice(array_keys($normalized), 0, $limit);
    }

    /**
     * Status kecocokan berdasarkan persentase skor 0-100.
     */
    public function resolveScoreStatus(float $scorePercent): array
    {
        if ($scorePercent >= 80) {
            return [
                'status' => 'Sangat Cocok',
                'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/20',
                'icon' => 'fa-circle-check',
                'score_class' => 'text-emerald-600 dark:text-emerald-400',
                'progress_class' => 'bg-emerald-500',
            ];
        }

        if ($scorePercent >= 65) {
            return [
                'status' => 'Cocok',
                'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-300 dark:border-indigo-500/20',
                'icon' => 'fa-thumbs-up',
                'score_class' => 'text-indigo-600 dark:text-indigo-400',
                'progress_class' => 'bg-indigo-500',
            ];
        }

        return [
            'status' => 'Alternatif',
            'class' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
            'icon' => 'fa-bookmark',
            'score_class' => 'text-slate-600 dark:text-slate-300',
            'progress_class' => 'bg-slate-400',
        ];
    }

    /**
     * Level TSK pada skala 0-120.
     */
    public function resolveTskLevel(float $tsk): array
    {
        if ($tsk >= 97) {
            return [
                'level' => 'Sangat Tinggi',
                'description' => 'Profil minat siswa menunjukkan kekuatan yang sangat tinggi secara keseluruhan. Kondisi ini dapat menjadi dasar yang kuat dalam mengeksplorasi berbagai bidang program studi yang sesuai.',
                'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/20',
            ];
        }

        if ($tsk >= 73) {
            return [
                'level' => 'Tinggi',
                'description' => 'Profil minat siswa menunjukkan kekuatan minat yang tinggi secara keseluruhan dan dapat menjadi salah satu pertimbangan utama dalam menentukan program studi.',
                'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-300 dark:border-indigo-500/20',
            ];
        }

        if ($tsk >= 49) {
            return [
                'level' => 'Sedang',
                'description' => 'Profil minat siswa berada pada tingkat sedang. Hasil rekomendasi dapat digunakan sebagai bahan eksplorasi sebelum menentukan pilihan program studi.',
                'class' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:border-amber-500/20',
            ];
        }

        return [
            'level' => 'Rendah',
            'description' => 'Profil minat siswa masih berada pada tingkat rendah secara keseluruhan. Siswa disarankan memperluas eksplorasi terhadap berbagai aktivitas dan bidang pembelajaran.',
            'class' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        ];
    }

    /**
     * Narasi analisis kecocokan minat siswa dan program studi.
     * Dipusatkan di service agar halaman siswa dan laporan BK memakai aturan sama.
     */
    public function buildInterestAnalysis(
        array $studentScores,
        array $majorWeights,
        string $majorName
    ): string {
        $meta = $this->riasecDimensionMeta();
        $studentTop = $this->topRiasecCodes($studentScores, 3);
        $majorTop = $this->topRiasecCodes($majorWeights, 3);

        $matchedCodes = array_values(array_intersect($studentTop, $majorTop));

        if (empty($matchedCodes)) {
            $matchedCodes = array_values(array_filter(
                $studentTop,
                function ($code) use ($studentScores, $majorWeights) {
                    return (float) ($majorWeights[$code] ?? 0) > 0
                        && (float) ($studentScores[$code] ?? 0) > 0;
                }
            ));
        }

        if (empty($matchedCodes)) {
            $matchedCodes = $studentTop;
        }

        $selectedDimensions = collect($matchedCodes)
            ->map(fn ($code) => $meta[$code] ?? null)
            ->filter()
            ->values();

        if ($selectedDimensions->isEmpty()) {
            return 'Profil minat siswa menunjukkan kecenderungan yang relevan dengan karakteristik bidang '
                . $majorName
                . '. Program studi ini memiliki aktivitas pembelajaran yang dapat mendukung pengembangan minat, kemampuan, dan potensi siswa.';
        }

        $interestSentence = $this->joinAsSentence(
            $selectedDimensions->pluck('interest')->unique()->take(3)->values()->all()
        );

        $supportSentence = $this->joinAsSentence(
            $selectedDimensions->pluck('major')->unique()->take(3)->values()->all()
        );

        return 'Profil minat siswa menunjukkan ketertarikan pada '
            . $interestSentence
            . ' Kecenderungan tersebut selaras dengan karakteristik '
            . $majorName
            . ' yang banyak melibatkan '
            . lcfirst($supportSentence);
    }

    /**
     * Helper pembentuk kalimat natural.
     */
    protected function joinAsSentence(array $parts): string
    {
        $parts = array_values(array_filter($parts));

        if (count($parts) === 0) {
            return '';
        }

        if (count($parts) === 1) {
            return ucfirst($parts[0]) . '.';
        }

        if (count($parts) === 2) {
            return ucfirst($parts[0]) . ', serta ' . $parts[1] . '.';
        }

        $last = array_pop($parts);

        return ucfirst(implode(', ', $parts)) . ', serta ' . $last . '.';
    }

    /**
     * Data RIASEC siap ditampilkan pada kartu/tabel profil siswa.
     */
    public function buildRiasecPresentation(
        array $studentScores,
        array $majorWeights = [],
        float $majorScale = 1.0
    ): array {
        $meta = $this->riasecDimensionMeta();
        $studentTop = $this->topRiasecCodes($studentScores, 3);
        $majorTop = !empty($majorWeights)
            ? $this->topRiasecCodes($majorWeights, 3)
            : [];
        $matchedCodes = array_values(array_intersect($studentTop, $majorTop));

        $items = [];

        foreach ($meta as $code => $info) {
            $studentScore = (float) ($studentScores[$code] ?? 0);
            $majorWeight = (float) ($majorWeights[$code] ?? 0);

            $items[$code] = [
                'code' => $code,
                'name' => $info['name'],
                'short' => $info['short'],
                'student_score' => $studentScore,
                'student_percent' => min(100, round(($studentScore / $this->maxDimensionScore) * 100)),
                'major_weight' => $majorWeight,
                'major_percent' => $majorScale > 0
                    ? min(100, round(($majorWeight / $majorScale) * 100))
                    : 0,
                'is_student_top' => in_array($code, $studentTop, true),
                'is_major_top' => in_array($code, $majorTop, true),
                'is_matched' => in_array($code, $matchedCodes, true),
            ];
        }

        return [
            'items' => $items,
            'student_top' => $studentTop,
            'major_top' => $majorTop,
            'matched_codes' => $matchedCodes,
            'matched_count' => count($matchedCodes),
        ];
    }

    /**
     * Rincian perhitungan SAW per dimensi untuk tabel transparansi.
     * Normalisasi siswa = Xi / 20.
     * Kontribusi = normalisasi x bobot program studi.
     */
    public function buildSawRows(array $studentScores, array $majorWeights): array
    {
        $rows = [];
        $totalWeight = array_sum($majorWeights);
        $totalContribution = 0.0;

        foreach ($this->riasecDimensionMeta() as $code => $info) {
            $studentValue = (float) ($studentScores[$code] ?? 0);
            $majorValue = (float) ($majorWeights[$code] ?? 0);
            $normalized = min(1, $studentValue / $this->maxDimensionScore);
            $contribution = $normalized * $majorValue;
            $totalContribution += $contribution;

            $rows[$code] = [
                'name' => $info['name'],
                'student' => $studentValue,
                'weight' => $majorValue,
                'normalized' => $normalized,
                'contribution' => $contribution,
            ];
        }

        return [
            'rows' => $rows,
            'total_weight' => $totalWeight,
            'total_contribution' => $totalContribution,
            'preference_score' => $totalWeight > 0
                ? $totalContribution / $totalWeight
                : 0,
        ];
    }

    /**
     * Normalisasi skor preferensi ke persentase 0-100.
     */
    public function toScorePercent($rawScore): float
    {
        $score = (float) $rawScore;
        $score = $score <= 1 ? $score * 100 : $score;

        return max(0, min(100, $score));
    }

    /**
     * Berapa banyak baris ranking program studi yang disimpan per siswa
     * (dipakai oleh runForAllStudents(), tidak memengaruhi tampilan tabel
     * "Perhitungan & Rekomendasi" yang menampilkan seluruh alternatif).
     */
    protected int $storeTopN = 199;

    /**
     * Berapa banyak program studi teratas yang ditampilkan pada
     * "Analisis Kecocokan Program Studi".
     */
    protected int $top5Count = 5;

    /**
     * Berapa banyak PTN (kampus) teratas yang ditampilkan sebagai
     * penyedia sebuah program studi. Menggantikan pendekatan lama yang
     * memetakan SATU kampus dari rentang skor TSK siswa
     * (lihat catatan pada getTopCampusesForMajor()).
     */
    protected int $topCampusCount = 5;

    /**
     * Memeriksa kelengkapan data siswa. Satu-satunya syarat kelengkapan
     * untuk proses SAW adalah Tes RIASEC (24 pernyataan).
     */
    public function checkCompleteness(Student $student): array
    {
        $hasRiasec = RiasecScore::where('student_id', $student->id)->exists();

        $missing = [];
        if (!$hasRiasec) {
            $missing[] = 'Tes RIASEC';
        }

        $isComplete = empty($missing);

        return [
            'is_complete' => $isComplete,
            'missing' => $missing,
            'label' => $isComplete ? 'Lengkap' : 'Belum Lengkap (' . implode(', ', $missing) . ')',
        ];
    }

    /**
     * Mengambil enam skor RIASEC siswa (R..C) dari tabel riasec_scores.
     */
    public function getStudentRiasecScores(Student $student): ?array
    {
        $riasec = RiasecScore::where('student_id', $student->id)->first();

        if (!$riasec) {
            return null;
        }

        return [
            'R' => (int) ($riasec->r_score ?? 0),
            'I' => (int) ($riasec->i_score ?? 0),
            'A' => (int) ($riasec->a_score ?? 0),
            'S' => (int) ($riasec->s_score ?? 0),
            'E' => (int) ($riasec->e_score ?? 0),
            'C' => (int) ($riasec->c_score ?? 0),
        ];
    }

    /**
     * TSK (Total Skor Komposit) = R + I + A + S + E + C. Rentang 24-120.
     *
     * PENTING: TSK sekarang MURNI dipakai sebagai indikator kekuatan
     * profil minat siswa (lihat CalculationController::getTskDescription()).
     * TSK TIDAK LAGI dipakai untuk menentukan PTN/kampus mana pun.
     * Penentuan PTN sekarang berbasis jurusan yang direkomendasikan
     * (lihat getTopCampusesForMajor()).
     */
    public function calculateTsk(array $scores): int
    {
        $tsk = 0;
        foreach ($this->riasecKeys as $key) {
            $tsk += (int) ($scores[$key] ?? 0);
        }

        return $tsk;
    }

    /**
     * Normalisasi skor RIASEC siswa. Seluruh kriteria benefit, dinormalisasi
     * terhadap skor maksimum tiap dimensi (20): Ri = Xi / 20.
     */
    public function normalizeStudentScores(array $scores): array
    {
        $normalized = [];

        foreach ($this->riasecKeys as $key) {
            $normalized[$key] = round(((float) ($scores[$key] ?? 0)) / $this->maxDimensionScore, 4);
        }

        return $normalized;
    }

    /**
     * Ubah model bobot RIASEC program studi (r_std..c_std pada MajorCriteria)
     * menjadi array asosiatif per kode huruf. Bobot ini SPESIFIK per program
     * studi, diambil apa adanya dari Seeder/database.
     */
    public function criteriaToArray($criteria): array
    {
        if (!$criteria) {
            return array_fill_keys($this->riasecKeys, 0.0);
        }

        return [
            'R' => (float) ($criteria->r_std ?? 0),
            'I' => (float) ($criteria->i_std ?? 0),
            'A' => (float) ($criteria->a_std ?? 0),
            'S' => (float) ($criteria->s_std ?? 0),
            'E' => (float) ($criteria->e_std ?? 0),
            'C' => (float) ($criteria->c_std ?? 0),
        ];
    }

    /**
     * Dimensi RIASEC yang menjadi titik temu antara profil dominan siswa
     * dan profil bobot dominan program studi (nama dimensi, bukan kode).
     */
    public function getTopDimensionsForMajor(array $studentRiasec, $criteria): array
    {
        if (!$criteria) {
            return [];
        }

        $studentTop = $this->topRiasecCodes($studentRiasec, 3);
        $majorTop = $this->topRiasecCodes($this->criteriaToArray($criteria), 3);

        $intersect = array_values(array_intersect($studentTop, $majorTop));

        return array_map(fn ($code) => $this->riasecNames[$code] ?? $code, $intersect);
    }

    public function formatDimensionsText(array $names): string
    {
        return $this->joinWithDan(array_values(array_filter($names)));
    }

    /**
     * Gabungkan array teks dengan format "a, b dan c".
     */
    protected function joinWithDan(array $items): string
    {
        $items = array_values(array_filter($items));
        $count = count($items);

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $items[0];
        }

        $last = array_pop($items);

        return implode(', ', $items) . ' dan ' . $last;
    }

    /**
     * Bangun narasi "Analisis Kecocokan Minat" berdasarkan 1-3 dimensi
     * RIASEC paling relevan, diterjemahkan menjadi aktivitas/karakteristik
     * (tidak menampilkan kode seperti "I-E-S").
     */
    public function buildInterestNarrative(
        string $studentName,
        array $rawStudentScores,
        $criteria,
        ?string $majorName
    ): string {
        $majorName = $majorName ?: 'program studi ini';

        $dimensionCodes = [];

        if ($criteria) {
            $studentTop = $this->topRiasecCodes($rawStudentScores, 3);
            $majorTop = $this->topRiasecCodes($this->criteriaToArray($criteria), 3);
            $dimensionCodes = array_values(array_intersect($studentTop, $majorTop));
        }

        if (empty($dimensionCodes)) {
            $dimensionCodes = $this->topRiasecCodes($rawStudentScores, 2);
        }

        $dimensionCodes = array_slice($dimensionCodes, 0, 3);

        $phrases = array_values(array_filter(array_map(
            fn ($code) => $this->riasecActivityPhrases[$code] ?? null,
            $dimensionCodes
        )));

        if (empty($phrases)) {
            return "{$studentName} menunjukkan profil minat yang relevan dengan bidang {$majorName}.";
        }

        $phraseText = $this->joinWithDan($phrases);

        return "{$studentName} memiliki ketertarikan kuat dalam {$phraseText}. "
            . "Karakteristik tersebut mendukung bidang {$majorName} yang banyak melibatkan "
            . "aktivitas-aktivitas sesuai dengan minat tersebut.";
    }

    /**
     * Daftar nama kampus yang menyediakan sebuah program studi.
     * Murni informasi ketersediaan (bukan bagian dari perhitungan
     * kecocokan SAW).
     */
    public function getCampusAvailability($major): array
    {
        if (!$major || !method_exists($major, 'campuses')) {
            return [];
        }

        return $major->campuses
            ->pluck('name')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Ambil Top-N PTN (kampus) yang memfasilitasi sebuah program studi,
     * diurutkan berdasarkan weight_score pivot tertinggi (fallback ke 0
     * jika tidak ada).
     *
     * Method ini MENGGANTIKAN pendekatan lama determineCampusByTsk(), yang
     * memetakan SATU kampus dari rentang skor TSK siswa. Penentuan PTN
     * sekarang murni berbasis ketersediaan/bobot kampus terhadap jurusan
     * yang direkomendasikan (relasi Major -> Campus), bukan berdasarkan
     * skor profil RIASEC siswa.
     *
     * @return array<int, array{campus: mixed, accreditation: string, weight_score: mixed, quota: mixed, position: int}>
     */
    public function getTopCampusesForMajor($major, ?int $limit = null): array
    {
        if (!$major || !method_exists($major, 'campuses')) {
            return [];
        }

        $limit = $limit ?? $this->topCampusCount;

        return $major->campuses
            ->sortByDesc(fn ($campus) => (float) ($campus->pivot->weight_score ?? 0))
            ->take($limit)
            ->values()
            ->map(function ($campus, $index) {
                $position = $index + 1;

                return [
                    'campus' => $campus,
                    'accreditation' => $campus->pivot->accreditation ?? 'Unggul (A)',
                    'weight_score' => $campus->pivot->weight_score ?? null,
                    'quota' => $campus->pivot->quota ?? null,
                    'position' => $position,
                ];
            })
            ->all();
    }

    /**
     * Format nilai preferensi sebagai desimal Indonesia, mis. "0,8225".
     */
    public function formatPreferenceDecimal(float $score): string
    {
        return number_format($score, 4, ',', '.');
    }

    /**
     * Format nilai preferensi sebagai persen Indonesia, mis. "82,25%".
     */
    public function formatPreferencePercent(float $score): string
    {
        return number_format($score * 100, 2, ',', '.') . '%';
    }

    /**
     * Membangun matriks bobot RIASEC seluruh program studi (Major) dari
     * tabel major_criteria (Seeder). Program studi tanpa profil bobot
     * dilewati (tidak ikut dihitung) — tidak pernah diberi bobot random.
     */
    public function buildMajorWeightMatrix()
    {
        $majors = Major::with(['criteriaProfiles', 'campuses'])->get();

        $matrix = [];

        foreach ($majors as $major) {
            $criteria = $major->criteriaProfiles->first();

            if (!$criteria) {
                continue;
            }

            $matrix[$major->id] = [
                'major' => $major,
                'weights' => $this->criteriaToArray($criteria),
                'criteria' => $criteria,
            ];
        }

        return $matrix;
    }

    /**
     * Vi = Sum(Wij x Rj) untuk SELURUH program studi pada matriks bobot,
     * lalu diurutkan dari nilai preferensi tertinggi ke terendah.
     * Method ini yang menjadi sumber tunggal data tabel "Perhitungan SAW".
     *
     * TIDAK DIUBAH sesuai batasan: logika SAW inti tetap sama persis.
     *
     * @param array $normalizedStudent Hasil normalizeStudentScores() (0-1)
     * @param array $rawStudentScores  Skor mentah R..C (4-20)
     * @param array $majorWeightMatrix Hasil buildMajorWeightMatrix()
     */
    public function computePreferences(array $normalizedStudent, array $rawStudentScores, array $majorWeightMatrix): array
    {
        $ranked = [];

        foreach ($majorWeightMatrix as $majorId => $row) {
            $v = 0.0;

            foreach ($this->riasecKeys as $key) {
                $v += ($row['weights'][$key] ?? 0) * ($normalizedStudent[$key] ?? 0);
            }

            $topDimensions = $this->getTopDimensionsForMajor($rawStudentScores, $row['criteria']);

            $ranked[$majorId] = [
                'major' => $row['major'],
                'weights' => $row['weights'],
                'top_dimensions' => $topDimensions,
                'preference_score' => round($v, 4),
            ];
        }

        uasort($ranked, fn ($a, $b) => $b['preference_score'] <=> $a['preference_score']);

        return $ranked;
    }

    /**
     * Ambil Top-5 program studi dari hasil ranking penuh (computePreferences),
     * lengkap dengan narasi kecocokan minat dan daftar Top-5 PTN penyedia
     * jurusan tersebut (informasi, bukan bagian dari perhitungan kecocokan,
     * dan TIDAK berbasis TSK).
     */
    public function buildTop5Analysis(array $ranked, array $rawStudentScores, string $studentName): array
    {
        $top = array_slice($ranked, 0, $this->top5Count, true);

        $result = [];
        $rank = 1;

        foreach ($top as $row) {
            $major = $row['major'];
            $criteria = $major && method_exists($major, 'criteriaProfiles')
                ? $major->criteriaProfiles->first()
                : null;

            $preference = (float) ($row['preference_score'] ?? 0);

            $result[] = [
                'rank' => $rank,
                'major' => $major,
                'preference_score' => $preference,
                'score_percent' => round($preference * 100, 2),
                'narrative' => $this->buildInterestNarrative(
                    $studentName,
                    $rawStudentScores,
                    $criteria,
                    optional($major)->name
                ),
                'campuses' => $this->getCampusAvailability($major),
                'top_campuses' => $this->getTopCampusesForMajor($major),
            ];

            $rank++;
        }

        return $result;
    }

    /**
     * Menjalankan proses SAW untuk seluruh siswa dan menyimpan hasilnya.
     *
     * Perhitungan SAW (buildMajorWeightMatrix/computePreferences) TIDAK
     * DIUBAH. Yang berubah hanya bagian penentuan PTN: sebelumnya
     * final_campus_id diisi dari determineCampusByTsk($tsk) — SATU kampus
     * berdasarkan rentang skor TSK siswa. Pendekatan itu sudah dihapus.
     *
     * Sekarang final_campus_id TIDAK diisi berdasarkan TSK sama sekali
     * (disimpan null), karena penentuan PTN yang relevan untuk sebuah
     * hasil rekomendasi adalah daftar Top-5 PTN penyedia JURUSAN pada
     * baris tersebut — bukan satu kampus tunggal per siswa. Daftar
     * tersebut diambil secara dinamis saat dibutuhkan (laporan/detail)
     * melalui getTopCampusesForMajor($major), berdasarkan relasi
     * Major -> Campus, bukan disimpan sebagai kolom tunggal di sini.
     */
    public function runForAllStudents(string $academicYear): array
    {
        $majorWeightMatrix = $this->buildMajorWeightMatrix();

        $students = Student::all();
        $processedCount = 0;
        $skipped = [];

        foreach ($students as $student) {
            $completeness = $this->checkCompleteness($student);
            if (!$completeness['is_complete']) {
                $skipped[] = optional($student->user)->name ?? "ID: {$student->id}";
                continue;
            }

            $scores = $this->getStudentRiasecScores($student);
            $tsk = $this->calculateTsk($scores);
            $normalized = $this->normalizeStudentScores($scores);

            $ranked = $this->computePreferences($normalized, $scores, $majorWeightMatrix);
            $topRanked = array_slice($ranked, 0, $this->storeTopN, true);

            RecommendationResult::where('student_id', $student->id)
                ->where('academic_year', $academicYear)
                ->delete();

            $rank = 1;
            foreach ($topRanked as $majorId => $data) {
                RecommendationResult::create([
                    'student_id' => $student->id,
                    'major_id' => $majorId,
                    'academic_year' => $academicYear,
                    'preference_score' => $data['preference_score'],
                    'rank' => $rank++,
                    'tsk' => $tsk,
                    // TSK tidak lagi dipakai untuk menentukan kampus.
                    // Top-5 PTN penyedia jurusan ini diambil secara dinamis
                    // via getTopCampusesForMajor() saat data ditampilkan.
                    'final_campus_id' => null,
                ]);
            }

            $processedCount++;
        }

        return [
            'processed' => $processedCount,
            'skipped' => $skipped,
        ];
    }

    /**
     * Ringkasan RIASEC siswa untuk keperluan laporan (header profil).
     */
    public function getStudentRiasecSummary(Student $student): array
    {
        $scores = $this->getStudentRiasecScores($student);

        if (!$scores) {
            return [];
        }

        $tsk = $this->calculateTsk($scores);
        $ordered = $scores;
        arsort($ordered);
        $topKeys = array_slice(array_keys($ordered), 0, 3);

        return [
            'profile_string' => implode('', $topKeys),
            'dominant_scores' => collect($scores)->map(fn ($v, $k) => "$k: $v")->join(', '),
            'tsk' => $tsk,
        ];
    }
}