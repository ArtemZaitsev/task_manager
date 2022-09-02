<?php

namespace App\Http\Controllers\TaskTree;

use App\BuisinessLogick\Voter\ProjectVoter;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TaskTreeTaskSaverController extends Controller
{
    public const ROUTE_NAME = 'task_tree.save';

    public function __construct(
        private ProjectVoter $projectVoter,
        private TaskTreeSaver $taskSaver)
    {
    }

    public function save(Request $request, $id)
    {
        /** @var Task $task */
        $task = Task::query()->findOrFail($id);
        /** @var Project $taskProject */
        $taskProject = $task->projects->get(0);

        if (!$this->projectVoter->canEditGantt($taskProject)) {
            throw new AccessDeniedHttpException();
        }

        $projectRawJson = $request->request->get('project');
        $projectJson = json_decode($projectRawJson, true);

        $this->taskSaver->saveTask($task,$taskProject, $projectJson);

        return response()->json([
            'ok' => true
        ]);
    }
}
