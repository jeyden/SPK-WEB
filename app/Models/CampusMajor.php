<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Pivot Campus <-> Major. Kolom `weight_score` di sini adalah skor
 * internal untuk menampilkan Top-3 kampus penyedia sebuah program studi
 * (fitur tampilan pada laporan) — TIDAK dipakai untuk menentukan kampus
 * akhir hasil rekomendasi. Penentuan kampus akhir kini murni berdasarkan
 * threshold TSK (lihat App\Services\SawRecommendationService::determineCampusByTsk()).
 */
class CampusMajor extends Pivot
{
    protected $table = 'campus_majors';

    protected $fillable = [
        'campus_id',
        'major_id',
        'required_school_major',
        'weight_score',
        'accreditation',
        'quota',
    ];
}