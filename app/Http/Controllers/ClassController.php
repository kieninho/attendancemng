<?php

namespace App\Http\Controllers;

use Facade\FlareClient\View;
use Illuminate\Http\Request;
use App\Services\helper;
use App\Models\Classes;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $records_per_page = 10;

        $keyword = $request->input('keyword');

        $classes = Classes::search($keyword, $records_per_page);

        $classes->appends(['keyword' => $keyword]);

        return view('class.index',compact('classes','keyword'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|min:2',
            ],
            [
                'name.required' => 'Tên lớp không được bỏ trống',
                'name.string' => 'Nhập tên lớp là chữ cái',
                'name.min' => 'Tên lớp phải nhiều hơn 2 kí tự',
            ],
            ['stopOnFirstFailure' => true]
        );

        $listExitsCode = Classes::pluck('code')->all();

        $data = $request->all();
        $data['status'] = 1;
        $data['code'] = helper::genCode('CL',$listExitsCode);

        $result = Classes::create($data);

        if($result){
            return redirect()->back();
        }
        
    }

    public function delete($id){
        $class = Classes::findOrFail($id);
        
        if($class){
            $class->status = 0;
            $class->save();
            $lessons = $class->lessons;
            foreach($lessons as $lesson){
                $lesson->status = 0;
                $lesson->save();
            }
            $message="Xóa thành công!";
        }
        else{
            $message = "Xóa không thành công!";
        }
        
    
        return redirect()->back()->withErrors($message);
    }

    public function getClass($id){
        $data = Classes::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request){

        $data = $request->all();
        $record = Classes::findOrFail($data['classId']);

        if(isset($record)){
            $request->validate(
                [
                    'name' => 'required|string|min:2',
                ],
                [
                    'name.required' => 'Tên lớp không được bỏ trống',
                    'name.string' => 'Nhập tên lớp là chữ cái',
                    'name.min' => 'Tên lớp phải nhiều hơn 2 kí tự',
                ],
                ['stopOnFirstFailure' => true]
            );
            $record->id = $data['classId'];
            $record->name = $data['name'];
            $record->description = $data['description'];
            $record->save();

            $message = "Cập nhật thành công!";
        }

        return redirect()->back()->withErrors($message);
    }
}
