<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\RecommendationResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    /**
     * Urutan tetap 6 dimensi RIASEC beserta nama singkatnya.
     */
    private const RIASEC_META = [
        'R' => 'Realistic',
        'I' => 'Investigative',
        'A' => 'Artistic',
        'S' => 'Social',
        'E' => 'Enterprising',
        'C' => 'Conventional',
    ];

    public function index()
    {
        $user = Auth::user();
        $student = $user?->student;

        if (!$student) {
            return view('student.dashboard', $this->emptyDashboardData());
        }

        $academicYear = $student->academic_year
            ?? DB::table('settings')->where('key', 'academic_year')->value('value')
            ?? (date('Y') . '/' . (date('Y') + 1));

        // ------------------------------------------------------------
        // RIASEC
        // ------------------------------------------------------------
        $riasec = $student->riasecScore;
        $riasecCompleted = $riasec !== null;

        $riasecData = $this->buildRiasecData($riasec);
        $dominantRiasec = collect($riasecData)->sortByDesc('score')->values();
        $dominantCode = $dominantRiasec->take(3)->pluck('code')->implode('');

        // ------------------------------------------------------------
        // Rekomendasi — ambil semua jurusan terurut berdasarkan skor preferensi tertinggi
        // ------------------------------------------------------------
        $recommendationQuery = RecommendationResult::with('major')
            ->where('student_id', $student->id)
            ->where(function ($query) use ($academicYear) {
                $query->where('academic_year', $academicYear)
                    ->orWhereNull('academic_year');
            })
            ->orderBy('preference_score', 'desc');

        $allRecommendationsRaw = $recommendationQuery->get();
        $recommendationCompleted = $allRecommendationsRaw->isNotEmpty();

       // ... di dalam method index() ...

$recommendationsList = collect();
if ($recommendationCompleted) {
    $recommendationsList = $allRecommendationsRaw->map(function ($item) {
        $major = $item->major;
        
        // MENGHAPUS ROUND DAN CASTING KE INT:
        // Kita simpan nilai asli (misal 0.854321) agar presisi
        $rawScore = (float) ($item->preference_score ?? 0);

        return [
            'id' => $item->id,
            'rank' => (int) ($item->rank ?? 1),
            'major_name' => $major?->name ?? 'Jurusan Tidak Diketahui',
            'degree' => $major?->degree ?? '',
            'score' => $rawScore, // Nilai asli tanpa pembulatan
        ];
    });
}
        // Ambil data untuk ditampilkan di dashboard (maksimal 3 teratas)
        // Jika Anda ingin menampilkan yang terbaik tanpa harus difilter kaku 100%:
        $perfectRecommendations = $recommendationsList->take(3);

        // ------------------------------------------------------------
        // Profil & tahapan
        // ------------------------------------------------------------
        $profileCompleted = (bool) (
            $student->profile_completed ||
            (
                !empty($student->nisn) &&
                !empty($student->class) &&
                !empty($student->academic_year) &&
                !empty($student->high_school_major)
            )
        );

        $profileRoute = $this->getRoute(['student.profile.index', 'student.profile', 'student.profile.edit']);
        $riasecRoute = $this->getRoute(['student.riasec.index']);
        $recommendationRoute = $this->getRoute(['student.recommendations.index']);

        $steps = [
            [
                'key' => 'profile',
                'title' => 'Profil Akademik',
                'description' => 'Lengkapi informasi akademik dan data diri Anda.',
                'completed' => $profileCompleted,
                'route' => $profileRoute,
                'icon' => 'fa-user-graduate',
            ],
            [
                'key' => 'riasec',
                'title' => 'Tes Minat RIASEC',
                'description' => '60 pertanyaan minat berdasarkan enam kategori RIASEC.',
                'completed' => $riasecCompleted,
                'route' => $riasecRoute,
                'icon' => 'fa-brain',
            ],
            [
                'key' => 'result',
                'title' => 'Hasil Analisis',
                'description' => 'Profil minat dan rekomendasi jurusan berdasarkan hasil analisis.',
                'completed' => $recommendationCompleted,
                'route' => $recommendationRoute,
                'icon' => 'fa-chart-line',
            ],
        ];

        $completedSteps = collect($steps)->where('completed', true)->count();
        $totalSteps = count($steps);
        $progress = $totalSteps > 0 ? (int) round(($completedSteps / $totalSteps) * 100) : 0;

        $nextStep = collect($steps)->first(fn ($step) => !$step['completed']);
        $allStepsCompleted = $nextStep === null;

        $heroSubtitle = $allStepsCompleted
            ? 'Semua tahapan sudah Anda selesaikan. Diskusikan hasil rekomendasi bersama Guru BK untuk memantapkan pilihan jurusan Anda.'
            : 'Lengkapi profil, kerjakan asesmen minat, dan lihat hasil analisis untuk mendapatkan rekomendasi jurusan yang sesuai.';

        // ------------------------------------------------------------
        // Sapaan dinamis & artikel
        // ------------------------------------------------------------
        $greeting = $this->buildGreeting();

        $articlesCount = Article::query()
            ->whereNotNull('published_at')
            ->count();

        $articles = Article::query()
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('student.dashboard', [
            'student' => $student,
            'user' => $user,
            'academicYear' => $academicYear,
            'greeting' => $greeting,
            'heroSubtitle' => $heroSubtitle,
            'riasecCompleted' => $riasecCompleted,
            'recommendationCompleted' => $recommendationCompleted,
            'completedSteps' => $completedSteps,
            'totalSteps' => $totalSteps,
            'progress' => $progress,
            'steps' => $steps,
            'nextStep' => $nextStep,
            'allStepsCompleted' => $allStepsCompleted,
            'riasec' => $riasec,
            'riasecData' => $riasecData,
            'dominantRiasec' => $dominantRiasec,
            'dominantCode' => $dominantCode,
            'recommendationsList' => $recommendationsList,
            'perfectRecommendations' => $perfectRecommendations,
            'articlesCount' => $articlesCount,
            'articles' => $articles,
        ]);
    }

    /**
     * Susun 6 dimensi RIASEC (urutan tetap R-I-A-S-E-C) menjadi
     * ['code'=>, 'name'=>, 'score'=>].
     */
    private function buildRiasecData($riasec): array
    {
        $data = [];

        foreach (self::RIASEC_META as $code => $name) {
            $column = strtolower($code) . '_score';

            $data[] = [
                'code' => $code,
                'name' => $name,
                'score' => (int) ($riasec?->{$column} ?? 0),
            ];
        }

        return $data;
    }

    /**
     * Sapaan ramah berdasarkan jam saat ini.
     */
    private function buildGreeting(): array
    {
        $hour = (int) now()->format('H');

        return match (true) {
            $hour < 11 => ['text' => 'Selamat Pagi', 'icon' => 'fa-sun'],
            $hour < 15 => ['text' => 'Selamat Siang', 'icon' => 'fa-cloud-sun'],
            $hour < 18 => ['text' => 'Selamat Sore', 'icon' => 'fa-cloud-sun-rain'],
            default => ['text' => 'Selamat Datang', 'icon' => 'fa-moon'],
        };
    }

    private function getRoute(array $names): string
    {
        foreach ($names as $name) {
            if (Route::has($name)) {
                try {
                    return route($name);
                } catch (\Throwable $e) {
                    return '#';
                }
            }
        }

        return '#';
    }

    private function emptyDashboardData(): array
    {
        $riasecData = $this->buildRiasecData(null);
        $dominantRiasec = collect($riasecData)->sortByDesc('score')->values();

        $steps = [
            [
                'key' => 'profile',
                'title' => 'Profil Akademik',
                'description' => 'Lengkapi informasi akademik dan data diri Anda.',
                'completed' => false,
                'route' => '#',
                'icon' => 'fa-user-graduate',
            ],
            [
                'key' => 'riasec',
                'title' => 'Tes Minat RIASEC',
                'description' => '60 pertanyaan minat berdasarkan enam kategori RIASEC.',
                'completed' => false,
                'route' => '#',
                'icon' => 'fa-brain',
            ],
            [
                'key' => 'result',
                'title' => 'Hasil Analisis',
                'description' => 'Profil minat dan rekomendasi jurusan berdasarkan hasil analisis.',
                'completed' => false,
                'route' => '#',
                'icon' => 'fa-chart-line',
            ],
        ];

        return [
            'student' => null,
            'user' => Auth::user(),
            'academicYear' => date('Y') . '/' . (date('Y') + 1),
            'greeting' => $this->buildGreeting(),
            'heroSubtitle' => 'Lengkapi profil, kerjakan asesmen minat, dan lihat hasil analisis untuk mendapatkan rekomendasi jurusan yang sesuai.',
            'riasecCompleted' => false,
            'recommendationCompleted' => false,
            'completedSteps' => 0,
            'totalSteps' => 3,
            'progress' => 0,
            'steps' => $steps,
            'nextStep' => $steps[0],
            'allStepsCompleted' => false,
            'riasec' => null,
            'riasecData' => $riasecData,
            'dominantRiasec' => $dominantRiasec,
            'dominantCode' => '',
            'mainRecommendation' => null,
            'articlesCount' => 0,
            'articles' => collect(),
        ];
    }
}