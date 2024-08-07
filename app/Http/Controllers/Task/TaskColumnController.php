<?php

namespace App\Http\Controllers\Task;

use Barryvdh\Debugbar\Controllers\BaseController;
use Illuminate\Http\Request;

class TaskColumnController extends BaseController
{
    const COLUMNS = [
        'created_at' => 'Дата создания',
        'updated_at' => 'Дата редактирования',
        'component_id' => 'Компонент',
        'priority' => 'Приоритет',
        'type' => 'Тип',
        'base' => 'Основание',
        'setting_date' => 'Дата постановки',
        'task_creator' => 'Постановщик',
        'direction' => 'Направление',
        'group' => 'Группа',
        'subgroup' => 'Подгруппа',
        'project' => 'Проект',
        'family' => 'Семейство',
        'product' => 'Продукт',
        'physical_object' => 'Объект',
        'task_document_id' => 'Основание',
        'task_approve_document_id' => 'Подтверждение',
        'theme' => 'Тема',
        'main_task' => 'Основная задача',
        'coperformers' => 'Соисполнители',
        'end_date_fact' => 'Дата окончания факт',
        'progress' => '% выполнения',
        'execute' => 'Приступить',
        'status' => 'Статус выполнения',
        'execute_time_plan' => 'Кол-во ч/ч, план',
        'execute_time_fact' => 'Кол-во ч/ч, факт',
        'comment' => 'Комментарии',


    ];

    public function index()
    {
        return view('task.setup_columns', ['columns' => self::COLUMNS]);
    }

    public function processForm(Request $request)
    {

        $columns = [];

        foreach (self::COLUMNS as $name => $label) {
            if ($request->request->has($name)) {
                $columns[$name] = true;
            } else {
                $columns[$name] = false;
            }
        }

        $request->session()->put('task_columns', $columns);

        return redirect()->route('tasks.list');

    }
}
