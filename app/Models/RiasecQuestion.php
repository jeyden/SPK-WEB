<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiasecQuestion extends Model
{
    protected $table = 'riasec_questions';

    protected $fillable = [
        'category',
        'indicator',
        'indicator_name',
        'question',
    ];

    protected function casts(): array
    {
        return [
            'indicator' => 'integer',
        ];
    }

    /**
     * Relasi ke jawaban/pilihan yang dipilih oleh siswa.
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(StudentAssessment::class, 'question_id');
    }

    /**
     * Nama lengkap dimensi RIASEC.
     */
    public function getDimensionNameAttribute(): string
    {
        $names = [
            'R' => 'Realistic',
            'I' => 'Investigative',
            'A' => 'Artistic',
            'S' => 'Social',
            'E' => 'Enterprising',
            'C' => 'Conventional',
        ];

        return $names[strtoupper($this->category)] ?? $this->category;
    }

    /**
     * Keterangan indikator berdasarkan dimensi RIASEC.
     */
    public function getIndicatorNameAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }

        $indicators = [
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

        $category = strtoupper($this->category);
        $indicator = (int) $this->indicator;

        return $indicators[$category][$indicator] ?? 'Indikator RIASEC';
    }
}