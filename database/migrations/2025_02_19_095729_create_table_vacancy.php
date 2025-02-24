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
        Schema::create('vacancy', function (Blueprint $table) {
            $table->id('vacancy_id'); // Primary Key
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade'); // Foreign Key ke companies
            $table->string('division', 100);
            $table->integer('duration'); // Durasi dalam jumlah hari/bulan
            $table->enum('type', ['full-time', 'part-time', 'internship', 'contract']); // Jenis pekerjaan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy');
    }
};
