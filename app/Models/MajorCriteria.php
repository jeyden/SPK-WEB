<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Menyimpan profil bobot RIASEC (r_std..c_std) untuk SETIAP program studi
 * secara individual — bukan berdasarkan field/rumpun ilmu, dan bukan bobot
 * yang dipakai bersama oleh beberapa jurusan.
 *
 * Ketentuan (lihat Seeder): r_std + i_std + a_std + s_std + e_std + c_std = 1.00
 *
 * Kolom `academic_std` masih ada di database untuk kompatibilitas data lama,
 * tetapi TIDAK LAGI dipakai oleh SawRecommendationService pada mesin
 * rekomendasi (lihat App\Services\SawRecommendationService::criteriaToArray()).
 */
class MajorCriteria extends Model
{
    protected $table = 'major_criteria';

    protected $fillable = [
        'major_id',
        'r_std',
        'i_std',
        'a_std',
        's_std',
        'e_std',
        'c_std',
        'academic_std', // sudah tidak dipakai di perhitungan SAW, dipertahankan untuk data lama
    ];

    protected function casts(): array
    {
        return [
            'r_std' => 'float',
            'i_std' => 'float',
            'a_std' => 'float',
            's_std' => 'float',
            'e_std' => 'float',
            'c_std' => 'float',
        ];
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    /**
     * Jumlah total bobot RIASEC — dipakai untuk validasi Seeder/admin (harus 1.00).
     */
    public function totalWeight(): float
    {
        return round(
            (float) $this->r_std + (float) $this->i_std + (float) $this->a_std
            + (float) $this->s_std + (float) $this->e_std + (float) $this->c_std,
            2
        );
    }
}