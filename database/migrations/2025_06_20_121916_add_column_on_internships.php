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
        $validDepartments = [
            'Departemen Teknik Elektro',
            'Departemen Teknik Informatika dan Komputer',
            'Departemen Teknik Mekanika Energi',
            'Departemen Teknologi Multimedia Kreatif'
        ];
      
        Schema::table('internships', function (Blueprint $table) {
            $table->enum('semester', ['ganjil', 'genap']);
            // periode magang, contoh 2024/2025
            $table->string('periode')->nullable();
            $table->date('tanggal_sidang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            //
        });
    }
};
