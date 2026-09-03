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
        Schema::create('field_of_studies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Sains dan Teknologi / Ilmu Komputer
            $table->unsignedBigInteger('parent_id')->nullable(); // Untuk hierarki Sub-Ilmu
            $table->timestamps();

            // Foreign key self-referencing untuk sub-ilmu
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('field_of_studies')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_of_studies');
    }
};