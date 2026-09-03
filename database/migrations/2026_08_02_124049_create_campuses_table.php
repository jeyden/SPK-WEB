<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // Contoh: Universitas Indonesia
            $table->string('code')->unique();     // Contoh: UI
            $table->string('type')->nullable();   // Contoh: PTN / PTS
            $table->string('city')->nullable();   // Contoh: Depok / Jakarta
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campuses');
    }
};