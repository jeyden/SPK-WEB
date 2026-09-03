<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            
            // Data Kriteria Paten 1: Nilai Akademik & Asal Jurusan Sekolah
            $table->decimal('academic_score', 5, 2)->nullable(); 
            $table->string('school_major_origin', 100)->nullable(); // Jurusan asal siswa saat di SMA/SMK
            
            // Ditambahkan: Kolom untuk Skor C3 (Dihitung/diatur otomatis oleh sistem berdasarkan asal jurusan)
            $table->decimal('c3_score', 5, 2)->default(0); 

            // Data Kriteria Paten 2: Rekapitulasi Skor Tes RIASEC
            $table->integer('score_r')->default(0);
            $table->integer('score_i')->default(0);
            $table->integer('score_a')->default(0);
            $table->integer('score_s')->default(0);
            $table->integer('score_e')->default(0);
            $table->integer('score_c')->default(0);
            $table->string('dominant_riasec_code', 20)->nullable(); // Contoh: I-S-A
            
            $table->string('academic_year', 20)->index();
            $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'academic_year'], 'student_assessment_year_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_assessments');
    }
};  