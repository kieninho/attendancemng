<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentLessonController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Auth;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['prefix' => 'class', 'middleware' => 'auth'], function () {
    Route::get('/', [ClassController::class, 'index'])->name('class');
    Route::post('/store', [ClassController::class, 'store'])->name('store.class');
    Route::get('/delete/{id}', [ClassController::class, 'delete'])->name('delete.class');
    Route::get('/getclass', [ClassController::class, 'getClass'])->name('get.class');
    Route::get('/getclass/{id}', [ClassController::class, 'getClass'])->name('get.class.id');
    Route::post('/update', [ClassController::class, 'update'])->name('update.class');

    Route::get('/export',[ClassController::class,'export'])->name('export.class');
    Route::get('/exportlessons/classid={classId}',[ClassController::class,'exportLessons'])->name('export.lessons');
});

Route::group(['prefix' => 'studentsinclass', 'middleware' => 'auth'], function () {
    Route::get('/classid={classId}', [StudentClassController::class, 'index'])->name('studentInClass');
    Route::get('/delete/{classId}/{studentId}', [StudentClassController::class, 'delete'])->name('delete.studentInClass');
    Route::post('/update/{classId}', [StudentClassController::class, 'update'])->name('update.studentInClass');
    Route::get('/add/{id}', [StudentClassController::class, 'add'])->name('add.studentsinclass');
    Route::get('/store/{classId}/{studentId}', [StudentClassController::class, 'store'])->name('store.studentsinclass');
    Route::post('/addmulti/{classId}', [StudentClassController::class, 'addMulti'])->name('addmulti.studentInClass');
    Route::get('/export/classid={classId}',[StudentClassController::class,'export'])->name('export.studentInClass');
    Route::post('/delete-multi/{classId}', [StudentClassController::class, 'deleteMulti'])->name('deleteMulti.studentInClass');
});



Route::group(['prefix' => 'lesson', 'middleware' => 'auth'], function () {
    Route::get('/class={classId}', [LessonController::class, 'classLesson'])->name('classLesson');
    Route::get('/export-class-lesson/{classId}',[LessonController::class,'exportClassLesson'])->name('export.classLesson');
    Route::post('/store/class={classId}', [LessonController::class, 'store'])->name('store.lesson');
    Route::get('/delete/{id}', [LessonController::class, 'delete'])->name('delete.lesson');
    Route::get('/get', [LessonController::class, 'get'])->name('get.lesson');
    Route::get('/get/{id}', [LessonController::class, 'get'])->name('get.lesson.id');
    Route::post('/update', [LessonController::class, 'update'])->name('update.lesson');
    
    
    Route::get('/detail/{id}', [LessonController::class, 'detail'])->name('detail.lesson');
    Route::get('/attend', [LessonController::class, 'attend'])->name('attend.lesson');
    Route::get('/attend/{lessonId}/{studentId}/{value}', [LessonController::class, 'attend'])->name('attend.lesson.id');
    Route::get('/leave', [LessonController::class, 'leave'])->name('leave.lesson');
    Route::get('/leave/{lessonId}/{studentId}', [LessonController::class, 'leave'])->name('leave.lesson.id');

    Route::get('/export-lesson-detail/{id}',[LessonController::class,'exportLessonDetail'])->name('export.lessonDetail');
});

Route::group(['prefix' => 'teacherlesson', 'middleware' => 'auth'], function () {
    Route::get('/get', [LessonController::class, 'getTeacherLesson'])->name('get.teacherLesson');
    Route::get('/get/{id}', [LessonController::class, 'getTeacherLesson'])->name('get.teacherLesson.id');
});



Route::group(['prefix' => 'student', 'middleware' => 'auth'], function () {
    Route::get('/', [StudentController::class, 'index'])->name('student');
    Route::post('/store', [StudentController::class, 'store'])->name('store.student');
    Route::get('/delete/{id}', [StudentController::class, 'delete'])->name('delete.student');
    Route::get('/get', [StudentController::class, 'get'])->name('get.student');
    Route::get('/get/{id}', [StudentController::class, 'get'])->name('get.student.id');
    Route::post('/update', [StudentController::class, 'update'])->name('update.student');
    Route::get('/detail/{id}', [StudentController::class, 'detail'])->name('detail.student');
    Route::post('/delete-multi', [StudentController::class, 'deleteMulti'])->name('deleteMulti.student');


    Route::get('/export',[StudentController::class,'export'])->name('export.student');
    Route::get('/exportDetail/{id}',[StudentController::class,'exportDetail'])->name('exportDetail.student');

});

Route::group(['prefix' => 'teacher', 'middleware' => 'auth'], function () {
    Route::get('/', [TeacherController::class, 'index'])->name('teacher');
    Route::post('/store', [TeacherController::class, 'store'])->name('store.teacher');
    Route::get('/delete/{id}', [TeacherController::class, 'delete'])->name('delete.teacher');
    Route::post('/deletemulti', [TeacherController::class, 'deleteMulti'])->name('delete.teachers');
    Route::get('/get', [TeacherController::class, 'get'])->name('get.teacher');
    Route::get('/get/{id}', [TeacherController::class, 'get'])->name('get.teacher.id');
    Route::post('/update', [TeacherController::class, 'update'])->name('update.teacher');
    Route::get('/export',[TeacherController::class,'export'])->name('export.teacher');
});

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::get('/', [UserController::class, 'index'])->name('user');
    Route::get('/delete/{id}', [UserController::class, 'delete'])->name('delete.user');
    Route::post('/store', [UserController::class, 'store'])->name('store.user');
    Route::get('/get', [UserController::class, 'get'])->name('get.user');
    Route::get('/get/{id}', [UserController::class, 'get']);
    Route::post('/update', [UserController::class, 'update'])->name('update.user');
    Route::get('/export',[UserController::class,'export'])->name('export.user');

    Route::post('/reset-password',[UserController::class,'resetPassword'])->name('resetPassword.user');
});


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test')->middleware('auth');