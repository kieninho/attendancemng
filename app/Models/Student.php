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

    // public function setBirthdayAttribute($value)
    // {
    //     $this->attributes['birthday'] = date(' d/m/yy', strtotime($value));
    // }

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
    //     $this->attributes['updated_at'] = date('H:i:s d/m/y', strtotime($value));
    // }

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

    public static function search($keyword){
        $result = Student::where(function($query) use ($keyword) {
            $query->where('name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
        })->where('status', 1);
        
        return $result;
    }

    
}
