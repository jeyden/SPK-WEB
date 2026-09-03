<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\RiasecScore;
use App\Models\RecommendationResult;
use App\Services\SawRecommendationService;
use Illuminate\Http\Request;

class StudentAssessmentController extends Controller
{
    /**
     * Mendapatkan default tahun akademik.
     * Contoh: 2026/2027
     */
    private function getDefaultAcademicYear(): string
    {
        $currentYear = (int) date('Y');
        $baseYear = $currentYear < 2024 ? 2024 : $currentYear;

        return $baseYear . '/' . ($baseYear + 1);
    }

    /**
     * Dashboard monitoring asesmen siswa oleh Guru BK.
     *
     * Tidak lagi menggunakan:
     * - Nilai akademik
     * - Nilai mata pelajaran
     * - Jurusan sekolah sebagai kriteria SAW
     *
     * Fokus:
     * - Status asesmen RIASEC
     * - Skor R, I, A, S, E, C
     * - TSK
     * - Status rekomendasi
     */
    public function index(
        Request $request,
        SawRecommendationService $saw
    ) {
        $defaultAcademicYear = $this->getDefaultAcademicYear();

        $academicYear = $request->get(
            'academic_year',
            $defaultAcademicYear
        );

        $students = Student::with([
            'user',
            'riasecScore',
        ])
            ->where('academic_year', $academicYear)
            ->orderBy('id')
            ->get();

        foreach ($students as $student) {

            /*
             * Status asesmen berdasarkan keberadaan
             * skor RIASEC siswa.
             */
            $student->assessment_status = $student->riasecScore
                ? 'completed'
                : 'not_completed';

            /*
             * Cek kelengkapan berdasarkan sistem
             * rekomendasi terbaru.
             *
             * Method checkCompleteness() pada
             * SawRecommendationService nantinya
             * harus hanya mengecek data RIASEC,
             * bukan nilai akademik atau jurusan sekolah.
             */
            $student->completeness = $saw->checkCompleteness(
                $student,
                $academicYear
            );

            /*
             * Ambil hasil rekomendasi terbaru siswa.
             *
             * Jika belum ada hasil, nilainya null.
             */
            $student->recommendation = RecommendationResult::with([
                'major',
                'finalCampus',
            ])
                ->where('student_id', $student->id)
                ->where('academic_year', $academicYear)
                ->latest()
                ->first();

            $student->recommendation_status =
                $student->recommendation
                    ? 'completed'
                    : 'not_completed';
        }

        return view(
            'counselor.assessments.index',
            compact(
                'students',
                'academicYear'
            )
        );
    }

    /**
     * Menampilkan detail hasil asesmen RIASEC siswa.
     *
     * Guru BK tidak lagi menginput nilai akademik.
     */
    public function assess(
        Student $student,
        Request $request
    ) {
        $defaultAcademicYear = $this->getDefaultAcademicYear();

        $academicYear = $request->get(
            'academic_year',
            $defaultAcademicYear
        );

        $student->load([
            'user',
            'riasecScore',
        ]);

        /*
         * Ambil hasil rekomendasi siswa pada
         * tahun akademik yang dipilih.
         */
        $recommendation = RecommendationResult::with([
            'major',
            'finalCampus',
        ])
            ->where('student_id', $student->id)
            ->where('academic_year', $academicYear)
            ->latest()
            ->first();

        /*
         * Data skor RIASEC.
         */
        $score = $student->riasecScore;

        return view(
            'counselor.assessments.assess',
            compact(
                'student',
                'academicYear',
                'score',
                'recommendation'
            )
        );
    }

    /**
     * Menampilkan detail asesmen siswa.
     *
     * Method ini dapat digunakan apabila ingin
     * memisahkan halaman monitoring dan detail.
     */
    public function show(
        Student $student,
        Request $request
    ) {
        $defaultAcademicYear = $this->getDefaultAcademicYear();

        $academicYear = $request->get(
            'academic_year',
            $defaultAcademicYear
        );

        $student->load([
            'user',
            'riasecScore',
        ]);

        $recommendation = RecommendationResult::with([
            'major',
            'finalCampus',
        ])
            ->where('student_id', $student->id)
            ->where('academic_year', $academicYear)
            ->latest()
            ->first();

        return view(
            'counselor.assessments.show',
            compact(
                'student',
                'academicYear',
                'recommendation'
            )
        );
    }
}