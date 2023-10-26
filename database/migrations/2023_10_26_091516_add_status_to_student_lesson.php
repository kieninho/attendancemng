<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToStudentLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('student_lesson', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->after('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_lesson', function (Blueprint $table) {
            $table->dropColumn('status');
        });

    }
}
