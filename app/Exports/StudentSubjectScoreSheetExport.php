<?php

namespace App\Exports;

use App\Models\SchoolSubject;
use App\Models\StudentSubjectScore;
use App\Support\SchoolMajorHelper;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentSubjectScoreSheetExport implements FromArray, WithStyles, WithTitle
{
    protected string $academicYear;
    protected string $groupLabel; // 'IPA' | 'IPS' | 'SMK' | 'BAHASA' | 'BELUM DIISI' | dst
    protected Collection $students;
    protected array $columns = [];

    public function __construct(string $academicYear, string $groupLabel, Collection $students)
    {
        $this->academicYear = $academicYear;
        $this->groupLabel = $groupLabel;
        $this->students = $students;
    }

    /**
     * Jadi nama sheet di Excel (mis. "IPA", "IPS"). Maks 31 karakter sesuai
     * batas Excel — label kelompok kita jauh di bawah itu jadi aman.
     */
    public function title(): string
    {
        return $this->groupLabel;
    }

    public function array(): array
    {
        // Hanya mapel yang relevan untuk kelompok jurusan sheet ini + mapel
        // umum (kalau ada) — pakai helper yang SAMA dengan filter di form
        // penilaian manual, supaya konsisten.
        $subjects = SchoolMajorHelper::relevantSubjectsForGroup($this->groupLabel);

        if ($subjects->isEmpty()) {
            // Tidak ada mapel yang match sama sekali untuk kelompok ini
            // (mis. master data mapel belum diisi untuk SMK) -> fallback ke
            // semua mapel, sama seperti fallback di form penilaian manual.
            $subjects = SchoolSubject::orderBy('name')->get();
        }

        $this->columns = $subjects->map(fn ($subject) => [
            'label' => $subject->name,
            'key' => "S{$subject->id}",
            'subject_id' => $subject->id,
        ])->all();

        $headerLabels = array_merge(['NISN', 'Nama Siswa'], array_column($this->columns, 'label'));
        $headerKeys = array_merge(['', ''], array_column($this->columns, 'key'));

        $rows = [$headerLabels, $headerKeys];

        $subjectIds = array_column($this->columns, 'subject_id');

        $existing = StudentSubjectScore::where('academic_year', $this->academicYear)
            ->whereIn('school_subject_id', $subjectIds)
            ->get()
            ->groupBy(fn ($item) => $item->student_id . '-' . $item->school_subject_id);

        foreach ($this->students as $student) {
            $row = [$student->nisn, optional($student->user)->name ?? '-'];

            foreach ($this->columns as $col) {
                $mapKey = $student->id . '-' . $col['subject_id'];
                $row[] = $existing->get($mapKey)?->first()?->score ?? '';
            }

            $rows[] = $row;
        }

        if ($this->students->isEmpty()) {
            // Supaya sheet tidak benar-benar 0 baris data (beberapa versi
            // Excel/PhpSpreadsheet rewel dengan sheet yang cuma berisi header).
            $rows[] = array_fill(0, count($headerLabels), '');
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getStyle('2:2')->getFont()->setItalic(true)->setColor(new Color(Color::COLOR_RED));

        // Sembunyikan baris kunci sistem agar tidak sengaja dihapus/diubah oleh pengguna
        $sheet->getRowDimension(2)->setVisible(false);

        return [];
    }
}