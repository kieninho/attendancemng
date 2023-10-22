<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\helper;
use Illuminate\Support\Carbon;


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
            ]
        );

        $listExitsCode = Student::pluck('code')->all();

        $data = $request->all();
        $data['status'] = 1;
        $data['code'] = helper::genCode('SV', $listExitsCode);
        $data['birthday'] = Carbon::createFromFormat('d/m/Y', $data['birthday'])->toDateTime();

        $result = Student::create($data);

        if ($result) {
            $message = "Thêm mới thành công!";
            return redirect()->back()->withErrors($message);
        }
    }

    public function delete($id)
    {
        $student = Student::findOrFail($id);
        if ($student) {
            $student->status = 0;
        }
        $student->save();
        return redirect()->back();
    }

    public function get($id)
    {
        $data = Student::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request)
    {

        $data = $request->all();

        $record = Student::findOrFail($data['studentId']);

        if (isset($record)) {
            $request->validate(
                [
                    'name' => 'required|string|min:3|max:100',
                    'email' => 'required|string|email|max:150'
                ],
                [
                    'name.required' => 'Tên lớp không được bỏ trống',
                    'name.string' => 'Nhập tên lớp là chữ cái',
                    'name.min' => 'Tên lớp phải nhiều hơn 3 kí tự',
                ],
                ['stopOnFirstFailure' => true]
            );
            $record->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'birthday' => Carbon::createFromFormat('d/m/Y', $data['birthday'])->toDateTime(),
            ]);

            $record->save();
            $message="Cập nhật thành công!";
        }

        return redirect()->back()->withErrors($message);
    }

    public function detail(Request $request, $id){

        $student = Student::where('id',$id)->where('status',1)->first();
        $keyword = $request->input('keyword');

        if(!$student){
            abort(404);
        }
        $classes = $student->classes()->where('name','like',"%$keyword%")->paginate(10);
        
        return view('student.detail',compact('student','classes','keyword'));
    }
}
