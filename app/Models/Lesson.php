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
        ->select('students.*')
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

    public function countStudentsInLesson()
    {
        $sqlResult =  DB::table('students')
        ->select('students.*')
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
        ->distinct()
        ->get()
        ;

        return count($sqlResult);
        
    }
}
