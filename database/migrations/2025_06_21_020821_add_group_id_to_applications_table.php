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
            $table->string('group_id')->nullable(); // bisa pakai UUID atau token string
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Finished', 'Terminated'])->default('Pending')->change();
            $table->string('terminated_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            //
        });
    }
};
