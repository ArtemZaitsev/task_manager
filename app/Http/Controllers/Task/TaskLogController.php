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
            'status' => ['required',Rule::in(array_keys(TaskLog::ALL_STATUSES))],
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

        return redirect()->route('tasks.list')->with('success',__('messages.task_refresh_success'));

    }
}
