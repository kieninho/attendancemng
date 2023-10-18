<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentClassController extends Controller
{
    public function index(Request $request, $classId){

        $user = Auth::user();
        if ($user->is_teacher) {
            $classes = $user->lessons->map(function ($lesson) {
                return $lesson->classes;
            });
        } else {
            $classes = Classes::where('status', 1)->orderBy('name','asc')->get();
        }

        $keyword = $request->input('keyword');

        $records_per_page = 10;

        $class = Classes::findOrFail($classId);
        if(!$class){
            abort(404);
        }

        $students = Student::where('name', 'like', '%' . $keyword . '%')
        ->whereHas('classes', function ($query) use ($classId) {
        $query->where('class_id', $classId);
        })
        ->orderBy('code','asc')->paginate($records_per_page);

        $allStudent = Student::where('status',1)->orderBy('code','asc')->get();
        return view('studentclass.index',compact('students','class','keyword','allStudent','classes'));
    }

    public function update(Request $request, $classId){
        $data = $request->all();
        $student_ids = $data['student_ids'];

        StudentClass::where('class_id',$classId)->delete();

        foreach($student_ids as $student_id){
            StudentClass::create([
                'student_id'=>$student_id,
                'class_id'=>$classId
            ]);
        }
        $message = "Cập nhật thành công!";
        return redirect()->back()->withErrors($message);
    }

    public function delete($classId, $studentId){
        StudentClass::where('class_id',$classId)->where('student_id',$studentId)->delete();

        $message = "Xóa sinh viên khỏi lớp thành công!";
        return redirect()->back()->withErrors($message);
    }
}
