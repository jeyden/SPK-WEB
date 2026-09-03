<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Campus;
use App\Models\RecommendationResult;
use App\Models\RegistrationPeriod;
use App\Models\RiasecScore;
use App\Models\Student;
use App\Models\Major;

class DashboardController extends Controller
{
    /**
     * Nama lengkap dari setiap kode dimensi RIASEC.
     * Dipakai di seluruh dashboard (stat, chart distribusi, dan tabel aktivitas).
     */
    private const RIASEC_DIMENSIONS = [
        'R' => 'Realistic',
        'I' => 'Investigative',
        'A' => 'Artistic',
        'S' => 'Social',
        'E' => 'Enterprising',
        'C' => 'Conventional',
    ];

    public function index()
    {
        // 1. Sapaan waktu otomatis
        $greeting = $this->buildGreeting();

        // 2. Periode pendaftaran aktif (otomatis menutup periode yang sudah lewat)
        $activePeriod = RegistrationPeriod::current();

        // 3. Statistik Partisipasi Asesmen
        $totalSiswa = Student::count();
        $sudahAsesmen = Student::whereHas('riasecScore')->count();
        $belumAsesmen = max(0, $totalSiswa - $sudahAsesmen);
        $persenPartisipasi = $totalSiswa > 0
            ? round(($sudahAsesmen / $totalSiswa) * 100)
            : 0;

        // 4. Parameter & Kriteria SAW — 6 dimensi RIASEC sebagai kriteria keputusan
        $totalKriteria = count(self::RIASEC_DIMENSIONS);
        $totalJurusanBerbobot = Major::has('criteria')->count();

       // 5. Hitung siswa yang sudah asesmen TAPI belum memiliki hasil rekomendasi SAW
$belumDiproses = Student::whereHas('riasecScore')
    ->whereNotIn('id', function($query) {
        $query->select('student_id')->from('recommendation_results');
    })
    ->count();

// 6. Total rekomendasi yang sudah tersedia
$totalRekomendasi = RecommendationResult::distinct('student_id')->count('student_id');

// Status selesai jika ada yang sudah asesmen dan tidak ada lagi yang tertinggal belum diproses
$sawSelesaiSemua = $sudahAsesmen > 0 && $belumDiproses === 0;

        // 7. Kartu statistik utama
        $statCards = [
            [
                'label' => 'Total Siswa & Partisipasi',
                'value' => $totalSiswa,
                'suffix' => 'Siswa',
                'sub' => "{$sudahAsesmen} siswa telah menyelesaikan tes RIASEC ({$persenPartisipasi}%)",
                'icon' => 'fa-users',
                'accent' => 'indigo',
            ],
            [
                'label' => 'Periode Pendaftaran Aktif',
                'value' => $activePeriod->academic_year ?? 'Belum diatur',
                'suffix' => null,
                'sub' => $activePeriod ? $activePeriod->statusLabel() : 'Belum ada periode berjalan',
                'icon' => 'fa-calendar-check',
                'accent' => $activePeriod && $activePeriod->isOpen() ? 'emerald' : 'amber',
                'badgeClass' => $activePeriod?->statusBadgeClass(),
            ],
            [
                'label' => 'Parameter & Kriteria SAW',
                'value' => $totalKriteria,
                'suffix' => 'Kriteria',
                'sub' => "Bobot RIASEC aktif pada {$totalJurusanBerbobot} profil jurusan",
                'icon' => 'fa-scale-balanced',
                'accent' => 'violet',
            ],
            [
                'label' => 'Rekomendasi Tersedia',
                'value' => $totalRekomendasi,
                'suffix' => 'Siswa',
                'sub' => $belumDiproses > 0
                    ? "{$belumDiproses} siswa menunggu proses perhitungan"
                    : 'Seluruh hasil siap divalidasi',
                'icon' => 'fa-ranking-star',
                'accent' => 'fuchsia',
            ],
        ];

        // 8. Distribusi rumpun minat RIASEC (rata-rata skor seluruh siswa yang sudah asesmen)
        $riasecDistribution = $this->buildRiasecDistribution();

        // 9. Donut progres asesmen (Selesai vs Belum Mengerjakan)
        $assessmentDonut = [
            'completed' => $sudahAsesmen,
            'pending' => $belumAsesmen,
            'percent' => $persenPartisipasi,
        ];

        // 10. Tabel aktivitas: aktivitas & hasil asesmen siswa terbaru
        $recentActivities = $this->buildRecentActivities();

        // Artikel & referensi kampus
        $articles = Article::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->with('author')
            ->take(2)
            ->get();

        $campuses = Campus::latest()->take(3)->get();

        return view('counselor.dashboard', compact(
            'greeting',
            'activePeriod',
            'statCards',
            'riasecDistribution',
            'assessmentDonut',
            'recentActivities',
            'sawSelesaiSemua',
            'belumDiproses',
            'sudahAsesmen',
            'totalSiswa',
            'articles',
            'campuses'
        ));
    }

