<?php

use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Task\PerformerTaskEditController;
use App\Http\Controllers\Task\TaskAddController;
use App\Http\Controllers\Task\TaskColumnController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\TaskDelController;
use App\Http\Controllers\Task\TaskEditController;
use App\Http\Controllers\Task\TaskLogController;
use App\Http\Controllers\Task\TasksExportController;
use App\Http\Controllers\TaskTree\TaskTreeProjectController;
use App\Http\Controllers\TaskTree\TaskTreeProjectSaveController;
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

//Route::group(['prefix' => 'admin','namespace' =>'Admin'], function(){
//    Route::get('/',[MainController::class,'index'])->name('admin.index');
//});

Route::impersonate();

Route::get('/setupcolumns', [TaskColumnController::class, 'index'])->name('task.setupColumns.show');
Route::post('/setupcolumns', [TaskColumnController::class, 'processForm'])->name('task.setupColumns.store');

Route::get('/', [TaskController::class, 'list'])
    ->name(TaskController::ACTION_LIST)
    ->middleware('auth');

Route::get('/tasks/project/{id}/tree', [TaskTreeProjectController::class, 'index'])
    ->name(TaskTreeProjectController::ROUTE_NAME);

Route::post('/tasks/project/{id}/save', [TaskTreeProjectSaveController::class, 'save'])
    ->name(TaskTreeProjectSaveController::ROUTE_NAME);


Route::get('/task/add', [TaskAddController::class, 'index'])->name('task.showFormAdd');
Route::post('/task/add', [TaskAddController::class, 'processForm'])->name('task.store');

Route::get('/task/{id}/edit', [TaskEditController::class, 'index'])
    ->where('id', '[0-9]+')
    ->name(TaskEditController::INDEX_ACTION);
Route::post('/task/{id}/edit', [TaskEditController::class, 'processForm'])
    ->where('id', '[0-9]+')
    ->name(TaskEditController::EDIT_ACTION);

Route::get('/task/{id}/edit_by_performer', [PerformerTaskEditController::class, 'index'])
    ->where('id', '[0-9]+');;

Route::post('/task/{id}/edit_by_performer', [PerformerTaskEditController::class, 'processForm'])
    ->where('id', '[0-9]+')
    ->name('task.edit_by_performer');

Route::get('/task/{id}/del', [TaskDelController::class, 'index'])
    ->where('id', '[0-9]+')
    ->name('task.del');

Route::get('/register', [RegisterController::class, 'show'])->name(RegisterController::REGISTER_ACTION);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'show'])->name(LoginController::LOGIN_ACTION);
Route::post('/login', [LoginController::class, 'authenticate'])->name(LoginController::AUTHENTICATE_ACTION);
Route::get('/logout', [LoginController::class, 'logout'])->name(LoginController::LOGOUT_ACTION);

Route::get('/tasks/export', [TasksExportController::class, 'export'])->name(TasksExportController::EXPORT_ACTION);


//Route::get('/persons', [\App\Http\Controllers\PersonController::class, 'list'])->name('tasks.list');
////Route::get('/test', [\App\Http\Controllers\TestController::class, 'index']);
//Route::get('/person/add', [\App\Http\Controllers\Task\TaskAddController::class, 'index']);
//Route::post('/person/add', [\App\Http\Controllers\Task\TaskAddController::class, 'processForm'])->name('task.store');
