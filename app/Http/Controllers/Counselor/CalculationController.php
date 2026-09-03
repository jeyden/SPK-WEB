<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\RecommendationResult;
use App\Models\Student;
use App\Services\SawRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class CalculationController extends Controller
{
    /**
     * Mengubah logo menjadi Base64 agar aman digunakan
     * pada browser, halaman cetak, dan DomPDF.
     */
    private function getBase64Logo($rawLogoUrl)
    {
        if (empty($rawLogoUrl)) {
            return '';
        }

        $rawLogoUrl = trim((string) $rawLogoUrl);

        if (str_starts_with(strtolower($rawLogoUrl), 'data:image/')) {
            return $rawLogoUrl;
        }

        $filePath = $this->resolveLogoPath($rawLogoUrl);

        if (!$filePath || !is_file($filePath) || !is_readable($filePath)) {
            return '';
        }

        try {
            $data = file_get_contents($filePath);

            if ($data === false || $data === '') {
                return '';
            }

            $mimeType = $this->getImageMimeType($filePath);

            if (!$mimeType) {
                return '';
            }

            return 'data:' . $mimeType . ';base64,' . base64_encode($data);
        } catch (\Throwable $e) {
            return '';
        }
    }

    /**
     * Mencari lokasi fisik file logo dari berbagai format path/URL.
     */
    private function resolveLogoPath(string $logo): ?string
    {
        $logo = trim($logo);

        if ($logo === '') {
            return null;
        }

        /*
         * Jika database menyimpan URL lengkap:
         * http://localhost/uploads/logo.png
         * https://domain.com/storage/logo.png
         */
        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            $parsedPath = parse_url($logo, PHP_URL_PATH);

            if ($parsedPath) {
                $logo = $parsedPath;
            }
        }

        $logo = urldecode($logo);

        /*
         * Hilangkan query string jika ada.
         */
        $logo = preg_replace('/\?.*$/', '', $logo);

        /*
         * Normalisasi slash.
         */
        $logo = str_replace('\\', '/', $logo);

        /*
         * Jika path diawali slash, hilangkan.
         */
        $logo = ltrim($logo, '/');

        /*
         * Kandidat lokasi file.
         */
        $candidates = [];

        /*
         * 1. Jika tersimpan sebagai uploads/nama.png
         */
        $candidates[] = public_path($logo);

        /*
         * 2. Jika tersimpan sebagai storage/nama.png
         */
        if (str_starts_with($logo, 'storage/')) {
            $relativeStorage = substr($logo, 8);

            $candidates[] = public_path('storage/' . $relativeStorage);
            $candidates[] = storage_path('app/public/' . $relativeStorage);
        } else {
            /*
             * Jika hanya nama file/path biasa,
             * cek juga melalui public/storage.
             */
            $candidates[] = public_path('storage/' . $logo);
            $candidates[] = storage_path('app/public/' . $logo);
        }

        /*
         * 3. Jika path lama mengandung /public/
         */
        $publicPosition = strpos($logo, 'public/');

        if ($publicPosition !== false) {
            $afterPublic = substr($logo, $publicPosition + 7);

            if ($afterPublic !== '') {
                $candidates[] = public_path($afterPublic);
            }
        }

        /*
         * 4. Jika URL/path lama mengarah ke uploads.
         */
        if (str_starts_with($logo, 'uploads/')) {
            $candidates[] = public_path($logo);
            $candidates[] = storage_path('app/public/' . $logo);
        }

        /*
         * 5. Jika path mengandung storage/uploads.
         */
        if (str_starts_with($logo, 'storage/uploads/')) {
            $relative = substr($logo, strlen('storage/'));

            $candidates[] = public_path('storage/' . $relative);
            $candidates[] = storage_path('app/public/' . $relative);
            $candidates[] = public_path($relative);
        }

        /*
         * Hilangkan duplikasi kandidat.
         */
        $candidates = array_unique($candidates);

        foreach ($candidates as $path) {
            if (is_file($path) && is_readable($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Menentukan MIME type gambar secara akurat.
     */
    private function getImageMimeType(string $filePath): ?string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension === 'svg') {
            return 'image/svg+xml';
        }

        if (function_exists('mime_content_type')) {
            $mime = mime_content_type($filePath);

            if ($mime && str_starts_with($mime, 'image/')) {
                return $mime;
            }
        }

        return match ($extension) {
            'jpg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
            default => null,
        };
    }

    /**
     * Mengubah nilai preferensi SAW menjadi persentase.
     */
    private function convertToPercentage($preferenceScore): int
    {
        $score = (float) $preferenceScore;

        if ($score > 1) {
            $score = $score / 100;
        }

        $score = max(0, min(1, $score));

        return (int) round($score * 100);
    }

    /**
     * Status rekomendasi berdasarkan nilai preferensi.
     */
    private function getRecommendationStatus(int $scorePercent): string
    {
        if ($scorePercent >= 95) {
            return 'Sangat Direkomendasikan';
        }

        if ($scorePercent >= 90) {
            return 'Direkomendasikan';
        }

        return 'Alternatif';
    }

    /**
     * Status PTN penyedia berdasarkan posisi.
     */
    private function getCampusStatus(int $position): string
    {
        return $position <= 2
            ? 'Sangat Direkomendasikan'
            : 'Direkomendasikan';
    }

    /**
     * Keterangan TSK sebagai informasi kekuatan profil RIASEC.
     */
    private function getTskDescription(int $tsk): string
    {
        if ($tsk >= 97) {
            return 'Profil minat siswa menunjukkan kekuatan yang sangat tinggi secara keseluruhan. Kondisi ini dapat menjadi dasar yang kuat dalam mengeksplorasi berbagai bidang program studi yang sesuai.';
        }

        if ($tsk >= 73) {
            return 'Profil minat siswa menunjukkan kekuatan minat yang tinggi secara keseluruhan dan dapat menjadi salah satu pertimbangan utama dalam menentukan program studi.';
        }

        if ($tsk >= 49) {
            return 'Profil minat siswa berada pada tingkat sedang. Hasil rekomendasi dapat digunakan sebagai bahan eksplorasi sebelum menentukan pilihan program studi.';
        }

        return 'Profil minat siswa masih berada pada tingkat rendah secara keseluruhan. Siswa disarankan memperluas eksplorasi terhadap berbagai aktivitas dan bidang pembelajaran.';
    }

    /**
     * Dashboard perhitungan dan rekapitulasi.
     */
    public function index(Request $request)
    {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        $currentYear = date('Y');

        $defaultAcademicYear = $settings['academic_year']
            ?? ($currentYear . '/' . ($currentYear + 1));

        $academicYear = $request->input('academic_year') ?: $defaultAcademicYear;

        $instansi = $settings['school_name']
            ?? 'SMA At-Tajdid Boarding School';

        $logoUrl = $settings['school_logo'] ?? '';

        $saw = new SawRecommendationService();

        $studentsCompleteness = Student::with(['user'])
            ->where('academic_year', $academicYear)
            ->get()
            ->map(function ($student) use ($saw) {
                $completeness = $saw->checkCompleteness($student);

                return [
                    'student' => $student,
                    'completeness' => [
                        'complete' => $completeness['is_complete'],
                        'label' => $completeness['label'],
                    ],
                ];
            });

        $completeCount = $studentsCompleteness
            ->where('completeness.complete', true)
            ->count();

        $incompleteCount = $studentsCompleteness->count() - $completeCount;

        $rankings = RecommendationResult::with([
            'student.user',
            'major',
            'finalCampus',
        ])
            ->where('academic_year', $academicYear)
            ->orderBy('student_id')
            ->orderByDesc('preference_score')
            ->get()
            ->groupBy('student_id');

        return view(
            'counselor.calculation.index',
            compact(
                'rankings',
                'academicYear',
                'instansi',
                'logoUrl',
                'completeCount',
                'incompleteCount'
            )
        );
    }

    /**
     * Menyimpan pengaturan sistem.
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran' => 'required|string|max:20',
            'nama_instansi' => 'nullable|string|max:255',
            'letterhead_line1' => 'nullable|string|max:255',
            'letterhead_line2' => 'nullable|string|max:255',
            'letterhead_line3' => 'nullable|string|max:255',
            'letterhead_line4' => 'nullable|string|max:255',
            'letterhead_line5' => 'nullable|string|max:255',
            'logo_left_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo_left_url' => 'nullable|string|max:500',
            'logo_right_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo_right_url' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $logoLeftPath = $request->input('logo_left_url');
        $logoRightPath = $request->input('logo_right_url');

        if ($request->hasFile('logo_left_file')) {
            $logoLeftPath = $this->storeLogoFile(
                $request->file('logo_left_file'),
                'logo_left_'
            );
        }

        if ($request->hasFile('logo_right_file')) {
            $logoRightPath = $this->storeLogoFile(
                $request->file('logo_right_file'),
                'logo_right_'
            );
        }

        $settingsData = [
            'academic_year' => $request->input('tahun_ajaran'),
            'school_name' => $request->input('nama_instansi'),
            'letterhead_line1' => $request->input('letterhead_line1'),
            'letterhead_line2' => $request->input('letterhead_line2'),
            'letterhead_line3' => $request->input('letterhead_line3'),
            'letterhead_line4' => $request->input('letterhead_line4'),
            'letterhead_line5' => $request->input('letterhead_line5'),
        ];

        if (!empty($logoLeftPath)) {
            $settingsData['school_logo_left'] = $logoLeftPath;
            $settingsData['school_logo'] = $logoLeftPath;
        }

        if (!empty($logoRightPath)) {
            $settingsData['school_logo_right'] = $logoRightPath;
        }

        foreach ($settingsData as $key => $value) {
            if ($value === null) {
                continue;
            }

            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => $value,
                    'updated_at' => now(),
                ]
            );
        }

        if ($request->expectsJson()) {
            $leftPath = $settingsData['school_logo_left'] ?? $logoLeftPath ?? null;
            $rightPath = $settingsData['school_logo_right'] ?? $logoRightPath ?? null;

            return response()->json([
                'message' => 'Pengaturan berhasil disimpan.',
                'academic_year' => $settingsData['academic_year'],
                'school_name' => $settingsData['school_name'],
                'letterhead' => [
                    'line1' => $settingsData['letterhead_line1'],
                    'line2' => $settingsData['letterhead_line2'],
                    'line3' => $settingsData['letterhead_line3'],
                    'line4' => $settingsData['letterhead_line4'],
                    'line5' => $settingsData['letterhead_line5'],
                ],
                // Tambahkan asset() agar path 'uploads/...' terbaca dengan benar di frontend
                'logo_left_url' => $leftPath ? asset($leftPath) : null,
                'logo_right_url' => $rightPath ? asset($rightPath) : null,
            ]);
        }

        return back()->with(
            'success',
            'Pengaturan instansi, kop surat, dan logo berhasil disimpan!'
        );
    }

    /**
     * Menyimpan logo ke public/uploads.
     *
     * Database hanya menyimpan path relatif.
     */
    private function storeLogoFile(
        \Illuminate\Http\UploadedFile $file,
        string $prefix
    ): string {
        $directory = public_path('uploads');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $filename = $prefix
            . time()
            . '_'
            . uniqid()
            . '.'
            . $extension;

        $file->move($directory, $filename);

        return 'uploads/' . $filename;
    }

    /**
     * Mengambil pengaturan kop surat.
     *
     * Kedua logo langsung dikonversi ke Base64.
     */
    private function getLetterheadSettings(array $settings): array
    {
        $logoLeft = $settings['school_logo_left']
            ?? $settings['school_logo']
            ?? '';

        $logoRight = $settings['school_logo_right']
            ?? '';

        return [
            'line1' => $settings['letterhead_line1'] ?? '',
            'line2' => $settings['letterhead_line2'] ?? '',
            'line3' => $settings['letterhead_line3'] ?? '',
            'line4' => $settings['letterhead_line4'] ?? '',
            'line5' => $settings['letterhead_line5'] ?? '',
            'logo_left' => $this->getBase64Logo($logoLeft),
            'logo_right' => $this->getBase64Logo($logoRight),
        ];
    }

    /**
     * Logo tunggal untuk kompatibilitas view lama.
     */
    private function getLogoForPrint(array $settings): string
    {
        return $this->getBase64Logo(
            $settings['school_logo_left']
                ?? ($settings['school_logo'] ?? '')
        );
    }

    /**
     * Menambahkan nomor urut tampilan.
     */
    private function attachDisplayRank($results)
    {
        return $results->values()->map(
            function ($item, $index) {
                $item->setAttribute('display_rank', $index + 1);
                $item->setAttribute('rank', $index + 1);

                return $item;
            }
        );
    }

    /**
     * Menentukan tahun akademik.
     */
    private function resolveAcademicYear(
        Request $request,
        array $settings
    ): string {
        $currentYear = date('Y');

        $defaultAcademicYear = $settings['academic_year']
            ?? ($currentYear . '/' . ($currentYear + 1));

        return $request->input('academic_year')
            ?: $defaultAcademicYear;
    }

    /**
     * Menjalankan proses SAW seluruh siswa.
     */
    public function process(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string|max:20',
        ]);

        $academicYear = $request->academic_year;

        $saw = new SawRecommendationService();

        $summary = $saw->runForAllStudents($academicYear);

        if ($summary['processed'] === 0) {
            return back()->with(
                'error',
                'Tidak ada siswa dengan data RIASEC lengkap untuk dihitung pada tahun ajaran ini.'
            );
        }

        $message =
            'Perhitungan Metode SAW berhasil diproses untuk ' .
            $summary['processed'] .
            ' siswa.';

        if (count($summary['skipped'])) {
            $message .=
                ' Dilewati (' .
                count($summary['skipped']) .
                ' siswa): ' .
                implode(', ', $summary['skipped']) .
                '.';
        }

        return redirect()
            ->route(
                'counselor.calculation.index',
                ['academic_year' => $academicYear]
            )
            ->with('success', $message);
    }

    /**
     * Detail seluruh proses perhitungan SAW satu siswa.
     */
    public function detail(Request $request, $studentId)
    {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        $academicYear = $this->resolveAcademicYear(
            $request,
            $settings
        );

        $student = Student::with([
            'user',
            'riasecScore'
        ])
            ->where('academic_year', $academicYear)
            ->findOrFail($studentId);

        $saw = new SawRecommendationService();

        $completeness = $saw->checkCompleteness($student);

        if (!$completeness['is_complete']) {
            return redirect()
                ->route(
                    'counselor.calculation.index',
                    ['academic_year' => $academicYear]
                )
                ->with(
                    'error',
                    'Data RIASEC siswa ini belum lengkap pada tahun ajaran tersebut.'
                );
        }

        $studentName = optional($student->user)->name ?? 'Siswa';

        $scores = $saw->getStudentRiasecScores($student);
        $tsk = $saw->calculateTsk($scores);
        $tskInfo = $saw->resolveTskLevel($tsk);
        $normalized = $saw->normalizeStudentScores($scores);
        $majorWeightMatrix = $saw->buildMajorWeightMatrix();
        $ranked = $saw->computePreferences(
            $normalized,
            $scores,
            $majorWeightMatrix
        );

        $calculationRows = [];
        $rank = 1;

        foreach ($ranked as $majorId => $data) {
            $weights = $data['weights'] ?? [];
            $sawRows = $saw->buildSawRows($scores, $weights);
            $servicePreference = round(
                (float) ($data['preference_score'] ?? 0),
                4
            );

            $calculationRows[] = [
                'rank' => $rank,
                'major_id' => $majorId,
                'major' => $data['major'] ?? null,
                'raw' => $scores,
                'normalized' => $normalized,
                'weights' => $weights,
                'multiplication' => array_map(
                    fn ($row) => round($row['contribution'], 4),
                    $sawRows['rows']
                ),
                'preference_score' => $servicePreference,
                'preference_calculated' => round(
                    $sawRows['preference_score'],
                    4
                ),
                'score_percent' => round(
                    $servicePreference * 100,
                    2
                ),
                'top_dimensions' => $data['top_dimensions'] ?? [],
            ];

            $rank++;
        }

        $alternativeCount = count($calculationRows);

        $riasecSummary = array_merge($scores, [
            'tsk' => $tsk,
            'tsk_level' => $tskInfo['level'],
            'tsk_description' => $tskInfo['description'],
            'tsk_class' => $tskInfo['class'],
        ]);

        return view(
            'counselor.calculation.detail',
            compact(
                'student',
                'studentName',
                'academicYear',
                'scores',
                'tsk',
                'tskInfo',
                'normalized',
                'majorWeightMatrix',
                'ranked',
                'calculationRows',
                'alternativeCount',
                'riasecSummary'
            )
        );
    }

    /**
     * Laporan rekomendasi lengkap siswa.
     */
    public function report(Request $request, $studentId)
    {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        $academicYear = $this->resolveAcademicYear(
            $request,
            $settings
        );

        $student = Student::with([
            'user',
            'riasecScore'
        ])
            ->where('academic_year', $academicYear)
            ->findOrFail($studentId);

        $saw = new SawRecommendationService();

        $completeness = $saw->checkCompleteness($student);

        if (!$completeness['is_complete']) {
            return redirect()
                ->route(
                    'counselor.calculation.index',
                    ['academic_year' => $academicYear]
                )
                ->with(
                    'error',
                    'Data RIASEC siswa ini belum lengkap pada tahun ajaran tersebut.'
                );
        }

        $rawResults = RecommendationResult::with([
            'major.fieldOfStudy.parent',
            'major.campuses',
            'major.criteriaProfiles',
            'finalCampus',
        ])
            ->where('student_id', $studentId)
            ->where('academic_year', $academicYear)
            ->orderByDesc('preference_score')
            ->get();

        $rawResults = $this->attachDisplayRank($rawResults);

        $studentScores = $saw->getStudentRiasecScores($student);
        $mainResult = $rawResults->first();

        $tsk = optional($mainResult)->tsk
            ?? $saw->calculateTsk($studentScores);

        $tskInfo = $saw->resolveTskLevel((float) $tsk);

        $riasecItems = $saw
            ->buildRiasecPresentation($studentScores)['items'];

        $finalCampusOptions = $mainResult
            ? $saw->getTopCampusesForMajor($mainResult->major)
            : [];

        $finalCampus = $finalCampusOptions[0]['campus'] ?? null;

        $results = $rawResults->map(
            function ($item) use ($saw, $studentScores) {
                $rankNumber = (int) (
                    $item->display_rank ?? 0
                );

                $scorePercent = $saw->toScorePercent(
                    $item->preference_score
                );

                $statusInfo = $saw->resolveScoreStatus(
                    $scorePercent
                );

                $major = $item->major;

                $majorName = trim(
                    (($major->degree ?? '')
                        ? $major->degree . ' '
                        : '')
                    . ($major->name ?? 'Program Studi')
                );

                $criteria = $major?->criteriaProfiles?->first();
                $majorWeights = $saw->criteriaToArray($criteria);

                $analysisText =
                    $item->interest_analysis
                    ?? $item->analysis_text
                    ?? $saw->buildInterestAnalysis(
                        $studentScores,
                        $majorWeights,
                        $majorName
                    );

                $fieldOfStudy = optional($major)->fieldOfStudy;
                $parent = optional($fieldOfStudy)->parent;

                if ($parent) {
                    $rumpun = $parent->name;
                    $subIlmu = optional($fieldOfStudy)->name ?? '-';
                } else {
                    $rumpun = optional($fieldOfStudy)->name ?? '-';
                    $subIlmu = '-';
                }

                $campuses = collect(
                    $saw->getTopCampusesForMajor($major)
                )->map(function ($row) {
                    $position = $row['position'];

                    return [
                        'campus' => $row['campus'],
                        'accreditation' => $row['accreditation'],
                        'weight_score' => $row['weight_score'],
                        'position' => $position,
                        'status' => $this->getCampusStatus($position),
                        'label' => 'Tersedia (Kuota: '
                            . ($row['quota'] ?? '-')
                            . ')',
                    ];
                });

                return [
                    'rank' => $rankNumber,
                    'major' => $major,
                    'major_name' => $majorName,
                    'rumpun' => $rumpun,
                    'sub_ilmu' => $subIlmu,
                    'score_percent' => $scorePercent,
                    'score_percent_display' => number_format(
                        $scorePercent,
                        2,
                        ',',
                        '.'
                    ),
                    'status' => $statusInfo['status'],
                    'status_class' => $statusInfo['class'],
                    'analysis_text' => $analysisText,
                    'campuses' => $campuses,
                ];
            }
        )->values();

        $instansi = $settings['school_name']
            ?? 'SMA At-Tajdid Boarding School';

        /*
         * PENTING:
         * Kop surat mengambil kedua logo dari database,
         * kemudian mengubahnya menjadi Base64.
         */
        $letterhead = $this->getLetterheadSettings($settings);

        /*
         * Tetap disediakan untuk kompatibilitas view lama.
         */
        $logoUrl = $this->getLogoForPrint($settings);

        $counselor = auth()->user();

        return view(
            'counselor.calculation.report',
            compact(
                'student',
                'results',
                'academicYear',
                'riasecItems',
                'tsk',
                'tskInfo',
                'mainResult',
                'finalCampus',
                'instansi',
                'logoUrl',
                'letterhead',
                'counselor'
            )
        );
    }

    /**
     * Cetak laporan satu siswa.
     */
   public function printSingle(
        Request $request,
        $studentId
    ) {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        $academicYear = $this->resolveAcademicYear(
            $request,
            $settings
        );

        $student = Student::with(['user'])
            ->where('academic_year', $academicYear)
            ->findOrFail($studentId);

        $results = RecommendationResult::with([
            'major.fieldOfStudy.parent',
            'major.campuses',
            'finalCampus',
        ])
            ->where('student_id', $studentId)
            ->where('academic_year', $academicYear)
            ->orderByDesc('preference_score')
            ->get();

        $results = $this->attachDisplayRank($results);

        $instansi = $settings['school_name']
            ?? 'SMA At-Tajdid Boarding School';

        $letterhead = $this->getLetterheadSettings($settings);
        
        // Definisikan variabel eksplisit agar cocok dengan view Blade
        $logoLeftUrl = $letterhead['logo_left'];
        $logoRightUrl = $letterhead['logo_right'];
        $logoUrl = $this->getLogoForPrint($settings);

        $counselor = auth()->user();

        return view(
            'counselor.calculation.print-single',
            compact(
                'student',
                'results',
                'academicYear',
                'instansi',
                'letterhead',
                'logoLeftUrl',
                'logoRightUrl',
                'logoUrl',
                'counselor'
            )
        );
    }
    /**
     * Export PDF satu siswa.
     */
    public function exportPdfSingle($studentId)
    {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        $academicYear = $this->resolveAcademicYear(
            request(),
            $settings
        );

        $student = Student::with(['user'])
            ->where('academic_year', $academicYear)
            ->findOrFail($studentId);

        $results = RecommendationResult::with([
            'major.fieldOfStudy.parent',
            'major.campuses',
            'finalCampus',
        ])
            ->where('student_id', $studentId)
            ->where('academic_year', $academicYear)
            ->orderByDesc('preference_score')
            ->get();

        $results = $this->attachDisplayRank($results);

        $instansi = $settings['school_name']
             ?? 'SMA At-Tajdid Boarding School';

        $letterhead = $this->getLetterheadSettings($settings);
        
        // Definisikan variabel eksplisit agar cocok dengan view Blade
        $logoLeftUrl = $letterhead['logo_left'];
        $logoRightUrl = $letterhead['logo_right'];
        $logoUrl = $this->getLogoForPrint($settings);
        
        $counselor = auth()->user();

        $pdf = Pdf::loadView(
            'counselor.calculation.print-single',
            compact(
                'student',
                'results',
                'academicYear',
                'instansi',
                'letterhead',
                'logoLeftUrl',
                'logoRightUrl',
                'logoUrl',
                'counselor'
            )
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'Laporan-Rekomendasi-'
            . optional($student->user)->name
            . '.pdf'
        );
    }
   /**
     * Cetak seluruh rekapitulasi siswa.
     *
     * Kedua logo kop surat dikirim sebagai Base64.
     */
    public function printAll(Request $request)
    {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        $academicYear = $this->resolveAcademicYear(
            $request,
            $settings
        );

        $instansi = $settings['school_name']
            ?? 'SMA At-Tajdid Boarding School';

        /*
         * PENTING:
         * Jangan menggunakan URL logo langsung.
         * getLetterheadSettings() akan membaca file fisik
         * dan mengubahnya menjadi Base64.
         */
        $letterhead = $this->getLetterheadSettings($settings);

        // Definisikan variabel eksplisit agar cocok dengan view Blade
        $logoLeftUrl = $letterhead['logo_left'] ?? null;
        $logoRightUrl = $letterhead['logo_right'] ?? null;

        /*
         * Kompatibilitas dengan view print-all lama.
         */
        $logoUrl = $this->getLogoForPrint($settings);

        $rankings = RecommendationResult::with([
            'student.user',
            'major.fieldOfStudy.parent',
            'major.campuses',
            'finalCampus',
        ])
            ->where('academic_year', $academicYear)
            ->orderBy('student_id')
            ->orderByDesc('preference_score')
            ->get()
            ->groupBy('student_id')
            ->map(function ($studentResults) {
                return $this->attachDisplayRank(
                    $studentResults
                );
            });

        $counselor = auth()->user();

        return view(
            'counselor.calculation.print-all',
            compact(
                'rankings',
                'academicYear',
                'instansi',
                'letterhead',
                'logoLeftUrl',
                'logoRightUrl',
                'logoUrl',
                'counselor'
            )
        );
    }

    /**
     * Export PDF seluruh rekapitulasi siswa.
     *
     * Logo kiri dan kanan diproses menjadi Base64
     * sebelum diberikan kepada DomPDF.
     */
    public function exportPdfAll(Request $request)
    {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        $academicYear = $this->resolveAcademicYear(
            $request,
            $settings
        );

        $instansi = $settings['school_name']
            ?? 'SMA At-Tajdid Boarding School';

        /*
         * Ambil kop surat beserta kedua logo.
         * Tidak menggunakan asset() atau URL HTTP.
         */
        $letterhead = $this->getLetterheadSettings($settings);

        // Definisikan variabel eksplisit agar cocok dengan view Blade
        $logoLeftUrl = $letterhead['logo_left'] ?? null;
        $logoRightUrl = $letterhead['logo_right'] ?? null;

        /*
         * Kompatibilitas dengan view lama.
         */
        $logoUrl = $this->getLogoForPrint($settings);

        $rankings = RecommendationResult::with([
            'student.user',
            'major.fieldOfStudy.parent',
            'major.campuses',
            'finalCampus',
        ])
            ->where('academic_year', $academicYear)
            ->orderBy('student_id')
            ->orderByDesc('preference_score')
            ->get()
            ->groupBy('student_id')
            ->map(function ($studentResults) {
                return $this->attachDisplayRank(
                    $studentResults
                );
            });

        $counselor = auth()->user();

        $pdf = Pdf::loadView(
            'counselor.calculation.print-all',
            compact(
                'rankings',
                'academicYear',
                'instansi',
                'letterhead',
                'logoLeftUrl',
                'logoRightUrl',
                'logoUrl',
                'counselor'
            )
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'Rekapitulasi-Rekomendasi-Siswa-'
            . str_replace('/', '-', $academicYear)
            . '.pdf'
        );
    }
}