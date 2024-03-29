<?php

namespace App\Http\Controllers\TaskTree;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommonTaskSaver
{
    public function saveProject(Project $project, array $projectJson, array $tasksToIgnore): void
    {
        $this->deleteTasks($projectJson['deletedTaskIds']);
        $this->saveNewTasks($projectJson, $project);

        $tasks = $this->fetchTasks($projectJson);
        $taskHasDeps = $this->fetchTaskHasDeps($tasks);

        $this->saveChanges($projectJson['tasks'], $tasks, $tasksToIgnore);
        $this->saveHierarchy($projectJson['tasks'], $tasks);
        $this->saveDependences($projectJson['tasks'], $tasks, $taskHasDeps);
    }

    private function saveNewTasks(array &$projectJson, Project $project): void
    {
        foreach ($projectJson['tasks'] as $idx => &$task) {
            if (str_starts_with($task['id'], 'tmp_')) {
                $taskEntity = (new Task());
                $taskEntity->fill([
                    //'base',
                    //'setting_date',
                    'number' => $idx + 1,
                    'task_creator' => null,
                    'priority' => Task::PRIORITY_LOW,
                    'type' => Task::TYPE_PLAN,
                    'name' => $task['name'],
                    'user_id' => Auth::id(),
                    'start_date' => $this->parseTimestamp($task['start']),
                    'end_date_plan' => $this->parseTimestamp($task['end']),
                    'progress' => $task['progress']?? 0,
                    'execute' => Task::EXECUTE_DONT_KNOW,
                    'comment' => $task['description'] ?? null,
                    'status' => array_flip(Task::STATUSES_STRING)[$task['status']],

                    'theme' => '',
                    'main_task' => '',
                    'parent_id' => null,
//                    'system_id' => null,
//                    'subsystem_id' => null,
//                    'detail_id' => null,
                    'physical_object_id' => null,
                    'end_date' => null,
                    'end_date_fact' => null,
                    'execute_time_plan' => null,
                    'execute_time_fact' => null,
                    'show_in_gantt' => 1
                ]);
                $taskEntity->save();
                $taskEntity->projects()->sync([$project->id]);


                $task['id'] = $taskEntity->id;
            }
        }
    }

    private function parseTimestamp($date): string
    {
        $date = empty($date) ? (new \DateTime())
            ->format('Y-m-d') :
            (new \DateTime())
                ->setTimestamp((int)($date / 1000))
                ->setTimezone( new \DateTimeZone('Europe/Moscow'))
                ->format('Y-m-d');
        return $date;
    }

    /**
     * @param array $projectJson
     * @return array<int, Task>
     */
    private function fetchTasks(array $projectJson): array
    {
        $ids = array_map(fn(array $task) => $task['id'], $projectJson['tasks']);
        $rows = Task::query()->whereIn('id', $ids)
            ->get()
            ->all();

        $tasks = [];
        foreach ($rows as $task) {
            $tasks[$task->id] = $task;
        }

        return $tasks;
    }

    private function saveChanges(array $sourceTasks, array $tasks, array $tasksToIgnore): void
    {
        foreach ($sourceTasks as $idx => $sourceTask) {
            if(in_array($sourceTask['id'], $tasksToIgnore)) {
                continue;
            }
            /** @var Task $task */
            $task = $tasks[$sourceTask['id']];
            $task
                ->fill([
                    'number' => $idx + 1,
                    'name' => $sourceTask['name'],
                    'start_date' => $this->parseTimestamp($sourceTask['start']),
                    'end_date_plan' => $this->parseTimestamp($sourceTask['end']),
                    'status' => array_flip(Task::STATUSES_STRING)[$sourceTask['status']],
                    'progress' => $sourceTask['progress'] ?? 0,
                    'comment' => $sourceTask['description'] ?? null,
                ])
                ->save();
        }
    }

    private function saveHierarchy(array $sourceTasks, array $tasks): void
    {
        foreach ($sourceTasks as $idx => $sourceTask) {
            /** @var Task $task */
            $task = $tasks[$sourceTask['id']];

            if ($sourceTask['level'] === 0) {
                $task->parent_id = null;
                $task->save();
            } else {
                $parentIdx = null;
                for ($i = $idx - 1; $i >= 0; $i--) {
                    if ($sourceTasks[$i]['level'] === $sourceTask['level'] - 1) {
                        $parentIdx = $i;
                        break;
                    }
                }
                $parentId = $sourceTasks[$parentIdx]['id'];
                $task->parent_id = $parentId;
                $task->save();
            }
        }
    }

    private function saveDependences(array $sourceTasks, array $tasks, array $taskHasDeps): void
    {
        foreach ($sourceTasks as $sourceTask) {
            /** @var Task $task */
            $task = $tasks[$sourceTask['id']];

            if (!empty($sourceTask['depends'])) {
                $dependIdxs = explode(',', $sourceTask['depends']);
                $dependIdxs = array_map(fn($data) => (int)$data, $dependIdxs);
                $dependIds = array_map(fn(int $idx) => $sourceTasks[$idx - 1]['id'], $dependIdxs);

                $task->prev()->sync($dependIds);
            } else {
                if(isset($taskHasDeps[$task->id])) {
                    $task->prev()->sync([]);
                }
            }

        }
    }

    private function deleteTasks(array $deletedTaskIds): void
    {
        if(empty($deletedTaskIds)) {
            return;
        }

        /** @var Task[] $tasks */
        $tasks = Task::query()->whereIn('id', $deletedTaskIds)
            ->get()
            ->all();

        foreach ($tasks as $task) {
            $task->delete();
        }
    }

    private function fetchTaskHasDeps(array $tasks): array
    {
        $rows = DB::table('tasks_prev')
            ->whereIn('task_id', array_keys($tasks))
            ->get()
            ->all();

        $hasDeps = [];
        foreach ($rows as $row) {
            $hasDeps[$row->task_id] = true;
        }

        return $hasDeps;
    }
}
