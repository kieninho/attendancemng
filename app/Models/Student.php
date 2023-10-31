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

  

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

 

    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

   

    // relationship
    public function classes(){
        return $this->belongsToMany(Classes::class,'student_class','student_id','class_id')->where('status', 1);
    }

    public function lessons(){
        return $this->belongsToMany(Lesson::class,'student_lesson','student_id','lesson_id')->where('status', 1);
    }

    public function studentlessons(){
        return $this->hasMany(StudentLesson::class,'student_id');
    }

    public static function search($keyword,$records_per_page){
        $result = Student::where(function($query) use ($keyword) {
            $query->where('name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
        })->where('status', 1)->orderBy('created_at','desc')->paginate($records_per_page);;
        
        return $result;
    }

    public static function getStudents(){
        return Student::where('status',1)->orderBy('code','asc');
    }

    public static function getItemById($id){
       $result = Student::where('status',1)->where('id',$id)->first();
       if($result){
        return $result;
       }
       return null;
    }

    public static function getStudentInLessonDetail($lessonId, $keyword, $records_per_page){
        return Student::whereHas('studentlessons', function ($query) use ($lessonId) {
            $query->where('lesson_id', $lessonId);
        })
        ->orderBy('code')
        ->where('name', 'LIKE', "%$keyword%")
        ->paginate($records_per_page);
    }

    public static function getStudentInLesson($lessonId){
        return Student::whereHas('studentlessons', function ($query) use ($lessonId) {
            $query->where('lesson_id', $lessonId);
        })
        ->orderBy('code')
        ->get();
    }

    public static function searchStudentsInClass($keyword, $classId, $records_per_page){
        return Student::where('name', 'like', '%' . $keyword . '%')
        ->whereHas('classes', function ($query) use ($classId) {
        $query->where('class_id', $classId);
        })
        ->orderBy('code','asc')->paginate($records_per_page);
    }

    public static function getAllStudent(){
        return Student::where('status',1)->orderBy('code','asc')->get();
    }

    public static function getAvailStudents($classId, $keyword, $records_per_page){
       return DB::table('students')
        ->whereNotIn('id', function ($query) use ($classId) {
            $query->select('student_id')
                ->from('student_class')
                ->where('class_id', $classId);
        })
        ->where('status',1)
        ->where('name','like',"%$keyword%")
        ->orderBy('code','asc')
        ->paginate($records_per_page);
    }

    // trả về tỷ lệ chuyên cần của sinh viên trong các lớp
    public function attendRate(){
        $countAttend = StudentLesson::where('student_id',$this->id)
        ->whereHas('lesson', function ($query) {
            $query->where('start_at','<', now());
            })
        ->where(function ($query) {
            $query->where('status', 1)
                  ->orWhere('status', 2);
        })
        ->count();
        if($countAttend==0)
        {
            return 0;
        }
        $countLesson = StudentLesson::where('student_id',$this->id)
        ->whereHas('lesson', function ($query) {
            $query->where('start_at','<', now());
            })
        ->count();

        $result = round(($countAttend/$countLesson)*100);
        return $result;
    }

    // trả về tỷ lệ chuyên cần của sinh viên trong 1 lớp
    public function classAttendRate($classId){
        $countAttend = $this->countAttendInClass($classId);
        if($countAttend==0)
        {
            return 0;
        }
        $countLesson = $this->countLessonInClass($classId);

        $result = round(($countAttend/$countLesson)*100);
        return $result;
    }

    // trả về số tiết học mà 1 sinh viên tham gia trong 1 lớp
    public function countAttendInClass($classId){
        $lessons = Lesson::where('class_id',$classId)
        ->where('status', 1)
        ->get();
        $result = 0;
        foreach($lessons as $lesson){
            $result += StudentLesson::where('student_id',$this->id)
            ->whereHas('lesson', function ($query) {
                $query->where('start_at','<', now());
                })
            ->where(function ($query) {
                $query->where('status', 1)
                      ->orWhere('status', 2);
            })
            ->where('lesson_id',$lesson->id)->count();
        }
        return $result;
    }

    // trả về tổng số lesson mà sinh viên đã tham gia trong 1 class (cả nghỉ và đi)
    public function countLessonInClass($classId){
        $lessons = Lesson::where('class_id',$classId)->where('status',1)->get();
        $result = 0;
        foreach($lessons as $lesson){
            $result+= StudentLesson::where('student_id',$this->id)
            ->whereHas('lesson', function ($query) {
                $query->where('start_at','<', now());
                })
            ->where('lesson_id',$lesson->id)
            ->count();
        }
        return $result;
    }

    // trả về trạng thái của sinh viên trong 1 lesson
    public function checkAttendLesson($lessonId){
        $result = StudentLesson::where('student_id',$this->id)->where('lesson_id',$lessonId)->first();
        if ($result) {
            return (int) $result->status;
        }
        return 0;
    }

    public function searchJoinClass($keyword,$records_per_page){
        return $this->classes()->where('name','like',"%$keyword%")->paginate($records_per_page);
    }

    public function getJoinClasses(){
        return $this->classes()->get();
    }

    public function getJoinDate($classId){
        return StudentClass::where('student_id',$this->id)->where('class_id', $classId)->first()->created_at;
    }

    public function attendString($lessonId){
        $status = StudentLesson::where('student_id',$this->id)->where('lesson_id',$lessonId)->value('status');
        if($status==0){
            return "Nghỉ không phép";
        }
        else if($status==1){
            return "Tham gia";
        }
        else if($status==2){
            return "Nghỉ có phép";
        }
        return "";
    }
}
