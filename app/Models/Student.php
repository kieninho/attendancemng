<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;


class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $fillable = [
        'name',
        'code',
        'email',
        'birthday',
        'status',
    ];

    protected $casts = [
        'birthday' => 'datetime',
    ];

    // get -set attribute
    public function getBirthdayAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

  

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

 

    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

   

    // relationship
    public function classes(){
        return $this->belongsToMany(Classes::class,'student_class','student_id','class_id')->where('status', 1);
    }

    public function lessons(){
        return $this->belongsToMany(Lesson::class,'student_lesson','student_id','lesson_id')->where('status', 1);
    }

    public function countLessonInClass(){
        return DB::table('students')
        ->leftJoin('student_class', 'student_class.student_id', '=', 'students.id')
        ->leftJoin('classes', 'classes.id', '=', 'student_class.class_id')
        ->leftJoin('lessons', 'lessons.class_id', '=', 'classes.id')
        ->where('students.id', $this->id)
        ->where('students.status', 1)
        ->where('classes.status', 1)
        ->where('lessons.status', 1)
        ->count();
    }

    public static function search($keyword,$records_per_page){
        $result = Student::where(function($query) use ($keyword) {
            $query->where('name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
        })->where('status', 1)->orderBy('created_at','desc')->paginate($records_per_page);;
        
        return $result;
    }

    public static function getStudents(){
        return Student::where('status',1);
    }

    public static function getItemById($id){
       $result = Student::where('status',1)->where('id',$id)->first();
       if($result){
        return $result;
       }
       return null;
    }

    public static function getStudentInLessonDetail($lessonId, $keyword, $records_per_page){
        return Student::whereHas('classes', function ($query) use ($lessonId) {
            $query->whereHas('lessons', function ($query) use ($lessonId) {
                $query->where('id', $lessonId);
            });
        })
        ->orderBy('code')
        ->where('name', 'LIKE', "%$keyword%")
        ->paginate($records_per_page);
    }

    public static function searchStudentsInClass($keyword, $classId, $records_per_page){
        return Student::where('name', 'like', '%' . $keyword . '%')
        ->whereHas('classes', function ($query) use ($classId) {
        $query->where('class_id', $classId);
        })
        ->orderBy('code','asc')->paginate($records_per_page);
    }

    public static function getAllStudent(){
        return Student::where('status',1)->orderBy('code','asc')->get();
    }

    public static function getAvailStudents($classId, $keyword, $records_per_page){
       return DB::table('students')
        ->whereNotIn('id', function ($query) use ($classId) {
            $query->select('student_id')
                ->from('student_class')
                ->where('class_id', $classId);
        })
        ->where('status',1)
        ->where('name','like',"%$keyword%")
        ->orderBy('code','asc')
        ->paginate($records_per_page);
    }
}
