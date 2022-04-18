<?php

namespace App\Http\Controllers\Task;


use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskDelController extends Controller
{
    public function index(Request $request, $id){

       /** @var Task $task */

        $task = Task::findOrFail($id);
        $task->delete();


        return redirect()->route('tasks.list')->with('success',__('messages.task_del_success'));

    }
}
