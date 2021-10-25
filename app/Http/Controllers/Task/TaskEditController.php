<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use function __;
use function redirect;
use function view;

class TaskEditController extends Controller
{
    public function index(Request $request, $id){

        $task = Task::findOrFail($id);

        $users = User::all();
        $tasks = Task::all();

        return view('task_edit',[
            'users' => $users,
            'tasks' => $tasks,
            'task' => $task,
        ]);
    }

    public function processForm (Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $data = $this->validate($request, [
            'name' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'user_id' => 'required|numeric',
//            'parent_id' => 'required|numeric',
        ]);

        /** @var  User $user */

        $user = User::findOrFail($data['user_id']);
//        $parentTask = Task::findOrFail($data['parent_id']);


        $task->name = $data['name'];
        $task->start_date = $data['start_date'];
        $task->end_date = $data['end_date'];
//        $task->status = Task::STATUS_NEW;

        $task->user()->associate($user);
//        $task->parent()->associate($parentTask);

        $task->save();

        return redirect()->route('tasks.list')->with('success',__('task_add_success'));
    }
}
