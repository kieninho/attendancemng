<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lesson;
use App\Models\Classes;
use App\Models\Student;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        $classes = Classes::getClassesByUser($user);
        $countStudent = Student::getStudents()->count();
        $countTeacher = User::getTeachers()->count();
        $countClass = Classes::getClass()->count();

        return view('home',compact('classes','countStudent','countTeacher','countClass'));
    }

    public function test(){
        $result = Lesson::findOrFail(45)->getStudentsInLesson();
        dd($result);
        return "Hello";
    }
}
