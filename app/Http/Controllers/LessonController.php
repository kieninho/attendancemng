<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class LessonController extends Controller
{

    public function classLesson($classId)
    {
        $user = Auth::user();
        if ($user->is_teacher) {
            $classes = $user->lessons->map(function ($lesson) {
                return $lesson->classes;
            });
        } else {
            $classes = Classes::where('status', 1)->orderBy('created_at','asc')->get();
        }

        
        $class = Classes::where('id',$classId)->where('status',1)->first();
        $lessons = $class->lessons->sortBy('created_at');
        return view('lesson.classLesson', compact('lessons', 'classes','class'));
    }


    public function index(){
        $user = Auth::user();
        // lấy ds lớp theo user
        if ($user->is_teacher) {
            $classes = $user->lessons->map(function ($lesson) {
                return $lesson->classes;
            });
        } else {
            $classes = Classes::where('status', 1)->orderBy('created_at','asc')->get();
        }
  
    }

    public function store(Request $request, $classId){
        $request->validate(
            [
                'name' => 'required',
                'start' => 'required',
                'end' => 'required',
                'date' => 'required',
            ],
            [
                'name.required' => 'Tên lớp không được bỏ trống',
            ]
        );
        $data = $request->all();
        $data['class_id'] = $classId;
        $startStr = $data['start']." ".$data['date'];
        $endStr = $data['end']." ".$data['date'];

        $data['start_at'] = $data['birthday'] = Carbon::createFromFormat('H:i d/m/Y', $startStr)->toDateTime();
        $data['end_at'] = $data['birthday'] = Carbon::createFromFormat('H:i d/m/Y', $endStr)->toDateTime();

        $result = Lesson::create($data);
        if ($result) {
            $message = 'Thêm mới thành công!';
        } else {
            $message = 'Thêm mới không thành công!';
        }
        return redirect()->back()->withErrors($message);
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


}
