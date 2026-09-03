<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel belum ada, buat dari awal beserta kolom academic_std
        if (!Schema::hasTable('major_criteria')) {
            Schema::create('major_criteria', function (Blueprint $table) {
                $table->id();
                $table->foreignId('major_id')->constrained('majors')->onDelete('cascade');
                $table->decimal('academic_std', 5, 2)->default(75); // Standar nilai akademik C1
                $table->decimal('r_std', 5, 2)->default(0);
                $table->decimal('i_std', 5, 2)->default(0);
                $table->decimal('a_std', 5, 2)->default(0);
                $table->decimal('s_std', 5, 2)->default(0);
                $table->decimal('e_std', 5, 2)->default(0);
                $table->decimal('c_std', 5, 2)->default(0);
                $table->timestamps();
            });
        } else {
            // Jika tabel sudah ada, tambahkan kolom academic_std jika belum tersedia
            Schema::table('major_criteria', function (Blueprint $table) {
                if (!Schema::hasColumn('major_criteria', 'academic_std')) {
                    $table->decimal('academic_std', 5, 2)->default(75)->after('major_id');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('major_criteria', function (Blueprint $table) {
            if (Schema::hasColumn('major_criteria', 'academic_std')) {
                $table->dropColumn('academic_std');
            }
        });
    }
};