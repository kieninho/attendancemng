<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\RegisteredUserController;
use App\Providers\RouteServiceProvider;
use app\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\ClassController;


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


// Route::group(['prefix' => 'class', 'middleware' => 'auth'], function () {
//     Route::get('/dashboard', [AdminController::class, 'dashboard']);
//     Route::get('/users', [AdminController::class, 'users']);
//     Route::get('/settings', [AdminController::class, 'settings']);
// });
