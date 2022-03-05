<?php

namespace App\Http\Controllers\Task;


use App\Http\Controllers\Controller;
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
        $users = User::all();
//        $tasks = Task::all();

        return view('task.add', [
            'users' => $users,
//            'tasks' => $tasks,
        ]);
    }

    public function processForm(Request $request)
    {
        $data = $this->validate($request, [

            'base' => 'nullable|max:255',
            'setting_date' => 'nullable|date',
            'task_creator' => 'nullable|max:255',
            'priority' => 'nullable|numeric',
            'type' => 'nullable|numeric',
            'theme' => 'nullable|max:255',
            'main_task' => 'nullable|max:255',
            'name' => 'required|max:255',
            'user_id' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'execute' => 'nullable|numeric',
            'status' => 'required|numeric',
//            'parent_id' => 'nullable|numeric',
        ]);

//        if (!isset($data['parent_id'])) {
//            $data['parent_id'] = null;
//        };

        /** @var  User $user */

        $user = User::findOrFail($data['user_id']);
//        $parentTask = $data['parent_id'] === null ? null : Task::findOrFail($data['parent_id']);

        $task = new Task();
        $task->base = $data['base'];
        $task->setting_date = $data['setting_date'];
        $task->task_creator = $data['task_creator'];
        $task->priority = $data['priority'];
        $task->type = $data['type'];
        $task->theme = $data['theme'];
        $task->main_task = $data['main_task'];
        $task->name = $data['name'];
        $task->user()->associate($user);
        $task->start_date = $data['start_date'];
        $task->end_date = $data['end_date'];
        $task->execute = $data['execute'];
        $task->status = $data['status'];
        $task->sort = 0;
        $task->number = 'lorem';
        $task->progress = 0;


//        if ($parentTask !== null) {
//            $task->parent()->associate($parentTask);
//        }

//        $sortNumber = $this->findPreviosSortNumber($parentTask);
//        if ($sortNumber === null) {
//            $task->sort = 10;
//        } else {
//            $task->sort = $sortNumber + 10;
//        }


//        $number = $this->findPreviosTaskNumber($parentTask);
//        if ($number === null) {
//            if ($parentTask === null) {
//                $task->number = [1];
//            } else {
//                $parentTaskNumber = $parentTask->number;
//                $parentTaskNumber[] = 1;
//                $task->number = $parentTaskNumber;
//            }
//        } else {
//            $number[count($number) - 1]++;
//            $task->number = $number;
//        }

        $task->save();

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
