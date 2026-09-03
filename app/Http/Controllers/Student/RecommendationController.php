<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\RecommendationResult;
use App\Models\RiasecScore;
use App\Services\SawRecommendationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    protected SawRecommendationService $sawService;

    public function __construct(SawRecommendationService $sawService)
    {
        $this->sawService = $sawService;
    }

    protected function getAcademicYear($student): string
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();

        return $settings['academic_year']
            ?? $student->academic_year
            ?? (date('Y') . '/' . (date('Y') + 1));
    }

    protected function getStudentRiasec($student): array
    {
        $riasec = RiasecScore::where('student_id', $student->id)->first();

        if (!$riasec) {
            return ['scores' => null, 'dominant_code' => null, 'tsk' => null];
        }

        $scores = [
            'R' => (float) ($riasec->r_score ?? 0),
            'I' => (float) ($riasec->i_score ?? 0),
            'A' => (float) ($riasec->a_score ?? 0),
            'S' => (float) ($riasec->s_score ?? 0),
            'E' => (float) ($riasec->e_score ?? 0),
            'C' => (float) ($riasec->c_score ?? 0),
        ];

        // Menggunakan service untuk menentukan top RIASEC codes
        $topCodes = $this->sawService->topRiasecCodes($scores, 3);

        return [
            'scores' => $scores,
            'dominant_code' => implode('', $topCodes),
            'tsk' => $riasec->tsk ?? $this->sawService->calculateTsk($scores),
        ];
    }

    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return view('student.recommendations.index', [
                'student' => null,
                'recommendations' => collect(),
                'academicYear' => null,
                'studentRiasec' => null,
                'studentDominantCode' => null,
                'tsk' => null,
            ]);
        }

        $academicYear = $this->getAcademicYear($student);
        $riasecData = $this->getStudentRiasec($student);

        $recommendations = RecommendationResult::with(['major.fieldOfStudy.parent'])
            ->where('student_id', $student->id)
            ->where('academic_year', $academicYear)
            ->orderBy('rank', 'asc')
            ->take(199)
            ->get();

        $recommendations->transform(function ($recommendation) {
            $major = $recommendation->major;
            $fieldOfStudy = optional($major)->fieldOfStudy;
            $parentField = optional($fieldOfStudy)->parent;

            $recommendation->rumpun = $parentField ? $parentField->name : ($fieldOfStudy->name ?? '-');
            $recommendation->subIlmu = $parentField ? ($fieldOfStudy->name ?? '-') : '-';
            // Menggunakan service untuk konversi persentase skor
            $recommendation->scorePercent = $this->sawService->toScorePercent($recommendation->preference_score);

            return $recommendation;
        });

        return view('student.recommendations.index', [
            'student' => $student,
            'recommendations' => $recommendations,
            'academicYear' => $academicYear,
            'studentRiasec' => $riasecData['scores'],
            'studentDominantCode' => $riasecData['dominant_code'],
            'tsk' => $riasecData['tsk'],
        ]);
    }

    /**
     * Detail satu rekomendasi — Seluruh data matang
     * diproses terpusat melalui SawRecommendationService.
     */
    public function detail($recommendationId)
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(403);
        }

        $academicYear = $this->getAcademicYear($student);
        $riasecData = $this->getStudentRiasec($student);

        $recommendation = RecommendationResult::with([
            'major.fieldOfStudy.parent',
            'major.campuses',
            'major.criteriaProfiles',
            'finalCampus',
        ])
            ->where('id', $recommendationId)
            ->where('student_id', $student->id)
            ->where('academic_year', $academicYear)
            ->firstOrFail();

        $studentScores = $riasecData['scores'] ?? [];
        $tsk = (float) ($riasecData['tsk'] ?? 0);
        
        // Memanfaatkan service untuk resolusi TSK dan status skor
        $tskInfo = $this->sawService->resolveTskLevel($tsk);

        $major = $recommendation->major;
        $criteria = $major?->criteriaProfiles?->first();
        
        // Menggunakan criteriaToArray() dari service
        $majorWeights = $this->sawService->criteriaToArray($criteria);

        $riasecPresentation = $this->sawService->buildRiasecPresentation($studentScores, $majorWeights, 1.0);
        $sawData = $this->sawService->buildSawRows($studentScores, $majorWeights);

        $scorePercent = $this->sawService->toScorePercent($recommendation->preference_score);
        $statusInfo = $this->sawService->resolveScoreStatus($scorePercent);

        $majorName = trim(($major->degree ?? '') . ' ' . ($major->name ?? 'Program Studi'));

        $analysisText = $recommendation->interest_analysis
            ?? $recommendation->analysis_text
            ?? $this->sawService->buildInterestAnalysis($studentScores, $majorWeights, $majorName);

        $fieldOfStudy = optional($major)->fieldOfStudy;
        $parentField = optional($fieldOfStudy)->parent;

        $rumpun = $parentField ? $parentField->name : (optional($fieldOfStudy)->name ?? '-');
        $subIlmu = $parentField ? (optional($fieldOfStudy)->name ?? '-') : '-';

        $campuses = collect();

        if ($major && method_exists($major, 'campuses')) {
            $campuses = $major->campuses
                ->sortByDesc(fn ($campus) => (float) ($campus->pivot->weight_score ?? 0))
                ->take(5)
                ->values();
        }

        return view('student.recommendations.detail-recommendation', [
            'student' => $student,
            'recommendation' => $recommendation,
            'major' => $major,
            'majorName' => $majorName,
            'rumpun' => $rumpun,
            'subIlmu' => $subIlmu,
            'academicYear' => $academicYear,
            'tsk' => $tsk,
            'tskInfo' => $tskInfo,
            'scorePercent' => $scorePercent,
            'scorePercentDisplay' => number_format($scorePercent, 2),
            'statusInfo' => $statusInfo,
            'analysisText' => $analysisText,
            'riasecPresentation' => $riasecPresentation,
            'sawData' => $sawData,
            'campuses' => $campuses,
        ]);
    }
}