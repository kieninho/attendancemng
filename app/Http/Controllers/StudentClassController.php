<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentClassController extends Controller
{
    public function index(Request $request, $classId){

        $user = Auth::user();

        $classes = Classes::getClassesByUser($user);
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

    public function add(Request $request, $classId){
        $user = Auth::user();
        
        $classes = Classes::getClassesByUser($user);

        $class = Classes::findOrFail($classId);
        // $students = Student::where('status',1)->get();
        // $listIdStudentinClass = StudentClass::where('class_id',$classId)->get();

        // $availStudents = $students->reject(function ($student) use ($listIdStudentinClass) {
        //     return $listIdStudentinClass->contains($student['id']);
        // });
        $keyword = $request->input('keyword');

        $records_per_page = 10;

        $availStudents = DB::table('students')
        ->whereNotIn('id', function ($query) use ($classId) {
            $query->select('student_id')
                ->from('student_class')
                ->where('class_id', $classId);
        })
        ->where('status',1)
        ->where('name','like',"%$keyword%")
        ->orderBy('code','asc')
        ->paginate($records_per_page);

        return view('studentclass.add',compact('keyword','class','availStudents','classes'));
    }


    public function store($classId, $studentId){
        $class = Classes::where('id',$classId)->where('status',1)->First(); 
        $student = Student::where('id',$studentId)->where('status',1)->First();

        if( $class &&  $student){
            StudentClass::create([
                'student_id'=>$studentId,
                'class_id'=>$classId,
            ]);

            $message = "Thêm thành công $student->name vào lớp!!!";
        }
        else{
            $message = "Thêm vào lớp không thành công !!!";
        }

        return redirect()->back()->withErrors($message);
    }
}
