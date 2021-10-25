<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Routing\Controller as BaseController;

class TestController extends BaseController
{
    public function index(){
//       $person = Person::all()[0];
//        foreach($person->tasks as $task) {
//            echo $task->name;
//        }

        $task = Task::all()[0];
        echo $task->person->name;


    }
}
