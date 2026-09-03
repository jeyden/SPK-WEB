<?php

namespace App\Support;

use App\Models\SchoolSubject;
use Illuminate\Support\Collection;

/**
 * Satu-satunya tempat logika normalisasi jurusan sekolah asal (IPA/IPS/SMK/dst)
 * dan pemetaan mapel-relevan didefinisikan.
 *
 * SEBELUMNYA logika ini terduplikasi di StudentAssessmentController dan
 * SawRecommendationService secara terpisah (dengan komentar "HARUS IDENTIK")
 * — cara itu rapuh karena mengandalkan disiplin manual untuk menjaga dua
 * salinan tetap sinkron. Sekarang keduanya (dan StudentSubjectScoreTemplateExport)
 * memanggil helper ini, jadi tidak mungkin lagi drift.
 */
class SchoolMajorHelper
{
    /**
     * Normalisasi nama jurusan sekolah asal ke bentuk baku: IPA, IPS, SMK,
     * BAHASA, atau UMUM.
     */
    public static function normalize($value): string
    {
        if (is_array($value)) {
            $value = $value[0] ?? '';
        }

        $value = strtoupper(trim((string) $value));
        $value = preg_replace('/\s+/', ' ', $value);

        return match ($value) {
            'IPA', 'MIPA', 'SAINS' => 'IPA',
            'IPS', 'SOSIAL' => 'IPS',
            'SMK', 'KEJURUAN' => 'SMK',
            'BAHASA' => 'BAHASA',
            'UMUM', 'SEMUA', 'BEBAS', '' => 'UMUM',
            default => $value,
        };
    }

    /**
     * Ambil daftar mapel yang relevan untuk SATU siswa, berdasarkan
     * high_school_major mentahnya (dinormalisasi dulu). Dipakai di form
     * penilaian manual (assess()).
     */
    public static function relevantSubjectsForStudentMajor(?string $studentMajor): Collection
    {
        return static::relevantSubjectsForGroup(static::normalize($studentMajor));
    }

    /**
     * Ambil daftar mapel yang relevan untuk SATU kelompok jurusan yang SUDAH
     * dinormalisasi (mis. 'IPA', 'IPS', 'SMK', 'BELUM DIISI'). Mapel
     * bertarget UMUM/SEMUA/BEBAS selalu ikut untuk semua kelompok.
     * Dipakai untuk membangun sheet per kelompok di template Excel.
     */
    public static function relevantSubjectsForGroup(string $normalizedGroup): Collection
    {
        // "BELUM DIISI" dipakai Export untuk siswa yang belum mengisi jurusan
        // sekolah asalnya -> tidak ada kelompok spesifik, jadi hanya mapel
        // umum yang cocok.
        $targetGroup = $normalizedGroup === 'BELUM DIISI' ? 'UMUM' : $normalizedGroup;

        return SchoolSubject::orderBy('name')->get()->filter(function ($subject) use ($targetGroup) {
            $targets = preg_split('/[,\/;|]+/', (string) $subject->major_target);

            foreach ($targets as $target) {
                if (static::normalize($target) === 'UMUM' || static::normalize($target) === $targetGroup) {
                    return true;
                }
            }

            return false;
        })->values();
    }
}