<?php

namespace App\Http\Controllers;

use Facade\FlareClient\View;
use Illuminate\Http\Request;
use App\Services\helper;
use App\Models\Classes;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::where('status','=',1)->get();
        return view('class.index',compact('classes'));
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
        }
        $class->save();
        return redirect()->back();
    }

    public function getClass($id){
        $data = Classes::findOrFail($id);

        return response()->json($data);
    }
}
