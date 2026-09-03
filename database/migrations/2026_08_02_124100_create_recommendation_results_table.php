<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Program studi terbaik hasil perhitungan SAW
            $table->foreignId('major_id')
                ->constrained('majors')
                ->cascadeOnDelete();

            $table->string('academic_year');

            // Nilai preferensi hasil perhitungan SAW
            $table->decimal('preference_score', 8, 6);

            // Total Skor Komposit
            $table->unsignedSmallInteger('tsk')->default(0);

            // Perguruan tinggi hasil threshold TSK
            $table->foreignId('final_campus_id')
                ->nullable()
                ->constrained('campuses')
                ->nullOnDelete();

            // Ranking program studi
            $table->unsignedInteger('rank')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_results');
    }
};