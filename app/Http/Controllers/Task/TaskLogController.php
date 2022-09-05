<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskLogController extends Controller
{
    const INDEX_ACTION = 'task_log_index';
    const PROCESS_FORM_ACTION = 'task_log_process_form';
    const DELETE_ACTION = 'task_log_delete';

    public function index(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        return view('task_log.create', [
            'task' => $task
        ]);
    }

    public function processForm(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $data = $this->validate($request, [
            'status' => ['required', Rule::in(array_keys(TaskLog::ALL_STATUSES))],
            'date_refresh_plan' => 'nullable|date',
            'date_refresh_fact' => ' nullable|date',
            'trouble' => 'required',
            'what_to_do' => 'nullable',
        ]);
        $taskLog = new TaskLog();
        $taskLog->status = $data['status'];
        $taskLog->date_refresh_plan = $data['date_refresh_plan'];
        $taskLog->date_refresh_fact = $data['date_refresh_fact'];
        $taskLog->trouble = $data['trouble'];
        $taskLog->what_to_do = $data['what_to_do'];

        $taskLog->task()->associate($task);

        $taskLog->save();


        $backUrl = $request->query->get('back');
        if(empty($backUrl)){
            $backUrl = route(TaskController::ACTION_LIST);
        }

        return redirect()->to($backUrl)->with('success', __('messages.task_refresh_success'));

    }

    public function deleteLog(Request $request, $id){
        /** @var TaskLog $taskLog */

        $taskLog = TaskLog::findOrFail($id);
        $task = $taskLog->task;
        $taskLog->delete();

        return response()->json([
            'status' => 'ok',
            'task' => $task->id
        ]);

    }
}
