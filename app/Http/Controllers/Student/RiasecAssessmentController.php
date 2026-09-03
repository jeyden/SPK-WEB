<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\RiasecQuestion;
use App\Models\RiasecScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiasecAssessmentController extends Controller
{
    /**
     * Menampilkan halaman assessment atau hasil RIASEC.
     */
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(403);
        }

        if ($student->riasecScore) {
            return view('student.riasec.result', [
                'score' => $student->riasecScore,
                'student' => $student,
            ]);
        }

        $indicatorNames = [
            'R' => [
                1 => 'Merawat dan Memperbaiki Perangkat',
                2 => 'Memasang dan Mengatur Perangkat',
            ],
            'I' => [
                1 => 'Mencari Solusi dan Memecahkan Masalah',
                2 => 'Mengamati dan Menganalisis Informasi',
            ],
            'A' => [
                1 => 'Membuat Tampilan Menarik',
                2 => 'Mengembangkan Ide Kreatif',
            ],
            'S' => [
                1 => 'Membantu dan Mengajari Orang Lain',
                2 => 'Bekerja Sama dan Berkomunikasi',
            ],
            'E' => [
                1 => 'Memimpin dan Mengatur Kegiatan',
                2 => 'Mencari Peluang dan Mempengaruhi Orang Lain',
            ],
            'C' => [
                1 => 'Mengatur dan Memeriksa Data',
                2 => 'Mengikuti Aturan dan Membuat Dokumentasi',
            ],
        ];

        $questions = RiasecQuestion::query()
            ->orderByRaw("
                CASE category
                    WHEN 'R' THEN 1
                    WHEN 'I' THEN 2
                    WHEN 'A' THEN 3
                    WHEN 'S' THEN 4
                    WHEN 'E' THEN 5
                    WHEN 'C' THEN 6
                END
            ")
            ->orderBy('indicator')
            ->orderBy('id')
            ->get();

        $questions->each(function ($question) use ($indicatorNames) {
            $category = strtoupper($question->category);
            $indicator = (int) $question->indicator;

            $question->indicator_name =
                $indicatorNames[$category][$indicator] ?? 'Indikator RIASEC';
        });

        return view('student.riasec.index', [
            'questions' => $questions,
            'student' => $student,
            'indicatorNames' => $indicatorNames,
        ]);
    }

    /**
     * Menyimpan jawaban dan menghitung skor RIASEC.
     */
    public function store(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(403);
        }

        $questions = RiasecQuestion::query()
            ->orderByRaw("
                CASE category
                    WHEN 'R' THEN 1
                    WHEN 'I' THEN 2
                    WHEN 'A' THEN 3
                    WHEN 'S' THEN 4
                    WHEN 'E' THEN 5
                    WHEN 'C' THEN 6
                END
            ")
            ->orderBy('indicator')
            ->orderBy('id')
            ->get();

        if ($questions->count() !== 24) {
            return back()
                ->withInput()
                ->with('error', 'Data RIASEC tidak valid. Sistem harus memiliki tepat 24 pernyataan.');
        }

        $categories = ['R', 'I', 'A', 'S', 'E', 'C'];
        $categoryCounts = $questions->groupBy('category');

        foreach ($categories as $category) {
            if (!isset($categoryCounts[$category]) || $categoryCounts[$category]->count() !== 4) {
                return back()
                    ->withInput()
                    ->with('error', "Dimensi {$category} harus memiliki tepat 4 pernyataan.");
            }
        }

        foreach ($categories as $category) {
            $indicatorCounts = $categoryCounts[$category]->groupBy('indicator');

            foreach ([1, 2] as $indicator) {
                if (!isset($indicatorCounts[$indicator]) || $indicatorCounts[$indicator]->count() !== 2) {
                    return back()
                        ->withInput()
                        ->with('error', "Dimensi {$category}, indikator {$indicator} harus memiliki tepat 2 pernyataan.");
                }
            }
        }

        $rules = [
            'answers' => ['required', 'array', 'size:24'],
        ];

        foreach ($questions as $question) {
            $rules["answers.{$question->id}"] = [
                'required',
                'integer',
                'min:1',
                'max:5',
            ];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($student, $questions, $validated) {
            $scores = [
                'R' => 0,
                'I' => 0,
                'A' => 0,
                'S' => 0,
                'E' => 0,
                'C' => 0,
            ];

            foreach ($questions as $question) {
                $value = (int) $validated['answers'][$question->id];
                $category = strtoupper($question->category);

                if (!array_key_exists($category, $scores)) {
                    throw new \RuntimeException("Kategori RIASEC {$category} tidak valid.");
                }

                $scores[$category] += $value;
            }

            foreach ($scores as $category => $score) {
                if ($score < 4 || $score > 20) {
                    throw new \RuntimeException("Skor {$category} tidak valid.");
                }
            }

            $tsk = array_sum($scores);

            if ($tsk < 24 || $tsk > 120) {
                throw new \RuntimeException('Total Skor Komposit (TSK) tidak valid.');
            }

            RiasecScore::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'r_score' => $scores['R'],
                    'i_score' => $scores['I'],
                    'a_score' => $scores['A'],
                    's_score' => $scores['S'],
                    'e_score' => $scores['E'],
                    'c_score' => $scores['C'],
                    'tsk' => $tsk,
                ]
            );
        });

        return redirect()
            ->route('student.riasec.index')
            ->with('success', 'Asesmen RIASEC berhasil diselesaikan dan disimpan.');
    }
}