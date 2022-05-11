<?php

namespace App\BuisinessLogick;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class TaskService
{
    private PlanerService $planerService;

    public function __construct(){
        $this->planerService = new PlanerService();
    }

    public function editUrl(Task $task):string{
        if($this->planerService->userIsPlaner(Auth::id())){
            return route('task.edit',['id' => $task->id, 'back' => Request::fullUrl()]);
        }

        if($task->user_id === Auth::id()){
            return route('task.edit_by_performer',['id' => $task->id, 'back' => Request::fullUrl()]);
        }

        throw new \LogicException('Invalid access rights');
    }
}
