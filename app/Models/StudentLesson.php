<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLesson extends Model
{
    use HasFactory;

    protected $table = 'student_lesson';
    protected $fillable = [
        'student_id',
        'lesson_id',
    ];

    //get-set format 
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
        $this->attributes['updated_at'] = date('H:i:s d/m/y', strtotime($value));
    }
}
