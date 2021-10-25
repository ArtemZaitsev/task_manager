<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Task\TaskAddController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\TaskDelController;
use App\Http\Controllers\Task\TaskEditController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/tasks', [TaskController::class, 'list'])
    ->name(TaskController::ACTION_LIST)
    ->middleware('auth');

Route::get('/task/add', [TaskAddController::class, 'index']);
Route::post('/task/add', [TaskAddController::class, 'processForm'])->name('task.store');

Route::get('/task/{id}/edit', [TaskEditController::class, 'index'])->where('id', '[0-9]+');
Route::post('/task/{id}/edit', [TaskEditController::class, 'processForm'])
    ->where('id', '[0-9]+')
    ->name('task.edit');

Route::get('/task/{id}/del', [TaskDelController::class, 'index'])
    ->where('id', '[0-9]+')
    ->name('task.del');

Route::get('/login', [LoginController::class, 'show'])->name(LoginController::LOGIN_ACTION);
Route::post('/login', [LoginController::class, 'authenticate'])->name(LoginController::AUTHENTICATE_ACTION);
Route::get('/logout', [LoginController::class, 'logout'])->name(LoginController::LOGOUT_ACTION);


//Route::get('/persons', [\App\Http\Controllers\PersonController::class, 'list'])->name('tasks.list');
////Route::get('/test', [\App\Http\Controllers\TestController::class, 'index']);
//Route::get('/person/add', [\App\Http\Controllers\Task\TaskAddController::class, 'index']);
//Route::post('/person/add', [\App\Http\Controllers\Task\TaskAddController::class, 'processForm'])->name('task.store');
