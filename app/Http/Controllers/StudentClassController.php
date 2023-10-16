<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

class StudentClassController extends Controller
{
    public function index(Request $request, $classId){
        $keyword = $request->input('keyword');

        $class = Classes::findOrFail($classId);
        if(!$class){
            abort(404);
        }

        $students = $class->students;
        return view('studentclass.index',compact('students','class'));
    }
}
