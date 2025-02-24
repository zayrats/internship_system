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
        Schema::table('internships', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable(); // Menambahkan kolom student_id
            $table->text('feedback')->nullable(); // Menambahkan kolom feedback
            $table->timestamps(); // Menambahkan timestamps
            $table->dropColumn(['description', 'requirements']); // Menghapus kolom yang tidak diperlukan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internship', function (Blueprint $table) {
            //
        });
    }
};
