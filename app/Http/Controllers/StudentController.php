<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\helper;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::where('status','=',1)->get();
        return view('student.index',compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|min:3|max:255',
                'email' => 'required|string|email|max:255'
            ],
            [
                'name.required' => 'Tên lớp không được bỏ trống',
                'name.string' => 'Nhập tên lớp là chữ cái',
                'name.min' => 'Tên lớp phải nhiều hơn 3 kí tự',
            ],
            ['stopOnFirstFailure' => true]
        );

        $listExitsCode = Student::pluck('code')->all();

        $data = $request->all();
        $data['status'] = 1;
        $data['code'] = helper::genCode('SV',$listExitsCode);

        $result = Student::create($data);

        if($result){
            return redirect()->back();
        }
        
    }

    public function delete($id){
        $student = Student::findOrFail($id);
        if($student){
            $student->status = 0;
        }
        $student->save();
        return redirect()->back();
    }

    public function get($id){
        $data = Student::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request){

        $data = $request->all();
        $record = Student::findOrFail($data['studentId']);
        if(isset($record)){
            $request->validate(
                [
                    'name' => 'required|string|min:3|max:255',
                    'email' => 'required|string|email|max:255'
                ],
                [
                    'name.required' => 'Tên lớp không được bỏ trống',
                    'name.string' => 'Nhập tên lớp là chữ cái',
                    'name.min' => 'Tên lớp phải nhiều hơn 3 kí tự',
                ],
                ['stopOnFirstFailure' => true]
            );
            $record->fill([
                'name'=>$data['name'],
                'email'=>$data['email'],
                'birthday'=>$data['birthday'],
            ]);

            $record->save();
        }

        return redirect()->back();
    }
}
