<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Campus;
use App\Models\Major;
use App\Models\Criteria;
use App\Models\CriteriaOption;
use App\Models\MajorCriteria;
use App\Models\StudentAssessment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Administrator & Guru BK (Counselor) Saja
        User::updateOrCreate(
            ['email' => 'admin@sekolah.sch.id'],
            [
                'name' => 'Administrator Sistem',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'bk@sekolah.sch.id'],
            [
                'name' => 'Ibu Guru BK, S.Pd.',
                'password' => Hash::make('password'),
                'role' => 'counselor',
                'email_verified_at' => now(),
            ]
        );
         User::updateOrCreate(
            ['email' => 'siswa@sekolah.sch.id'],
            [
                'name' => 'Siswa',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );
    }
}