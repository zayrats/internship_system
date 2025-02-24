<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->enum('prodi', ['D3', 'D4'])->after('student_id');
        });

        Schema::table('internships', function (Blueprint $table) {
            $table->string('position')->after('student_id');
        });

    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('prodi');
        });

        Schema::table('internship', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
