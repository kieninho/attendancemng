<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLesson extends Model
{
    use HasFactory;

    protected $table = 'student_lesson';
    protected $fillable = [
        'student_id',
        'lesson_id',
    ];

    //get-set format 
    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }



    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }


    public static function deleteItem($lessonId,$studentId){
        StudentLesson::where('lesson_id',$lessonId)->where('student_id',$studentId)->delete();
    }

    public static function checkExits($lessonId, $studentId){
        return StudentLesson::where('lesson_id',$lessonId)->where('student_id',$studentId)->get()->isEmpty();
    }
}
