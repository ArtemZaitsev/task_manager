<?php

namespace App\Http\Controllers\Task;


use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskDelController extends Controller
{
    public function index(Request $request, $id){
        $task = Task::findOrFail($id);

        /** @var Task[] $tasksForDel */
        $tasksForDel = [
            $task

        ];

        $taskQueue = [
            $task
        ];

        while(count($taskQueue) > 0 ) {
            $current = array_shift($taskQueue);
            $nestedTasks = Task::where('parent_id', $current->id)->get();

            foreach ($nestedTasks as $task) {
                $taskQueue[] = $task;
                $tasksForDel[] = $task;
            }

        }

        $tasksForDel = array_reverse($tasksForDel);

        DB::transaction(function () use ($tasksForDel) {
            foreach ($tasksForDel as $task) {
                $task->delete();
            }
        });

        return redirect()->route('tasks.list')->with('success',__('messages.task_del_success'));

    }
}