    /**
     * Hitung rata-rata skor tiap dimensi RIASEC dari seluruh siswa yang sudah
     * mengerjakan asesmen, lalu susun untuk chart distribusi rumpun minat.
     */
    private function buildRiasecDistribution(): array
    {
        $averages = RiasecScore::selectRaw('
                AVG(r_score) as r,
                AVG(i_score) as i,
                AVG(a_score) as a,
                AVG(s_score) as s,
                AVG(e_score) as e,
                AVG(c_score) as c
            ')
            ->first();

        $raw = [
            'R' => round((float) ($averages->r ?? 0), 1),
            'I' => round((float) ($averages->i ?? 0), 1),
            'A' => round((float) ($averages->a ?? 0), 1),
            'S' => round((float) ($averages->s ?? 0), 1),
            'E' => round((float) ($averages->e ?? 0), 1),
            'C' => round((float) ($averages->c ?? 0), 1),
        ];

        $max = max($raw) ?: 1;

        $distribution = [];
        foreach (self::RIASEC_DIMENSIONS as $code => $name) {
            $score = $raw[$code];
            $distribution[] = [
                'code' => $code,
                'name' => $name,
                'score' => $score,
                'percent' => $max > 0
                    ? max(6, min(100, round(($score / $max) * 100)))
                    : 0,
            ];
        }

        // Urutkan dari rumpun minat paling dominan agar tren mudah dibaca.
        usort($distribution, fn ($a, $b) => $b['score'] <=> $a['score']);

        return $distribution;
    }

    /**
     * Susun daftar aktivitas & hasil asesmen siswa terbaru untuk tabel utama.
     * Siswa yang sudah mengerjakan asesmen ditampilkan lebih dahulu berdasarkan
     * waktu penyelesaian terbaru, diikuti siswa yang belum mengerjakan.
     */
    private function buildRecentActivities()
    {
        return Student::query()
            ->with(['user', 'riasecScore'])
            ->leftJoin('riasec_scores', 'riasec_scores.student_id', '=', 'students.id')
            ->select('students.*', 'riasec_scores.updated_at as assessment_date')
            ->orderByRaw('riasec_scores.updated_at IS NULL, riasec_scores.updated_at DESC')
            ->orderByDesc('students.updated_at')
            ->take(8)
            ->get()
            ->map(function (Student $student) {
                $score = $student->riasecScore;
                
                $assessmentDate = null;
                if ($score && $score->updated_at) {
                    $assessmentDate = \Carbon\Carbon::parse($score->updated_at)->translatedFormat('d M Y');
                }

                // Cek apakah siswa sudah memiliki hasil rekomendasi SAW
                $hasRecommendation = \App\Models\RecommendationResult::where('student_id', $student->id)->exists();

                return [
                    'student_id' => $student->id,
                    'nama' => optional($student->user)->name ?? 'Tanpa Nama',
                    'kelas' => $student->class ?? '-',
                    'assessment_date' => $assessmentDate,
                    'is_completed' => (bool) $score,
                    'has_recommendation' => $hasRecommendation, // Status apakah sudah dihitung SAW
                    'status_label' => $score ? 'Selesai' : 'Belum Mengerjakan',
                ];
            });
    }

    private function buildGreeting(): array
    {
        $hour = (int) now()->format('H');

        return match (true) {
            $hour < 11 => ['text' => 'Selamat Pagi', 'icon' => 'fa-sun'],
            $hour < 15 => ['text' => 'Selamat Siang', 'icon' => 'fa-cloud-sun'],
            $hour < 18 => ['text' => 'Selamat Sore', 'icon' => 'fa-cloud-sun-rain'],
            default => ['text' => 'Selamat Malam', 'icon' => 'fa-moon'],
        };
    }
}