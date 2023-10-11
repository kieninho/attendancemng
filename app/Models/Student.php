<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;
use App\Models\Lesson;


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
        return $this->belongsToMany(Classes::class,'student_class','student_id','class_id');
    }

    public function lessons(){
        return $this->belongsToMany(Lesson::class,'student_lesson','student_id','lesson_id');
    }

}
