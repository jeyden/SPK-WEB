<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Kolom `code` WAJIB diisi salah satu dari: UI, ITB, UGM, IPB, ITS.
 * Nilai ini dipakai langsung oleh
 * App\Services\SawRecommendationService::determineCampusByTsk() untuk
 * menentukan satu kampus akhir berdasarkan threshold TSK siswa.
 * Kampus lain (di luar 5 kode ini) tidak lagi dipakai dalam mekanisme
 * rekomendasi terbaru, meskipun datanya boleh tetap ada di database.
 */
class Campus extends Model
{
    protected $fillable = ['name', 'code', 'type', 'city'];

    public function majors(): BelongsToMany
    {
        return $this->belongsToMany(Major::class, 'campus_majors')
            ->using(CampusMajor::class)
            ->withPivot(['required_school_major', 'weight_score', 'accreditation', 'quota'])
            ->withTimestamps();
    }

    /**
     * Hasil rekomendasi akhir (berbasis threshold TSK) yang mengarah ke kampus ini.
     */
    public function recommendationResults(): HasMany
    {
        return $this->hasMany(RecommendationResult::class, 'final_campus_id');
    }
}