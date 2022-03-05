<?php

namespace App\Http\Controllers\Task;

use Barryvdh\Debugbar\Controllers\BaseController;
use Illuminate\Http\Request;

class TaskColumnController extends BaseController
{
    const COLUMNS =[
        'project' => 'Проект',
        'family' => 'Семейство',
        'product' => 'Продукт',
        'direction' => 'Направление',
        'group' => 'Группа',
        'subgroup' => 'Подгруппа',
        'base' => 'Основание',
        'setting_date' => 'Дата постановки',
        'task_creator' => 'Постановщик',
        'priority' => 'Приоритет',

        ];

    public function index(){
        return view('task.setup_columns',['columns' => self::COLUMNS]);
    }

    public function processForm(Request $request){

        $columns = [];

        foreach ( self::COLUMNS as $name => $label){
            if ($request->request->has($name)){
                $columns[$name] = true;
            } else{
                $columns[$name] = false;
            }
        }

        $request->session()->put('task_columns', $columns);



        return redirect()->route('tasks.list');

    }
}
