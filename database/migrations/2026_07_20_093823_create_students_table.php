<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dream_major_id')->nullable()->constrained('majors')->nullOnDelete();
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('class', 50);
            $table->string('academic_year', 20)->nullable();
            $table->string('high_school_major', 50);
            $table->string('interest', 150)->nullable();
            $table->string('economy', 100)->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            
            // Tambahkan baris ini untuk menandai status kelengkapan profil onboarding
            $table->boolean('profile_completed')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};