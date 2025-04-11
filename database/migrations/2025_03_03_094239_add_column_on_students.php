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
        Schema::table('students', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('student_number');
            $table->string('address')->nullable()->after('phone');
            $table->enum('gender', ['Laki-laki', 'Perempuan']); // Menghapus spasi yang salah
            $table->string('instagram')->nullable()->after('gender'); // Setelah gender
            $table->date('birthdate')->nullable()->after('instagram'); // Tanggal lahir
            $table->string('profile_picture')->nullable()->after('birthdate'); // Foto profil
            $table->string('cv')->nullable()->after('profile_picture'); // File CV
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'instagram', 'birthdate', 'profile_picture', 'cv']);
        });
    }
};
