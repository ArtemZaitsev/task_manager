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



//        return redirect()->route('tasks.list')->with('success',__('messages.task_del_success'));


        if ($request->query->has('back')) {
            $backUrl = $request->query->get('back');
            $response = redirect()->to($backUrl);
        } else {
            $response = redirect()->route('tasks.list');
        }

        $task->delete();

        return $response->with('success', __('messages.task_del_success'));

    }
}
