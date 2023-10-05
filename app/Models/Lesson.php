<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;
use App\Models\User;
use App\Models\Student;


class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';
    protected $fillable = [
        'class_id',
        'teacher_id',
        'name',
        'start_at',
        'end_at',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    //get-set format
    public function getStartAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setStartAtAttribute($value)
    {
        $this->attributes['start_at'] = date('H:i:s d/m/y', strtotime($value));
    }

    public function getEndAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setEndAtAttribute($value)
    {
        $this->attributes['end_at'] = date('H:i:s d/m/y', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = date('H:i:s d/m/y', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setUpdatedAtAttribute($value)
    {
        return $this->attributes['updated_at'] = date('H:i:s d/m/y', strtotime($value));
    }

    public function classes(){
        return $this->belongsTo(Classes::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class,'student_lesson','lesson_id','student_id');
    }

    public function teachers(){
        return $this->belongsToMany(User::class,'teacher_lesson','lesson_id','teacher_id');
    }
}
