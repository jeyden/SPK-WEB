<?php

namespace App\Imports;

use App\Models\SchoolSubject;
use App\Models\Student;
use App\Models\StudentAssessment;
use App\Models\StudentSubjectScore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentSubjectScoreImport implements ToCollection
{
    protected string $academicYear;

    public array $errorMessages = [];
    public int $rowsProcessed = 0;
    public int $cellsUpdated = 0;

    public function __construct(string $academicYear)
    {
        $this->academicYear = $academicYear;
    }

    public function collection(Collection $rows): void
    {
        if ($rows->count() < 3) {
            return;
        }

        $keyRow = $rows->get(1);
        $columnMap = [];

        foreach ($keyRow as $colIndex => $rawKey) {
            $key = trim((string) $rawKey);
            if ($colIndex < 2 || $key === '') continue;

            if (preg_match('/^S(\d+)$/', $key, $m)) {
                $columnMap[$colIndex] = (int) $m[1];
            }
        }

        if (empty($columnMap)) {
            $this->errorMessages[] = 'Kolom mata pelajaran tidak valid — pastikan menggunakan template resmi.';
            return;
        }

        $validSubjectIds = SchoolSubject::whereIn('id', array_values($columnMap))
            ->pluck('id')
            ->all();

        for ($i = 2; $i < $rows->count(); $i++) {
            $row = $rows->get($i);
            $excelRowNumber = $i + 1;

            $nisn = trim((string) ($row->get(0) ?? ''));
            if ($nisn === '') {
                continue;
            }

            $student = Student::where('nisn', $nisn)->first();
            if (!$student) {
                $this->errorMessages[] = "Baris {$excelRowNumber}: NISN '{$nisn}' tidak ditemukan di sistem, dilewati.";
                continue;
            }

            $rowHasUpdates = false;

            DB::transaction(function () use ($row, $student, $excelRowNumber, $columnMap, $validSubjectIds, &$rowHasUpdates) {
                foreach ($columnMap as $colIndex => $subjectId) {
                    if (!in_array($subjectId, $validSubjectIds, true)) continue;
                    if (!$row->has($colIndex)) continue;

                    $value = $row->get($colIndex);

                    if ($value === null || trim((string) $value) === '') {
                        StudentSubjectScore::where('student_id', $student->id)
                            ->where('school_subject_id', $subjectId)
                            ->where('academic_year', $this->academicYear)
                            ->delete();
                        continue;
                    }

                    if (!is_numeric($value) || $value < 0 || $value > 100) {
                        $this->errorMessages[] = "NISN {$student->nisn} (baris {$excelRowNumber}): nilai '{$value}' tidak valid (0-100), dilewati.";
                        continue;
                    }

                    StudentSubjectScore::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'school_subject_id' => $subjectId,
                            'academic_year' => $this->academicYear,
                        ],
                        ['score' => $value]
                    );

                    $this->cellsUpdated++;
                    $rowHasUpdates = true;
                }

                $average = StudentSubjectScore::where('student_id', $student->id)
                    ->where('academic_year', $this->academicYear)
                    ->avg('score');

                $assessment = StudentAssessment::firstOrNew([
                    'student_id' => $student->id,
                    'academic_year' => $this->academicYear,
                ]);

                // Menggunakan kolom 'academic_score' yang sesuai dengan database
                $assessment->academic_score = $average !== null ? round($average, 2) : 0;
                $assessment->assessed_by = Auth::id();
                $assessment->save();
            });

            if ($rowHasUpdates) {
                $this->rowsProcessed++;
            }
        }
    }

    public function processSheet(Collection $rows): void
    {
        $this->collection($rows);
    }
}