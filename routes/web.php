<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\RegisteredUserController;
use App\Providers\RouteServiceProvider;
use app\Http\Controllers\Auth\CustomLoginController;

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

Route::get('/ss', function () {
    return view('student.index');
});


// Route::get('/register', function () {
//     return view('auth.register');
// })->name('register');

// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

//Route::prefix('')->group(function () {
//     Route::get('/register', [RegisteredUserController::class, 'create'])
//         ->middleware(['guest'])
//         ->name('register');

//     Route::post('/register', [RegisteredUserController::class, 'store'])
//         ->middleware(['guest']);

    // Route::get('/login', [CustomLoginController::class, 'create'])
    //     ->middleware(['guest'])
    //     ->name('login');

    // Route::post('/login', [CustomLoginController::class, 'store'])
    //     ->middleware(['guest']);

//     Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
//         ->middleware(['auth'])
//         ->name('logout');
//});

// Route::post('/login', [CustomLoginController::class, 'store'])
//     ->middleware(['guest'])
//     ->name('login');