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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('contact_person'); // Hapus kolom contact_person
            $table->text('description')->nullable(); // Tambah kolom deskripsi
            $table->decimal('x_coordinate', 10, 6)->nullable(); // Koordinat X (misalnya untuk lokasi)
            $table->decimal('y_coordinate', 10, 6)->nullable(); // Koordinat Y (misalnya untuk lokasi)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
};
