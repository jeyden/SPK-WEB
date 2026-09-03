<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiasecScore extends Model
{
    protected $fillable = [
        'student_id',
        'r_score', // Realistic
        'i_score', // Investigative
        'a_score', // Artistic
        's_score', // Social
        'e_score', // Enterprising
        'c_score', // Conventional
        'tsk',     // Total Skor Komposit = R+I+A+S+E+C (24-120)
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'dominant_code',
        'dominant_code_description',
    ];

    protected function casts(): array
    {
        return [
            'r_score' => 'integer',
            'i_score' => 'integer',
            'a_score' => 'integer',
            's_score' => 'integer',
            'e_score' => 'integer',
            'c_score' => 'integer',
            'tsk' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Hitung TSK dari 6 skor dimensi. Dipakai sebagai fallback bila kolom
     * tsk belum terisi (mis. data lama sebelum kolom ini ditambahkan).
     */
    public function computeTsk(): int
    {
        return (int) ($this->r_score ?? 0)
            + (int) ($this->i_score ?? 0)
            + (int) ($this->a_score ?? 0)
            + (int) ($this->s_score ?? 0)
            + (int) ($this->e_score ?? 0)
            + (int) ($this->c_score ?? 0);
    }

    /**
     * Accessor untuk mendapatkan kode RIASEC dominan secara otomatis (3 huruf tertinggi).
     * Contoh hasil: "RIA", "SEC", "IEC"
     */
    public function getDominantCodeAttribute(): string
    {
        $scores = [
            'R' => $this->r_score ?? 0,
            'I' => $this->i_score ?? 0,
            'A' => $this->a_score ?? 0,
            'S' => $this->s_score ?? 0,
            'E' => $this->e_score ?? 0,
            'C' => $this->c_score ?? 0,
        ];

        arsort($scores);

        $topKeys = array_slice(array_keys($scores), 0, 3);

        return implode('', $topKeys);
    }

    /**
     * Accessor untuk mendapatkan penjelasan deskriptif berdasarkan huruf pertama dari kode dominan.
     */
    public function getDominantCodeDescriptionAttribute(): string
    {
        $descriptions = [
            'R' => 'Realistic: Praktis, fisik, menyukai aktivitas langsung dengan mesin, alat, atau objek nyata.',
            'I' => 'Investigative: Analitis, ilmiah, menyukai pemecahan masalah kompleks, riset, dan teori.',
            'A' => 'Artistic: Kreatif, ekspresif, mandiri, menyukai seni, desain, bahasa, dan situasi bebas.',
            'S' => 'Social: Empatik, suka membantu, mengajar, membimbing, dan berinteraksi sosial.',
            'E' => 'Enterprising: Persuasif, berjiwa pemimpin, menyukai bisnis, negosiasi, dan pencapaian target.',
            'C' => 'Conventional: Terstruktur, teliti, menyukai data, angka, administrasi, dan keteraturan prosedur.',
        ];

        $domCode = $this->dominant_code;
        $firstChar = strtoupper(substr($domCode, 0, 1));

        return $descriptions[$firstChar] ?? 'Siswa memiliki kombinasi minat kerja yang unik untuk penentuan rekomendasi jurusan.';
    }
}