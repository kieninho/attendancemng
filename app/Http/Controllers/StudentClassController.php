<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentClassExport;

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

        $students = Student::searchStudentsInClass($keyword, $classId, $records_per_page);
        
        $allStudent = Student::getAllStudent();
        return view('studentclass.index',compact('students','class','keyword','allStudent','classes'));
    }

    public function update(Request $request, $classId){
        $data = $request->all();
        $student_ids = $data['student_ids'];

        StudentClass::deleteByClassId($classId);

        StudentClass::addListStudentClass($classId,$student_ids);

        $message = "Cập nhật thành công!";
        return redirect()->back()->withErrors($message);
    }

    public function delete($classId, $studentId){
        StudentClass::deleteItem($classId, $studentId);
        StudentLesson::deleteByStudentAndClass($studentId, $classId);

        $message = "Xóa sinh viên khỏi lớp thành công!";
        return redirect()->back()->withErrors($message);
    }

    public function add(Request $request, $classId){
        $user = Auth::user();
        
        $classes = Classes::getClassesByUser($user);

        $class = Classes::findOrFail($classId);

        $keyword = $request->input('keyword');

        $records_per_page = 10;

        $availStudents = Student::getAvailStudents($classId, $keyword, $records_per_page);

        return view('studentclass.add',compact('keyword','class','availStudents','classes'));
    }


    public function store($classId, $studentId){
        $class = Classes::getItemById($classId);
        $student = Student::getItemById($studentId);

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

        return redirect(route('studentInClass',['classId'=>$classId]))->withErrors($message);
    }

    public function addMulti(Request $request, $classId){
        $studentIds = $request->input('item_ids');

        $countStd = count($studentIds);
        
        if($countStd <= 0 ){
            $message = "Thêm vào lớp không thành công !!!";

            return redirect(route('studentInClass',['classId'=>$classId]))->withErrors($message);
        }


        foreach($studentIds as $studentId){
            StudentClass::create([
                'student_id'=>$studentId,
                'class_id'=>$classId,
            ]);
        }
        $message = "Thêm thành công $countStd sinh viên vào lớp !!!";

        return redirect(route('studentInClass',['classId'=>$classId]))->withErrors($message);
    }

    public function export($classId){
        $students = StudentClass::getStudentsInClass($classId);

        return Excel::download(new StudentClassExport($students, $classId), "StudentInClass$classId.xlsx");
    }

}
