<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentLesson;
use App\Models\StudentClass;
use App\Services\helper;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentExport;
use App\Exports\StudentDetailExport;


class StudentController extends Controller
{
    public function index(Request $request)
    {

        $records_per_page = 10;

        $keyword = $request->input('keyword');

        $students = Student::search($keyword, $records_per_page);
        
        $students->appends(['keyword' => $keyword]);
    
        return view('student.index', compact('students','keyword'));
    }

    public function store(StudentRequest $request)
    {
        $request->validated();

        $listExitsCode = Student::pluck('code')->all();

        $data = $request->all();
        $data['status'] = 1;
        $data['code'] = helper::genCode('SV', $listExitsCode);
        if(!empty($data['birthday'])){
            $data['birthday'] = Carbon::createFromFormat('Y-m-d', $data['birthday'])->toDateTime();
        }
        $result = Student::create($data);

        if ($result) {
            $message = "Thêm mới thành công!";
            return redirect()->back()->withErrors($message);
        }
    }

    public function delete($id)
    {
        StudentLesson::deleteByStudentId($id);
        StudentClass::deleteByStudentId($id);
        Student::deleteItem($id);
        $message = "Xóa thành công!";
        return redirect()->back()->withErrors($message);
    }

    public function get($id)
    {
        $data = Student::findOrFail($id);

        return response()->json($data);
    }

    public function update(StudentRequest $request)
    {

        $data = $request->all();

        $record = Student::findOrFail($data['studentId']);

        if (isset($record)) {
            $request->validated();
            $record->fill([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            if(!empty($data['birthday'])){
                $data['birthday'] = Carbon::createFromFormat('Y-m-d', $data['birthday'])->toDateTime();
            }
            $record->birthday = $data['birthday'];

            $record->save();
            $message="Cập nhật thành công!";
        }

        return redirect()->back()->withErrors($message);
    }

    public function detail(Request $request, $id){

        $records_per_page =10;
        $student = Student::where('id',$id)->where('status',1)->first();
        $keyword = $request->input('keyword');

        if(!$student){
            abort(404);
        }
        $classes = $student->searchJoinClass($keyword, $records_per_page);
        $classes->appends(['keyword' => $keyword]);
        return view('student.detail',compact('student','classes','keyword'));
    }

    public function export(){

        $students = Student::getStudents()->get();

        return Excel::download(new StudentExport($students), 'students.xlsx');
    }

    public function exportDetail($id){
        $student = Student::where('id',$id)->where('status',1)->first();
        $classes = $student->getJoinClasses();
        return Excel::download(new StudentDetailExport($student,$classes), "StudentDetail$id.xlsx");
    }

    public function deleteMulti(Request $request){
        $studentIds = $request->input('item_ids');
        $countStd = count($studentIds);

        if($countStd <= 0 ){
            $message = "Thao tác không thành công !!!";

            return redirect()->back()->withErrors($message);
        }

        foreach($studentIds as $studentId){
            StudentLesson::deleteByStudentId($studentId);
            StudentClass::deleteByStudentId($studentId);
            Student::deleteItem($studentId);
        }

        $message = "Xóa thành công $countStd sinh viên !!!";
        return redirect()->back()->withErrors($message);
    }
}
