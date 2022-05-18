<?php

namespace App\Http\Controllers\Task;

use App\BuisinessLogick\TaskVoter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Task\Request\TaskEditRequest;
use App\Models\Direction;
use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use function __;
use function redirect;
use function view;

class TaskEditController extends Controller
{
    public const EDIT_ACTION = 'task.edit';
    public const INDEX_ACTION = 'task.edit.index';

    public function __construct(
        private TaskVoter $voter
    )
    {
    }

    public function index(Request $request, $id)
    {

        $task = Task::findOrFail($id);
        $logs = TaskLog::where('task_id', $id)->get();

        $fieldsToEdit = match($this->voter->editRole($task)) {
            'planer' => null,
            'performer' =>  [
                'end_date_plan',
                'end_date_fact',
                'execute',
                'status',
                'comment',
                'execute_time_fact'
            ],
            default => []
        };

        return view('task.edit', [
            'actionUrl' => route('task.edit', ['id' => $task->id]),
            'title' => $task->name,
            'users' => User::all(),
            'projects' => Project::all(),
            'families' => Family::all(),
            'products' => Product::all(),
            'task' => $task,
            'logs' => $logs,
            'fieldsToEdit' => $fieldsToEdit
        ]);
    }

    public function processForm(TaskEditRequest $request, $id)
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
