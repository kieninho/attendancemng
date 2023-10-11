<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\LessonController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/class', [ClassController::class, 'index'])->name('class');
    Route::post('/class/store', [ClassController::class, 'store'])->name('store.class');
    Route::get('/class/delete/{id}', [ClassController::class, 'delete'])->name('delete.class');
    Route::get('/class/getclass', [ClassController::class, 'getClass'])->name('get.class');
    Route::get('/class/getclass/{id}', [ClassController::class, 'getClass'])->name('get.class.id');
    Route::post('/class/update', [ClassController::class, 'update'])->name('update.class');
});





Route::get('/lesson', [LessonController::class, 'index'])->name('lesson');

Route::group(['prefix' => 'lesson', 'middleware' => 'auth'], function () {
    Route::get('/', [LessonController::class, 'index'])->name('lesson');
    Route::post('/store', [LessonController::class, 'store'])->name('store.lesson');
    Route::get('/delete/{id}', [LessonController::class, 'delete'])->name('delete.lesson');
    Route::get('/get', [LessonController::class, 'get'])->name('get.lesson');
    Route::get('/get/{id}', [LessonController::class, 'get'])->name('get.lesson.id');
    Route::post('/update', [LessonController::class, 'update'])->name('update.lesson');
});



Route::group(['prefix' => 'student', 'middleware' => 'auth'], function () {
    Route::get('/', [StudentController::class, 'index'])->name('student');
    Route::post('/store', [StudentController::class, 'store'])->name('store.student');
    Route::get('/delete/{id}', [StudentController::class, 'delete'])->name('delete.student');
    Route::get('/get', [StudentController::class, 'get'])->name('get.student');
    Route::get('/get/{id}', [StudentController::class, 'get'])->name('get.student.id');
    Route::post('/update', [StudentController::class, 'update'])->name('update.student');
});


Route::group(['prefix' => 'teacher', 'middleware' => 'auth'], function () {
    Route::get('/', [TeacherController::class, 'index'])->name('teacher');
    Route::post('/store', [TeacherController::class, 'store'])->name('store.teacher');
    Route::get('/delete/{id}', [TeacherController::class, 'delete'])->name('delete.teacher');
    Route::get('/get', [TeacherController::class, 'get'])->name('get.teacher');
    Route::get('/get/{id}', [TeacherController::class, 'get'])->name('get.teacher.id');
    Route::post('/update', [TeacherController::class, 'update'])->name('update.teacher');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
