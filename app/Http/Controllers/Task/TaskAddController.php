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


    public function index (Request $request) {
        $users = User::all();
        $tasks = Task::all();

        return view('task_add',[
            'users' => $users,
            'tasks' => $tasks,
        ]);
    }

    public function processForm (Request $request) {
       $data = $this->validate($request, [

            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'user_id' => 'required|numeric',
            'parent_id' => 'nullable|numeric',
        ]);

       if(!isset($data['parent_id'])){
            $data['parent_id'] = null;
        };

       /** @var  User $user */

        $user = User::findOrFail($data['user_id']);
        $parentTask = $data['parent_id'] === null ? null : Task::findOrFail($data['parent_id']);

        $task = new Task();
        $task->name = $data['name'];
        $task->start_date = $data['start_date'];
        $task->end_date = $data['end_date'];
        $task->status = Task::STATUS_NEW;

        $task->user()->associate($user);
        if($parentTask !== null){
            $task->parent()->associate($parentTask);
        }

        $task->save();

        return redirect()->route('tasks.list')->with('success',__('task_add_success'));
    }


}
