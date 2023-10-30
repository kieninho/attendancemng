<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;




class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';
    protected $fillable = [
        'class_id',
        'teacher_id',
        'name',
        'description',
        'start_at',
        'end_at',
        'status',
        'checked_attendance',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function getStartAndEnd()
    {
        $start = Carbon::parse($this->start_at)->format('H:i');
        $end = Carbon::parse($this->end_at)->format('H:i');
        $date = Carbon::parse($this->start_at)->format('d/m/Y');

        return $start . ' - ' . $end . ' ' . $date;
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }


    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class,'class_id')->where('status', 1);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_lesson', 'lesson_id', 'student_id')->where('status', 1);
    }

    public function studentlessons(){
        return $this->hasMany(StudentLesson::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_lesson', 'lesson_id', 'teacher_id')->where('status', 1);
    }

    // method
    public static function search($keyword){
        $result = Lesson::where('name','like',"%$keyword%")->orWhere('description','like',"%$keyword%");
        return $result;
    }

    public function getStudentsInLesson()
    {
        $result =  DB::table('students')
        ->leftJoin('student_class', 'student_class.student_id', '=', 'students.id')
        ->leftJoin('classes', 'classes.id', '=', 'student_class.class_id')
        ->leftJoin('lessons', 'lessons.class_id', '=', 'classes.id')
        ->leftJoin('student_lesson', 'student_lesson.lesson_id', '=', 'lessons.id')
        ->where('students.status', 1)
        ->where('classes.status', 1)
        ->where('lessons.status', 1)
        ->whereRaw('student_lesson.created_at > student_class.created_at')
        ->where('lessons.id', $this->id)
        ->orderBy('students.id')
        ->distinct();

        return $result;
        
    }

    public static function deleteByClassId($classId){
        Lesson::where('class_id',$classId)->delete();
    }

    // trả về số sinh viên tham gia và xin nghỉ buổi học đó
    public function countAttend(){
        return StudentLesson::where('lesson_id',$this->id)
        ->where(function ($query) {
            $query->where('status', 1)
                  ->orWhere('status', 2);
        })
        ->count();
    }

    public function countNumberAttend(){
        return StudentLesson::where('lesson_id',$this->id)
        ->where('status', 1)
        ->count();
    }

    public function countStudent(){
        return StudentLesson::where('lesson_id',$this->id)->count();
    }

    public static function findAfterLesson($classId){
        return Lesson::where('class_id', $classId)
                ->where('start_at', '>', now())
                ->get();
    }

    public function getAttendRate(){
        if($this->countStudent()==0){
            return 0;
        }

        return round(($this->countAttend()/$this->countStudent()) * 100);
    }

    public function checkedAttendance(){
        $result = Lesson::where('id',$this->id)->first()->checked_attendance;
        if( $result==0 ){
            return false;
        }
        return true;
    }
}
