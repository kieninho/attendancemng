<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherLesson extends Model
{
    use HasFactory;

    protected $table = 'teacher_lesson';
    protected $fillable = [
        'teacher_id',
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

    public static function deleteByLessonId($lessonId){
        TeacherLesson::Where('lesson_id',$lessonId)->delete();
    }

    public static function getItemByLessonId($lessonId){
        $result = TeacherLesson::where('lesson_id',$lessonId)->get();
        if($result){
            return $result;
        }
        return null;
    }
}
