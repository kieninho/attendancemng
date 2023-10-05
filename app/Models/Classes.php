<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Lesson;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';
    // attribute
    protected $fillable = [
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
    
    // Relationship
    public function students(){
        return $this->belongsToMany(Student::class,'student_class','class_id','student_id');
    }

    public function lessons(){
        return $this->hasMany(Lesson::class);
    }
    

}
