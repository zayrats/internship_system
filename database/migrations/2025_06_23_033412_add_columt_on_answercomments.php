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
        Schema::table('answer_comments', function (Blueprint $table) {

            // $table->foreignId('answer_id')->constrained()->onDelete('cascade')->change();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade')->change();
            $table->foreignId('parent_id')->nullable()->constrained('answer_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('answer_comments', function (Blueprint $table) {
            //
        });
    }
};
