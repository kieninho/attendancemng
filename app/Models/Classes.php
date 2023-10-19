<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;


class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';
    // attribute
    protected $fillable = [
        'id',
        'name',
        'code',
        'description',
        'status',
    ];

    //get-set format 
    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    // public function setCreatedAtAttribute($value)
    // {
    //     $this->attributes['created_at'] = date('H:i:s d/m/y', strtotime($value));
    // }

    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    // public function setUpdatedAtAttribute($value)
    // {
    //     $this->attributes['updated_at'] = date('d/m/y H:i:s', strtotime($value));
    // }
    
    // Relationship
    public function students(){
        return $this->belongsToMany(Student::class,'student_class','class_id','student_id')->where('status', 1);
    }

    public function lessons(){
        return $this->hasMany(Lesson::class,'class_id')->where('status', 1);
    }
    
    public static function search($keyword){
        $result = Classes::where(function($query) use ($keyword) {
            $query->where('name','like',"%$keyword%")
                  ->orWhere('description','like',"%$keyword%");
        })->where('status',1);
        
        return $result;
    }

    public static function searchLesson($classId,$keyword){
        $class = Classes::findOrFail($classId);
        if(!$class){
            return;
        }
        
        $lessons = $class->lessons()->where('name','like',"%$keyword%")->get();

        return  $lessons;
    }

    public function countLessonAttend($studentId){
        $result = DB::table('classes')
        ->leftJoin('student_class', 'student_class.class_id', '=', 'classes.id')
        ->leftJoin('students', 'students.id', '=', 'student_class.student_id')
        ->leftJoin('student_lesson', 'student_lesson.student_id', '=', 'students.id')
        ->leftJoin('lessons', 'lessons.id', '=', 'student_lesson.lesson_id')
        ->where('lessons.class_id', '=', $this->id)
        ->where('student_lesson.student_id', '=', $studentId)
        ->where('lessons.status', '=', 1)
        ->groupBy('lessons.id')
        ->select('lessons.id')
        ->get();

        return count($result);
    }

    

}
