<?php

namespace App\Http\Controllers\TaskTree;

use App\BuisinessLogick\Voter\ProjectVoter;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TaskTreeProjectController extends Controller
{
    public const ROUTE_NAME = 'project.gantt';

    public function __construct(
        private ProjectVoter    $projectVoter,
        private TaskTreeBuilder $treeBuilder
    )
    {
    }

    public function index(Request $request)
    {
        /** @var Task $task */
        $task = null;
        $project = null;

        if ($request->query->has('task')) {
            $task = Task::query()->findOrFail($request->query->get('task'));
            $tasks = $this->treeBuilder->forTask($task);
        } elseif ($request->query->has('project')) {
            $project = Project::findOrFail($request->query->get('project'));
            $tasks = $this->treeBuilder->forProject($project);
        } else {
            throw new \LogicException();
        }


        $result = [
            'tasks' => $tasks,
            'selectedRow' => 0,
            'deletedTaskIds' => [],
            "resources" => [],
            "roles" => [],
            "canAdd" => true,
            "canWrite" => true,
            "canWriteOnParent" => true,
            "zoom" => "1Q"
        ];

        $taskProject = $project ?? $task->projects->get(0);
        return view('task/task_project_tree', [
            'project' => $taskProject,
            'projectVoter' => $this->projectVoter,
            'saveUrl' => $project !== null ?
                route(TaskTreeProjectSaveController::ROUTE_NAME, ['id' => $project->id])
                : route(TaskTreeTaskSaverController::ROUTE_NAME, ['id' => $task->id]),
            'tasks' => json_encode($result)
        ]);

    }


}
