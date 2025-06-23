<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    // {
    //     Schema::table('students', function (Blueprint $table) {
    //         $table->enum('prodi', ['D3 Teknik Elektronika', 'D4  Teknik Elektronika', 'D3 Teknik Informatika', 'D4 Teknik Informatika', 'D3 Teknik Telekomunikasi', 'D4 Teknik Telekomunikasi', 'D3 Teknik Elektro Industri', 'D4 Teknik Elektro Industri', 'D4 Teknik Komputer', 'D4 Teknik Rekayasa Perangkat Lunak', 'D4 Teknik Multimedia Digital', 'D4 Teknik Mekatronika', 'D4 Sistem Pembangkitan Energi', 'D4 Teknologi Rekayasa Otomotif', 'D4 Teknologi Rekayasa Multimedia', 'D4 Teknologi Game', 'D4 Desain Grafis'] )->change();
    //         $table->enum('department', ['Departemen Teknik Elektro', 'Departemen Teknik Informatika dan Komputer', 'Departemen Teknik Mekanika Energi', 'Departemen Teknologi Multimedia Kreatif'])->change();
    //     });
    // }
    {
        // DB::table('students')->where('prodi', 'D3')->update(['prodi' => 'D3 Teknik Informatika']);
        // DB::table('students')->where('prodi', 'D4')->update(['prodi' => 'D4 Teknik Informatika']);

        // Step 2: Perbaiki data department yang tidak valid
        // $validDepartments = [
        //     'Departemen Teknik Elektro',
        //     'Departemen Teknik Informatika dan Komputer',
        //     'Departemen Teknik Mekanika Energi',
        //     'Departemen Teknologi Multimedia Kreatif'
        // ];

        // // Step 3: Ubah tipe kolom setelah data fix
        Schema::table('students', function (Blueprint $table) {
            // Perbaiki typo pada opsi prodi
            $table->enum('prodi', [
                'D3 Teknik Elektronika',
                'D4 Teknik Elektronika',
                'D3 Teknik Informatika',
                'D4 Teknik Informatika',
                'D3 Teknik Telekomunikasi',
                'D4 Teknik Telekomunikasi',
                'D3 Teknik Elektro Industri',
                'D4 Teknik Elektro Industri',
                'D4 Teknik Komputer',
                'D4 Teknik Rekayasa Perangkat Lunak',
                'D4 Teknik Multimedia Digital',
                'D4 Teknik Mekatronika',
                'D4 Sistem Pembangkitan Energi', // Perbaiki typo: 'Pembangkitan'
                'D4 Teknologi Rekayasa Otomotif',
                'D4 Teknologi Rekayasa Multimedia',
                'D4 Teknologi Game',
                'D4 Desain Grafis'
            ])->nullable();

            $table->enum('department', [
                'Departemen Teknik Elektro',
                'Departemen Teknik Informatika dan Komputer',
                'Departemen Teknik Mekanika Energi',
                'Departemen Teknologi Multimedia Kreatif'
            ])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
