<?php

namespace App\Http\Controllers\Task;


use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use function __;
use function redirect;
use function view;

class TaskAddController extends Controller
{


    public function index(Request $request)
    {
        $task = new Task();
        $task->status = Task::STATUS_NOT_DONE;
        $task->priority = Task::PRIORITY_LOW;
        $task->execute = Task::EXECUTE_DONT_KNOW;
        $task->type = Task::TYPE_NOT_PLAN;
        $logs = [];

        return view('task.edit', [
            'actionUrl' => route('task.store'),
            'title' => "Создание задачи",
            'users' => User::all(),
            'projects' => Project::all(),
            'families' => Family::all(),
            'products' => Product::all(),
            'task' => $task,
            'logs' => $logs,
        ]);
    }

    public function processForm(TaskRequest $request)
    {
        $task = new Task();
        $request->store($task);
        return redirect()->route('tasks.list')->with('success', __('messages.task_add_success'));
    }

//    private function findPreviosSortNumber(?Task $parentTask): ?int
//    {
//        $task = $this->findLatestTask($parentTask);
//        if ($task === null) {
//            return null;
//        } else {
//            return $task->sort;
//        }
//    }

//    private function findPreviosTaskNumber(?Task $parentTask): ?array
//    {
//        $task = $this->findLatestTask($parentTask);
//        if ($task === null) {
//            return null;
//        } else {
//            return $task->number;
//        }
//    }


//    private function findLatestTask(?Task $parentTask): ?Task
//    {
//        if ($parentTask === null) {
//            $task = Task::query()
//                ->whereNull('parent_id')
//                ->orderByDesc('sort')
//                ->limit(1)
//                ->first();
//            return $task;
//        } else {
//            $task = Task::query()
//                ->where('parent_id', $parentTask->id)
//                ->orderByDesc('sort')
//                ->limit(1)
//                ->first();
//            return $task;
//
//        }
//    }
}
