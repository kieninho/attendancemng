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

    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    
    // Relationship
    public function students(){
        return $this->belongsToMany(Student::class,'student_class','class_id','student_id')->where('status', 1);
    }

    public function lessons(){
        return $this->hasMany(Lesson::class,'class_id')->where('status', 1);
    }
    
    public static function search($keyword, $records_per_page){
        $result = Classes::where(function($query) use ($keyword) {
            $query->where('name','like',"%$keyword%")
                  ->orWhere('description','like',"%$keyword%");
        })->where('status',1)->orderBy('created_at','desc')->paginate($records_per_page);
        
        return $result;
    }

    public static function searchLesson($classId,$keyword, $records_per_page){
        $class = Classes::findOrFail($classId);
        if(!$class){
            return;
        }
        
        $lessons = $class->lessons()->where('name','like',"%$keyword%")->orderBy('start_at','asc')->paginate($records_per_page);

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

    public static function getClassesByUser($user){
        if ($user->is_teacher) {
            $classes = $user->lessons->map(function ($lesson) {
                return $lesson->classes;
            });
        } else {
            $classes = Classes::where('status', 1)->orderBy('name','asc')->get();
        }
        return $classes;
    }


    public static function getClass(){
        return Classes::where('status',1);
    }

    public static function getItemById($id){
        $result = Classes::where('status',1)->where('id',$id)->first();
        if($result){
         return $result;
        }
        return null;
     }

    public static function getClassById($classId){
        $class = Classes::where('id',$classId)->where('status',1)->first();
        if($class){
            return $class;
        }
        return null;
    }

    // Trả về tỷ lệ tham gia điểm danh của lớp
    public function getAverageAttendance(){

        $Lessons =  Classes::findOrFail($this->id)->lessons->where('start_at','<',now());
        $countAttend = 0;
        $countStudentInLesson = 0;
        foreach($Lessons as $lesson){
            $countAttend += $lesson->countAttend();
            $countStudentInLesson += $lesson->countStudent();
        }

       if($countStudentInLesson==0){
        return 0;
       }
        
        return round(($countAttend/ $countStudentInLesson) *100);
    }

    public static function getAllClass(){
        return Classes::where('status',1)->orderBy('code','asc')->get();
    }

    public function countLesson(){
        $lessons = $this->lessons;
        $count = count($lessons);
        if($count){
            return $count;
        }
        else{
            return "0";
        }
    }

    public function countStudent(){
        $students = $this->students;
        $count = count($students);

        if($count){
            return $count;
        }
        else{
            return "0";
        }
    }

    // tra ve so buoi hoc cua sinh vien co the di trong 1 lop
    public function countLessonWithStudentId($studentId){
        $student = Student::where('id',$studentId)->first();
        $result = $student->countLessonInClass($this->id);
        return $result;
    }

    public function countAttendWithStudentId($studentId){
        $student = Student::where('id',$studentId)->first();
        $result = $student->countAttendInClass($this->id);
        return $result;
    }

    public function startDay(){
        $lesson = $this->lessons->sortBy('start_at')->first();

        if($lesson == null){
            return "";
        }

        if($lesson->start_at == null){
            return "";
        }
        return $lesson->start_at->format('d/m/Y');
    }

    public function endDay(){
        $lesson = $this->lessons->sortBy('start_at')->last();

        if($lesson == null){
            return "";
        }
        
        if($lesson->start_at == null){
            return "";
        }
        return $lesson->start_at->format('d/m/Y');
    }

    public function getStatus(){
        $lesson = $this->lessons->sortBy('start_at')->first();
        if($lesson == null){
            return "Chưa bắt đầu";
        }
        
        $start =  $lesson->start_at;
        $end = $this->lessons->sortBy('start_at')->last()->start_at;

        if($start > now()){
            return "Chưa bắt đầu";
        }else if( $start <= now() && $end > now()){
            return "Hoạt động";
        }
        else{
            return "Kết thúc";
        }
    }

    public function getTeachersStringByClass(){
        $class = Classes::where('id',$this->id)->first();
        $lessons = $class->lessons;

        if(empty($lessons)){
            return "";
        }

        $teachers = $lessons->flatMap(function ($lesson) {
            return $lesson->teachers;
        })->unique('id');
        $result = "";
        
        if(empty($teachers)){
            return "";
        }

        foreach($teachers as $teacher){
            $result = $result . $teacher->name.", ";
        }
        $result = substr($result, 0, -2);
        return $result;
    }

}
