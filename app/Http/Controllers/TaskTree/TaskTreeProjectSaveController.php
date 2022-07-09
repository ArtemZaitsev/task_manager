<?php

namespace App\Http\Controllers\TaskTree;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTreeProjectSaveController extends Controller
{
    public const ROUTE_NAME = 'project.save';

    public function save(Request $request, $id)
    {
        $project = Project::query()->findOrFail($id);

        $projectRawJson = $request->request->get('project');
        $projectJson = json_decode($projectRawJson, true);

        $this->saveNewTasks($projectJson, $project);

        return response()->json([
            'ok' => true
        ]);
    }

    private function saveNewTasks(array $projectJson, Project $project): void
    {
        foreach ($projectJson['tasks'] as $task) {
            if (str_starts_with($task['id'], 'tmp_')) {
                $taskEntity = (new Task());
                $taskEntity->fill([
                    //'base',
                    //'setting_date',
                    'task_creator' => Auth::id(),
                    'priority' => Task::PRIORITY_LOW,
                    'type' => Task::TYPE_PLAN,
                    'theme' => '',
                    'main_task' => '',
                    'parent_id' => null,
                    'name' => $task['name'],
                    'user_id' => Auth::id(),
                    'system_id' => null,
                    'subsystem_id' => null,
                    'detail_id' => null,
                    'physical_object_id' => null,
                    'start_date' => $this->parseTimestamp($task['start']),
                    'end_date_plan' => $this->parseTimestamp($task['end']),
                    'end_date' => null,
                    'end_date_fact' => null,
                    'progress' => 0,
                    'execute' => Task::EXECUTE_DONT_KNOW,
                    'status' => Task::STATUS_NOT_DONE,
                    'comment' => null,
                    'execute_time_plan' => null,
                    'execute_time_fact' => null
                ]);
                $taskEntity->save();
                $taskEntity->projects()->sync([$project->id]);
            }
        }
    }

    private function parseTimestamp($date): string
    {
        return empty($date) ? (new \DateTime())
            ->format('Y-m-d') :
            (new \DateTime((int)($date / 1000)))->format('Y-m-d');
    }
}
