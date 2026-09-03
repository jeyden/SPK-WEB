<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riasec_questions', function (Blueprint $table) {
            $table->id();

            // Dimensi RIASEC: R, I, A, S, E, C
            $table->enum('category', ['R', 'I', 'A', 'S', 'E', 'C']);

            // Nomor indikator dalam setiap dimensi
            $table->unsignedTinyInteger('indicator')->default(1);

            // Nama/keterangan indikator
            $table->string('indicator_name');

            // Pernyataan / pertanyaan tes
            $table->text('question');

            $table->timestamps();

            // Index untuk pengurutan dan pencarian berdasarkan dimensi serta indikator
            $table->index(['category', 'indicator']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riasec_questions');
    }
};