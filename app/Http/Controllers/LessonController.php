<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\TeacherLesson;
use App\Models\StudentLesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Input\Input;
use Whoops\Run;
use Illuminate\Support\Facades\DB;


class LessonController extends Controller
{

    public function classLesson(Request $request,$classId)
    {
        $user = Auth::user();
        if ($user->is_teacher) {
            $classes = $user->lessons->map(function ($lesson) {
                return $lesson->classes;
            });
        } else {
            $classes = Classes::where('status', 1)->orderBy('name','asc')->get();
        }

        $records_per_page = 10;

        $keyword = $request->input('keyword');
        
        $class = Classes::where('id',$classId)->where('status',1)->first();

        $lessons = Classes::searchLesson($classId,$keyword);
        $teachers = User::where('is_teacher',1)->where('status',1)->get();

        return view('lesson.classLesson', compact('lessons', 'classes','class','keyword','teachers'));
    }


    public function store(Request $request,$classId){
        $request->validate(
            [
                'name' => 'required',
                'start' => 'required',
                'end' => 'required',
                'date' => 'required',
            ],
            [
                'name.required' => 'Tên không được bỏ trống',
            ]
        );
        $data = $request->all();
        $startStr = $data['start']." ".$data['date'];
        $endStr = $data['end']." ".$data['date'];

        $data['start_at']  = Carbon::createFromFormat('H:i d/m/Y', $startStr)->toDateTime();
        $data['end_at'] = Carbon::createFromFormat('H:i d/m/Y', $endStr)->toDateTime();
        $result = Lesson::create($data);
        if ($result) {
            $teacher_ids = $request->input('teacher_ids');
            if(!empty($teacher_ids)){
                foreach($teacher_ids as $teacher_id){
                    TeacherLesson::create([
                        'teacher_id'=>$teacher_id,
                        'lesson_id'=>$result->id,
                    ]);
                }
            }

            // Tự động thêm các học sinh trong lớp vào tiết học
            $class = Classes::findOrFail($classId);
            $studentsInClass = $class->students;

            foreach($studentsInClass as $student){
                StudentLesson::create([
                    'student_id'=>$student->id,
                    'lesson_id'=>$result->id,
                ]);
            }

            $message = 'Thêm mới thành công!';
        } else {
            $message = 'Thêm mới không thành công!';
        }
        
        return redirect()->back()->withErrors($message);
    }

    public function detail(Request $request,$id){

        $user = Auth::user();
        if ($user->is_teacher) {
            $classes = $user->lessons->map(function ($lesson) {
                return $lesson->classes;
            });
        } else {
            $classes = Classes::where('status', 1)->orderBy('created_at','asc')->get();
        }


        $records_per_page = 10;

        $keyword = $request->input('keyword');

        $lesson = Lesson::findOrFail($id);
        $students = Student::whereHas('classes', function ($query) use ($id) {
            $query->whereHas('lessons', function ($query) use ($id) {
                $query->where('id', $id);
            });
        })
        ->where('name', 'LIKE', "%$keyword%")
        ->paginate($records_per_page);

        
        return view('lesson.detail',compact('students','lesson','classes','keyword'));
    }

    public function delete($id)
    {
        $lesson = Lesson::findOrFail($id);
        if ($lesson) {
            $lesson->status = 0;
        }
        $lesson->save();
        $message = "Xóa thành công !";
        return redirect()->back()->withErrors($message);
    }

    public function get($id){
        $data = Lesson::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request)
    {

        $data = $request->all();
        $record = Lesson::findOrFail($data['lessonId']);

        if (isset($record)) {
            $request->validate([
                'name' => 'required',
                'start' => 'required',
                'end' => 'required',
                'date' => 'required',
            ], [
                'name.required' => 'Tên không được bỏ trống!',
                'name.min' => 'Tên phải nhiều hơn 3 kí tự!',
            ]);

            $startStr = $data['start']." ".$data['date'];
            $endStr = $data['end']." ".$data['date'];
    
            $record->start_at  = Carbon::createFromFormat('H:i d/m/Y', $startStr)->toDateTime();
            $record->end_at = Carbon::createFromFormat('H:i d/m/Y', $endStr)->toDateTime();
            $record->name = $data['name'];
            $record->description = $data['description'];

            $record->save();

            $teacher_ids = $request->input('teacher_ids');
            TeacherLesson::Where('lesson_id',$record->id)->delete();
            if(!empty($teacher_ids)){
                foreach($teacher_ids as $teacher_id){
                    TeacherLesson::create([
                        'teacher_id'=>$teacher_id,
                        'lesson_id'=>$record->id,
                    ]);
                }
            }

            $message = "Chỉnh sửa thành công!";

        }
        else{
            $message = "Chỉnh sửa không thành công!";
        }


        return redirect()->back()->withErrors($message);
    }

    public function getTeacherLesson($id){
        $data = TeacherLesson::where('lesson_id',$id)->get();
        return response()->json($data);
    }

    public function attend($lessonId,$studentId){
        $checkExits = StudentLesson::where('lesson_id',$lessonId)->where('student_id',$studentId)->get()->isEmpty();
        if($checkExits){
            $data = ['lesson_id'=>$lessonId,'student_id'=>$studentId];
            StudentLesson::create($data);

            $countStudentInLesson = Lesson::findOrFail($lessonId)->students->count();

            return response()->json($countStudentInLesson);
        }
    }
    public function leave($lessonId,$studentId){
        StudentLesson::where('lesson_id',$lessonId)->where('student_id',$studentId)->delete();

        $countStudentInLesson = Lesson::findOrFail($lessonId)->students->count();

        return response()->json($countStudentInLesson);
    }

}
