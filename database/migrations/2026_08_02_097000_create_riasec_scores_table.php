<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riasec_scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            // Enam skor dimensi RIASEC
            $table->unsignedTinyInteger('r_score')->default(0);
            $table->unsignedTinyInteger('i_score')->default(0);
            $table->unsignedTinyInteger('a_score')->default(0);
            $table->unsignedTinyInteger('s_score')->default(0);
            $table->unsignedTinyInteger('e_score')->default(0);
            $table->unsignedTinyInteger('c_score')->default(0);

            // Total Skor Komposit: R + I + A + S + E + C
            $table->unsignedSmallInteger('tsk')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riasec_scores');
    }
};