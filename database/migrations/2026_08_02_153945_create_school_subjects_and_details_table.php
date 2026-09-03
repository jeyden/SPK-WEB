<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel master mata pelajaran berdasarkan jurusan asal (misal: MIPA / IPS)
        Schema::create('school_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('major_target'); // Contoh: 'MIPA', 'IPS', dll.
            $table->string('name'); // Contoh: 'Matematika Peminatan', 'Fisika', 'Sosiologi'
            $table->timestamps();
        });

        // Tabel untuk menyimpan nilai per mapel paten siswa
        Schema::create('student_subject_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('school_subject_id')->constrained('school_subjects')->cascadeOnDelete();
            $table->string('academic_year', 20);
            $table->decimal('score', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_subject_scores');
        Schema::dropIfExists('school_subjects');
    }
};