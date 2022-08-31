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

    public function forTask(Task $task)
    {
        $sql = <<<SQL
with recursive tree as (
    select * from tasks t where t.id=:taskId
    union all
    select t2.* from tasks t2 inner join tree on tree.id = t2.parent_id
)
select * from tree
SQL;
        $tasks = DB::select($sql, ['taskId' => $task->id]);
        $tasks = array_map(fn($obj) => (array) $obj, $tasks);
        $tasks = array_map(fn(array $row) => (new Task())->fill($row), $tasks);

        $rootTasks = [$task];
        return $this->buildJsonForTasks($tasks, $rootTasks);
    }

    public function forProject(Project $project): array
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
        $rootTasks = array_filter($tasks, fn(Task $task) => $task->parent_id === null);

       return $this->buildJsonForTasks($tasks, $rootTasks);
    }

    private function buildTree(TreeItem $treeNode, array $tasksChilds) {
        $childs = $tasksChilds[$treeNode->getData()->id] ?? [];
        $childNodes = array_map(fn(Task $task) => new TreeItem($task), $childs);

        foreach ($childNodes as $childNode) {
            $treeNode->addChild($childNode);
        }
        foreach ($childNodes as $childNode) {
            $this->buildTree($childNode, $tasksChilds);
        }
    }

    private function buildJsonForTasks(array $tasks, array $rootTasks) {
        //$prevTasks = $this->buildPrevTasks($project);
        $rootTaskIds = array_map(fn(Task $task) => $task->id, $rootTasks);
        $rootTrees = array_map(fn(Task $task) => new TreeItem($task), $rootTasks);

        $treeItemIdMap = [];
        foreach ($rootTrees as $item) {
            $treeItemIdMap[$item->getData()->id] = $item;
        }

        $tasks = array_filter($tasks, fn(Task $task) => !in_array($task->id, $rootTaskIds));
        $tasksChilds = $this->buildTaskChilds($tasks);

        foreach ($rootTrees as $treeNode) {
            $this->buildTree($treeNode, $tasksChilds);
        }

        foreach ($rootTrees as $idx => $treeItem) {
            if (!$treeItem->getData()->show_in_gantt) {
                unset($rootTrees[$idx]);
            }
        }
        foreach ($rootTrees as $treeItem) {
            $this->clearShowInGanttTassk($treeItem);
        }

        foreach ($rootTrees as $treeItem) {
            $treeItem->sort(fn(Task $a, Task $b)=> $a->number <=> $b->number);
        }

        $tasksJson = [];
        foreach ($rootTrees as $treeRoot) {
            $rootTasks = $this->treeList($treeRoot);
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

    private function buildTaskChilds(array $tasks): array
    {
        $childs = [];

        /** @var Task $task */
        foreach ($tasks as $task) {
            if($task->parent_id !== null) {
                if(!isset($childs[$task->parent_id])) {
                    $childs[$task->parent_id] = [];
                }
                $childs[$task->parent_id][] = $task;
            }
        }

        return $childs;
    }
}
