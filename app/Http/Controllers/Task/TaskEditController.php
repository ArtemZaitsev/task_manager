<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Direction;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use function __;
use function redirect;
use function view;

class TaskEditController extends Controller
{
    public function index(Request $request, $id)
    {

        $task = Task::findOrFail($id);


//        $tasks = Task::all();
//        SELECT * FROM task_log WHERE task_id = 5
        $logs = TaskLog::where('task_id', $id)->get();

        return view('task.edit', [
            'users' => User::all(),
            'products' => Product::all(),
            'task' => $task,
            'logs' => $logs,
        ]);
    }

    public function processForm(Request $request, $id)
    {
        /** @var  Task $task */
        $task = Task::findOrFail($id);
        $data = $this->validate($request, [
            'product_id' => 'required|numeric',
            'base' => 'nullable|max:255',
            'setting_date' => 'nullable|date',
            'task_creator' => 'nullable|max:255',
            'priority' => ['nullable', Rule::in(array_keys(Task::All_PRIORITY))],
            'type' => ['nullable', Rule::in(array_keys(Task::All_TYPE))],
            'theme' => 'nullable|max:255',
            'main_task' => 'nullable|max:255',
            'name' => 'required|max:255',
            'user_id' => ['required',Rule::exists(User::class,'id')],
            'coperformers' => 'nullable|array',
            'coperformers.*' => Rule::exists(User::class,'id') ,
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'execute' => ['nullable', Rule::in(array_keys(Task::ALL_EXECUTIONS))],
            'status' => ['required', Rule::in(array_keys(Task::ALL_STATUSES))],
//            'parent_id' => 'required|numeric',
            'task_log.*.status' => ['required', Rule::in(array_keys(TaskLog::ALL_STATUSES))],
            'task_log.*.date_refresh_plan' => 'date',
            'task_log.*.date_refresh_fact' => 'date',
            'task_log.*.trouble' => 'required|max:255',
            'task_log.*.what_to_do' => 'max:255',
        ], [
            'required' => 'Поле обязательно для заполнения',
        ]);

        /** @var  User $user */

        $user = User::findOrFail($data['user_id']);
        $product = Product::findOrFail($data['product_id']);
//        $parentTask = Task::findOrFail($data['parent_id']);

        DB::transaction(function () use ($task, $data, $user, $product) {
            $task->product()->associate($product);
            $task->base = $data['base'];
            $task->setting_date = $data['setting_date'];
            $task->task_creator = $data['task_creator'];
            $task->priority = $data['priority'];
            $task->type = $data['type'];
            $task->theme = $data['theme'];
            $task->main_task = $data['main_task'];
            $task->name = $data['name'];
            $task->user()->associate($user);
            $task->start_date = $data['start_date'];
            $task->end_date = $data['end_date'];
            $task->execute = $data['execute'];
            $task->status = $data['status'];
            //        $task->parent()->associate($parentTask);
            $task->save();
            $task->coperformers()->sync($data['coperformers']);


            if (isset($data['task_log'])) {
                foreach ($data['task_log'] as $id => $taskLogData) {
                    $taskLog = TaskLog::findOrFail($id);
                    $taskLog->status = $taskLogData['status'];
                    $taskLog->date_refresh_plan = $taskLogData['date_refresh_plan'];
                    $taskLog->date_refresh_fact = $taskLogData['date_refresh_fact'];
                    $taskLog->trouble = $taskLogData['trouble'];
                    $taskLog->what_to_do = $taskLogData['what_to_do'];

                    $taskLog->save();
                }
            }

        });


        return redirect()->route('tasks.list')->with('success', __('messages.task_edit_success'));
    }
}
