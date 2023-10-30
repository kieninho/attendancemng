<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\StudentLesson;

class AddCheckedAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        StudentLesson::truncate();

        Schema::table('lessons', function (Blueprint $table) {
            $table->tinyInteger('checked_attendance')->default(0);
        });

    }

 
    public function down()
    {
        //
    }
}
