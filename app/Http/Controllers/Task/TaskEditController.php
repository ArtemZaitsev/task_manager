<?php

namespace App\Http\Controllers\Task;

use App\BuisinessLogick\Voter\TaskVoter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Task\Request\TaskEditRequest;
use App\Lib\SelectUtils;
use App\Models\Component\Component;
use App\Models\Component\PhysicalObject;
use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskDocument;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Http\Request;
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
                'start_date',
                'end_date_plan',
                'end_date_fact',
                'progress',
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
            'components' => Component::all('id', 'title', 'identifier'),
            'taskDocuments' => SelectUtils::entityListToLabelMap(
                TaskDocument::query()->get()->all(),
                fn(TaskDocument $entity) => $entity->label()
            ),
            'physical_objects' => PhysicalObject::all(),
            'tasks' => Task::all(),
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
