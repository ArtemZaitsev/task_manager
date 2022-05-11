<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskLog;

class PerformerTaskEditController extends Controller
{
    public function index($id)
    {

        $task = Task::findOrFail($id);
        $logs = TaskLog::where('task_id', $id)->get();

        return view('task.edit_by_performer', [
            'actionUrl' => route('task.edit_by_performer', ['id' => $task->id]),
            'title' => "Редактирование задачи " . $task->name,
            'task' => $task,
            'logs' => $logs,
        ]);
    }

    public function processForm(TaskByPerformerRequest $request, $id)
    {
        /** @var  Task $task */
        $task = Task::findOrFail($id);
        $request->store($task);

        if ($request->query->has('back')) {
            $backUrl = $request->query->get('back');
            $response = redirect()->to($backUrl);
        } else {
            $response = redirect()->route('tasks.list');
        }

        return $response->with('success', __('messages.task_edit_success'));

    }
}
