<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;

    protected $table = 'student_class';
    protected $fillable = [
        'student_id',
        'class_id',
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

    public static function deleteByClassId($classId){
        StudentClass::where('class_id',$classId)->delete();
    }

    public static function addListStudentClass($classId, $student_ids){
        foreach($student_ids as $student_id){
            StudentClass::create([
                'student_id'=>$student_id,
                'class_id'=>$classId
            ]);
        }
    }

    public static function deleteItem($classId, $studentId){
        StudentClass::where('class_id',$classId)->where('student_id',$studentId)->delete();
    }

    public static function deleteByClass($classId){
        StudentClass::where('class_id',$classId)->delete();
    }

    public static function getStudentsInClass($classId){
        $class = Classes::findOrFail($classId);
        return $class->students;
    }
}
