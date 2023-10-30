<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Class_;

class StudentLesson extends Model
{
    use HasFactory;

    protected $table = 'student_lesson';
    protected $fillable = [
        'student_id',
        'lesson_id',
        'status',
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

    public static function getItemByStudentAndLesson($lessonId, $studentId){
        return StudentLesson::where('lesson_id',$lessonId)->where('student_id',$studentId)->first();
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public static function deleteByLessonId($lessonId){
        StudentLesson::where('lesson_id',$lessonId)->delete();
    }

    public static function deleteByClass($classId){
        $lessons = Classes::findOrFail($classId)->lessons()->get();
        foreach($lessons as $lesson){
            StudentLesson::deleteByLessonId($lesson->id);
        }
    }

    public static function deleteByStudentAndClass($studentId, $classId){
        $lessons = Classes::findOrFail($classId)->lessons()->get();

        foreach($lessons as $lesson){
            StudentLesson::where('lesson_id',$lesson->id)->where('student_id',$studentId)->delete();
        }
    }
}
