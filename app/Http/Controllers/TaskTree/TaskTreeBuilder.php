<?php

namespace App\Http\Controllers\TaskTree;

use App\BuisinessLogick\Voter\TaskVoter;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskTreeBuilder
{

    public function __construct(private TaskVoter $taskVoter)
    {
    }

    public function build(Project $project): array
    {
        /** @var Task[] $tasks */
        $tasks = Task::query()
            ->whereIn('id', function ($query) use ($project) {
                $query->select('tproj.task_id')
                    ->from('task_project', 'tproj')
                    ->where('tproj.project_id', $project->id);
            })
            ->orderBy('number')
            ->get()
            ->all();

        $prevTasks = $this->buildPrevTasks($project);


        $rootTasks = array_filter($tasks, fn(Task $task) => $task->parent_id === null);
        $rootTrees = array_map(fn(Task $task) => new TreeItem($task), $rootTasks);

        $treeItemIdMap = [];
        foreach ($rootTrees as $item) {
            $treeItemIdMap[$item->getData()->id] = $item;
        }

        $tasks = array_filter($tasks, fn(Task $task) => $task->parent_id !== null);
        $taskIds = [];
        foreach ($tasks as $task) {
            $taskIds[$task->id] = $task;
        }

        foreach ($taskIds as $task) {
            $taskCopy = $task;
            /** @var TreeItem $rootItem */
            $rootItem = null;

            while (true) {
                if (isset($treeItemIdMap[$taskCopy->parent_id])) {
                    $rootItem = $treeItemIdMap[$taskCopy->parent_id];
                    break;
                } else {
                    if (!isset($taskIds[$taskCopy->parent_id])) {
                        throw new \LogicException();
                    }
                    $taskCopy = $taskIds[$taskCopy->parent_id];
                }
            }

            $child = new TreeItem($taskCopy);
            $rootItem->addChild($child);

            unset($taskIds[$taskCopy->id]);
            $treeItemIdMap[$taskCopy->id] = $child;
        }

        foreach ($rootTrees as $idx => $treeItem) {
            if (!$treeItem->getData()->show_in_gantt) {
                unset($rootTrees[$idx]);
            }
        }
        foreach ($rootTrees as $treeItem) {
            $this->clearShowInGanttTassk($treeItem);
        }

        $tasksJson = [];
        foreach ($rootTrees as $treeRoot) {
            $rootTasks = $this->treeList($treeRoot, $prevTasks);
            $tasksJson = array_merge($tasksJson, $rootTasks);
        }

     //   $this->buildDepends($tasksJson, $prevTasks);

        return $tasksJson;
    }

    private function buildDepends(array &$tasksJson, array $prevTasks): void
    {
        $idToIndex = [];
        $indexToId = [];

        foreach ($tasksJson as $idx => $task) {
            $idToIndex[$task['id']] = $idx;
            $indexToId[$idx] = $task['id'];
        }

        foreach ($prevTasks as $sourceId => $destIds) {
            $destIdIndexes = array_map(fn(int $id) => $idToIndex[$id], $destIds);
            $destIdIndexes = array_map(fn(int $idx) => $idx + 1, $destIdIndexes);
            $tasksJson[$idToIndex[$sourceId]]["depends"] = implode(",", $destIdIndexes);
        }
    }

    private function treeList(TreeItem $item): array
    {
        $list = [];
        $list[] = $this->treeItemData($item);

        foreach ($item->getChilds() as $child) {
            $parentList = $this->treeList($child);
            foreach ($parentList as $parentItem) {
                $list[] = $parentItem;
            }
        }

        return $list;
    }

    private function treeItemData(TreeItem $item): array
    {
        /** @var Task $task */
        $task = $item->getData();
        $taskData = [
            "id" => $task->id,
            "name" => $task->name,
            "progress" => $task->progress,
            "progressByWorklog" => false,
            "relevance" => 0,
            "type" => "",
            "typeId" => "",
            "description" => $task->comment,
            "code" => "",
            "level" => $item->level(),
            "status" => $this->taskStatus($task),
            "depends" => "",
            "start" => $task->start_date === null
                ? $this->dateToTimestamp($task->created_at)
                : $this->dateToTimestamp($task->start_date),
            "end" => $task->end_date_plan === null
                ? $this->dateToTimestamp($task->created_at)
                : $this->dateToTimestamp($task->end_date_plan),
            "duration" => $this->duration($task),
            "startIsMilestone" => false,
            "endIsMilestone" => false,
            "collapsed" => false,
            "canWrite" => true,
            "canAdd" => true,
            "canDelete" => true,
            "canAddIssue" => true,
            "assigs" => [],
            "hasChild" => true,
            "userName" => $task->user->label,
            "canEdit" => $this->taskVoter->canEdit($task)

        ];
        return $taskData;
    }

    private function duration(Task $task): int
    {
        $startDate = $task->start_date ?? $task->created_at;
        $endDate = $task->end_date_plan ?? ($task->start_date !== null ? $task->start_date : $task->created_at);

        $diff = (new \DateTime($startDate))->diff(new \DateTime($endDate))->days;
        return $diff + 1;
    }

    private function dateToTimestamp(string $data): int
    {
        return (new \DateTime($data))
                ->setTimezone(new \DateTimeZone('Europe/Moscow'))
                ->getTimestamp() * 1000;
    }

    private function taskStatus(Task $task): string
    {
        return Task::STATUSES_STRING[$task->status];
    }


    public function buildPrevTasks($project): array
    {
        $prevTasks = DB::table('tasks_prev')->whereIn('task_id', function ($query) use ($project) {
            $query->select('tproj.task_id')
                ->from('task_project', 'tproj')
                ->where('tproj.project_id', $project->id);
        })
            ->get()
            ->toArray();

        $prevMap = [];

        foreach ($prevTasks as $record) {
            if (isset($prevMap[$record->task_id])) {
                $prevMap[$record->task_id][] = $record->task_prev_id;
            } else {
                $prevMap[$record->task_id] = [$record->task_prev_id];
            }
        }

        return $prevMap;
    }

    private function clearShowInGanttTassk(TreeItem $treeItem)
    {
        foreach ($treeItem->getChilds() as $idx => $child) {
            if (!$child->getData()->show_in_gantt) {
                $treeItem->removeChild($idx);
            }
        }
        foreach ($treeItem->getChilds() as $child) {
            $this->clearShowInGanttTassk($child);
        }
    }
}
