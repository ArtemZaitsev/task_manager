<?php

use App\Http\Controllers\Component\ComponentController;
use App\Http\Controllers\Component\ComponentCreateController;
use App\Http\Controllers\Component\ComponentDeleteController;
use App\Http\Controllers\Component\ComponentEditController;
use App\Http\Controllers\Component\ComponentExportController;
use App\Http\Controllers\FileUploadTestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PhysicalObject\PhysicalObjectReportController;
use App\Http\Controllers\PurchaseOrder\PurchaseOrderCreateController;
use App\Http\Controllers\PurchaseOrder\PurchaseOrderDeleteController;
use App\Http\Controllers\PurchaseOrder\PurchaseOrderEditController;
use App\Http\Controllers\PurchaseOrder\PurchaseOrderListController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Sz\SzCreateController;
use App\Http\Controllers\Sz\SzDeleteController;
use App\Http\Controllers\Sz\SzEditController;
use App\Http\Controllers\Sz\SzListController;
use App\Http\Controllers\Task\PerformerTaskEditController;
use App\Http\Controllers\Task\TaskAddController;
use App\Http\Controllers\Task\TaskColumnController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\TaskDelController;
use App\Http\Controllers\Task\TaskEditController;
use App\Http\Controllers\Task\TasksExportController;
use App\Http\Controllers\TaskTree\TaskTreeProjectController;
use App\Http\Controllers\TaskTree\TaskTreeProjectSaveController;
use App\Http\Controllers\TaskTree\TaskTreeTaskSaverController;
use App\Http\Controllers\TechnicalTaskCalculation\TechnicalTaskCalculationCreateController;
use App\Http\Controllers\TechnicalTaskCalculation\TechnicalTaskCalculationDeleteController;
use App\Http\Controllers\TechnicalTaskCalculation\TechnicalTaskCalculationEditController;
use App\Http\Controllers\TechnicalTaskCalculation\TechnicalTaskCalculationListController;
use App\Http\Middleware\VerifyCsrfToken;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/setupcolumns', [TaskColumnController::class, 'index'])->name('task.setupColumns.show');
    Route::post('/setupcolumns', [TaskColumnController::class, 'processForm'])->name('task.setupColumns.store');


    Route::get('/', [TaskController::class, 'list'])
        ->name(TaskController::ACTION_LIST);

    Route::get('/tasks/tree', [TaskTreeProjectController::class, 'index'])
        ->name(TaskTreeProjectController::ROUTE_NAME);

    Route::post('/tasks/project/{id}/save', [TaskTreeProjectSaveController::class, 'save'])
        ->name(TaskTreeProjectSaveController::ROUTE_NAME);
    Route::post('/tasks/{id}/tree/save', [TaskTreeTaskSaverController::class, 'save'])
        ->name(TaskTreeTaskSaverController::ROUTE_NAME);

    Route::get('/task/add', [TaskAddController::class, 'index'])->name('task.showFormAdd');
    Route::post('/task/add', [TaskAddController::class, 'processForm'])->name('task.store');

    Route::get('/task/{id}/edit', [TaskEditController::class, 'index'])
        ->where('id', '[0-9]+')
        ->name(TaskEditController::INDEX_ACTION);
    Route::post('/task/{id}/edit', [TaskEditController::class, 'processForm'])
        ->where('id', '[0-9]+')
        ->name(TaskEditController::EDIT_ACTION);

    Route::get('/task/{id}/edit_by_performer', [PerformerTaskEditController::class, 'index'])
        ->where('id', '[0-9]+');

    Route::post('/task/{id}/edit_by_performer', [PerformerTaskEditController::class, 'processForm'])
        ->where('id', '[0-9]+')
        ->name('task.edit_by_performer');

    Route::get('/task/{id}/del', [TaskDelController::class, 'index'])
        ->where('id', '[0-9]+')
        ->name('task.del');

    Route::get('/tasks/export', [TasksExportController::class, 'export'])->name(TasksExportController::EXPORT_ACTION);


    Route::get('/components', [ComponentController::class, 'index'])
        ->name(ComponentController::ROUTE_NAME);

    Route::get('/component/{id}/edit', [ComponentEditController::class, 'index'])
        ->where('id', '[0-9]+')
        ->name(ComponentEditController::INDEX_ACTION);
    Route::post('/component/{id}/edit', [ComponentEditController::class, 'processForm'])
        ->where('id', '[0-9]+')
        ->name(ComponentEditController::EDIT_ACTION);
    Route::get('/component/{id}/delete', [ComponentDeleteController::class, 'index'])
        ->where('id', '[0-9]+')
        ->name(ComponentDeleteController::ROUTE_NAME);

    Route::get('/component/create', [ComponentCreateController::class, 'index'])
        ->name(ComponentCreateController::INDEX_ACTION);
    Route::post('/component/create', [ComponentCreateController::class, 'processForm'])
        ->name(ComponentCreateController::PROCESS_FORM_ACTION);
    Route::post('/component/save-fields', [ComponentController::class, 'saveDisplayFields'])
        ->name(ComponentController::SAVE_FIELDS_ROUTE_NAME);
    Route::get('/components/export', [ComponentExportController::class, 'export'])->name
    (ComponentExportController::EXPORT_ACTION);

    Route::get('/physical_object/{id}/report', [PhysicalObjectReportController::class, 'index'])
        ->name(PhysicalObjectReportController::ROUTE_NAME)
        ->withoutMiddleware([VerifyCsrfToken::class]);
    Route::post('/physical_object/save-fields', [PhysicalObjectReportController::class, 'saveDisplayFields'])
        ->name(PhysicalObjectReportController::SAVE_FIELDS_ROUTE_NAME);

    Route::get('/sz/list', [SzListController::class, 'index'])
        ->name(SzListController::ROUTE_NAME);
    Route::get('/sz/{id}/delete', [SzDeleteController::class, 'index'])
        ->name(SzDeleteController::ROUTE_NAME);
    Route::get('/sz/create', [SzCreateController::class, 'index'])
        ->name(SzCreateController::INDEX_ACTION);
    Route::post('/sz/create', [SzCreateController::class, 'processForm'])
        ->name(SzCreateController::PROCESS_FORM_ACTION);
    Route::get('/sz/{id}/edit', [SzEditController::class, 'index'])
        ->where('id', '[0-9]+')
        ->name(SzEditController::INDEX_ACTION);
    Route::post('/sz/{id}/edit', [SzEditController::class, 'processForm'])
        ->where('id', '[0-9]+')
        ->name(SzEditController::PROCESS_FORM_ACTION);

    Route::get('/purchase_order/list', [PurchaseOrderListController::class, 'index'])
        ->name(PurchaseOrderListController::ROUTE_NAME);
    Route::get('/purchase_order/{id}/delete', [PurchaseOrderDeleteController::class, 'index'])
        ->name(PurchaseOrderDeleteController::ROUTE_NAME);
    Route::get('/purchase_order/create', [PurchaseOrderCreateController::class, 'index'])
        ->name(PurchaseOrderCreateController::INDEX_ACTION);
    Route::post('/purchase_order/create', [PurchaseOrderCreateController::class, 'processForm'])
        ->name(PurchaseOrderCreateController::PROCESS_FORM_ACTION);
    Route::get('/purchase_order/{id}/edit', [PurchaseOrderEditController::class, 'index'])
        ->where('id', '[0-9]+')
        ->name(PurchaseOrderEditController::INDEX_ACTION);
    Route::post('/purchase_order/{id}/edit', [PurchaseOrderEditController::class, 'processForm'])
        ->where('id', '[0-9]+')
        ->name(PurchaseOrderEditController::PROCESS_FORM_ACTION);

    Route::get('/ttc/list', [TechnicalTaskCalculationListController::class, 'index'])
        ->name(TechnicalTaskCalculationListController::ROUTE_NAME);
    Route::get('/ttc/{id}/delete', [TechnicalTaskCalculationDeleteController::class, 'index'])
        ->name(TechnicalTaskCalculationDeleteController::ROUTE_NAME);
    Route::get('/ttc/create', [TechnicalTaskCalculationCreateController::class, 'index'])
        ->name(TechnicalTaskCalculationCreateController::INDEX_ACTION);
    Route::post('/ttc/create', [TechnicalTaskCalculationCreateController::class, 'processForm'])
        ->name(TechnicalTaskCalculationCreateController::PROCESS_FORM_ACTION);
    Route::get('/ttc/{id}/edit', [TechnicalTaskCalculationEditController::class, 'index'])
        ->where('id', '[0-9]+')
        ->name(TechnicalTaskCalculationEditController::INDEX_ACTION);
    Route::post('/ttc/{id}/edit', [TechnicalTaskCalculationEditController::class, 'processForm'])
        ->where('id', '[0-9]+')
        ->name(TechnicalTaskCalculationEditController::PROCESS_FORM_ACTION);



    Route::get('/test/file_upload', [FileUploadTestController::class, 'index']);
    Route::post('/test/file_upload', [FileUploadTestController::class, 'processForm'])
        ->name('test.file_upload');

});



Route::get('/register', [RegisterController::class, 'show'])->name(RegisterController::REGISTER_ACTION);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'show'])->name(LoginController::LOGIN_ACTION);
Route::post('/login', [LoginController::class, 'authenticate'])->name(LoginController::AUTHENTICATE_ACTION);
Route::get('/logout', [LoginController::class, 'logout'])->name(LoginController::LOGOUT_ACTION);

