<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Http\Requests\CreateClass;
use App\Http\Requests\UpdateClass;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use App\Services\helper;
use App\Models\Classes;
use App\Models\Lesson;
use App\Models\StudentClass;
use App\Models\StudentLesson;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClassExport;

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

    public function store(ClassRequest $request)
    {
        $request->validated();

        $listExitsCode = Classes::pluck('code')->all();

        $data = $request->all();
        $data['status'] = 1;
        $data['code'] = helper::genCode('CL',$listExitsCode);

        $result = Classes::create($data);

        if($result){
            $message = "Thạo lớp thành công !";
            return redirect()->back()->withErrors($message);
        }
        
    }

    public function delete($id){
        $class = Classes::findOrFail($id);
        
        if($class){
            // xóa học viên trong buổi học
            StudentLesson::deleteByClass($id);

            // xóa học viên trong lớp
            StudentClass::deleteByClass($id);

            //Xóa các buổi hoc
            Lesson::deleteByClassId($id);

            $message="Xóa thành công!";
            
            //--
            $class->status = 0;
            $class->save();
            $lessons = $class->lessons;
            foreach($lessons as $lesson){
                $lesson->status = 0;
                $lesson->save();
            }
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

    public function update(ClassRequest $request){

        $data = $request->all();
        $record = Classes::findOrFail($data['classId']);

        if(isset($record)){
            $request->validated();
            $record->id = $data['classId'];
            $record->name = $data['name'];
            $record->description = $data['description'];
            $record->save();

            $message = "Cập nhật thành công!";
        }

        return redirect()->back()->withErrors($message);
    }

    public function export(){

        $classes = Classes::getAllClass();

        return Excel::download(new ClassExport($classes), 'Classes.xlsx');
    }

    public function exportLessons($classId){
        
    }
}
