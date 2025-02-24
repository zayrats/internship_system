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
        Schema::table('applications', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['student_id']);

            // Hapus kolom student_id
            $table->dropColumn('student_id');

            // Tambah kolom baru user_id
            $table->unsignedBigInteger('user_id')->after('id');

            // Tambahkan foreign key baru ke students.user_id
            $table->foreign('user_id')->references('user_id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
