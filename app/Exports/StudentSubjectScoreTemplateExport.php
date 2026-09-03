<?php

namespace App\Exports;

use App\Models\Student;
use App\Support\SchoolMajorHelper;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentSubjectScoreTemplateExport implements WithMultipleSheets
{
    protected string $academicYear;
    protected string $major;

    public function __construct(string $academicYear, string $major = 'IPA')
    {
        $this->academicYear = $academicYear;
        $this->major = strtoupper($major);
    }

    public function sheets(): array
    {
        $students = Student::with('user')->orderBy('nisn')->get();

        $filteredStudents = $students->filter(function ($student) {
            $normalized = SchoolMajorHelper::normalize($student->high_school_major);
            $groupLabel = $normalized === 'UMUM' ? 'BELUM DIISI' : $normalized;
            return strtoupper($groupLabel) === $this->major;
        });

        return [
            $this->major => new StudentSubjectScoreSheetExport(
                $this->academicYear,
                $this->major,
                $filteredStudents->values()
            )
        ];
    }
}