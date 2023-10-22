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
        
        $classes = Classes::getClassesByUser($user);

        $records_per_page = 10;

        $keyword = $request->input('keyword');
        
        $class = Classes::getClassById($classId);

        $lessons = Classes::searchLesson($classId,$keyword);
        
        $teachers = User::getTeachers()->get();

        return view('lesson.classlesson', compact('lessons', 'classes','class','keyword','teachers'));
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

        $records_per_page = 10;

        $keyword = $request->input('keyword');
        $lesson = Lesson::findOrFail($id);
        if(!$lesson){
            abort(404);
        }
        $lessons = $lesson->classes->lessons;
        $students = Student::getStudentInLessonDetail($id,$keyword,$records_per_page);
        // $students = $lesson->getStudentsInLesson()->orderBy('code')->paginate($records_per_page); -- e chua xoa di do van chua xong hoan toan
        
        return view('lesson.detail',compact('students','lessons','lesson','keyword'));
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
            TeacherLesson::deleteByLessonId($record->id);
            
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
        $data = TeacherLesson::getItemById($id);
        return response()->json($data);
    }

    public function attend($lessonId,$studentId){
        $checkExits = StudentLesson::checkExits($lessonId, $studentId);
        if($checkExits){
            $data = ['lesson_id'=>$lessonId,'student_id'=>$studentId];
            StudentLesson::create($data);

            $countStudentInLesson = Lesson::findOrFail($lessonId)->students->count();

            return response()->json($countStudentInLesson);
        }
    }
    public function leave($lessonId,$studentId){
        StudentLesson::deleteItem($lessonId,$studentId);

        $countStudentInLesson = Lesson::findOrFail($lessonId)->students->count();

        return response()->json($countStudentInLesson);
    }

}
