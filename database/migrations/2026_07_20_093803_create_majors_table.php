<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            // Gunakan unsignedBigInteger agar aman jika tabel field_of_studies dibuat belakangan
            $table->unsignedBigInteger('field_of_study_id')->nullable();
            $table->string('name');
            $table->string('degree')->nullable();
            $table->text('description')->nullable();
            $table->text('prospects')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};