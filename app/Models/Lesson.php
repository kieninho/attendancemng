<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Carbon;




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
}
