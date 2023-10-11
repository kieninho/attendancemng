<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\RegisteredUserController;
use App\Providers\RouteServiceProvider;
use app\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\CustomRegisterController;


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


Route::middleware(['auth'])->group(function () {
});
Route::get('/class', [ClassController::class, 'index'])->name('class');
Route::post('/class/store', [ClassController::class, 'store'])->name('store.class');
Route::get('/class/delete/{id}', [ClassController::class, 'delete'])->name('delete.class');
Route::get('/class/getclass', [ClassController::class, 'getClass'])->name('get.class');
Route::get('/class/getclass/{id}', [ClassController::class, 'getClass'])->name('get.class.id');
Route::post('/class/update',[ClassController::class, 'update'])->name('update.class');


Route::get('/lesson', [LessonController::class, 'index'])->name('lesson');




Route::get('/student', [StudentController::class, 'index'])->name('student');
Route::post('/student/store', [StudentController::class, 'store'])->name('store.student');
Route::get('/student/delete/{id}', [StudentController::class, 'delete'])->name('delete.student');
Route::get('/student/get', [StudentController::class, 'get'])->name('get.student');
Route::get('/student/get/{id}', [StudentController::class, 'get'])->name('get.student.id');
Route::post('/student/update',[StudentController::class, 'update'])->name('update.student');

// Route::group(['prefix' => 'class', 'middleware' => 'auth'], function () {
//     Route::get('/dashboard', [AdminController::class, 'dashboard']);
//     Route::get('/users', [AdminController::class, 'users']);
//     Route::get('/settings', [AdminController::class, 'settings']);
// });
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
