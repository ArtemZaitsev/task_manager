<?php

namespace App\Http\Controllers\Task;

use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskByPerformerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'execute' => ['nullable', Rule::in(array_keys(Task::ALL_EXECUTIONS))],
            'status' => ['required', Rule::in(array_keys(Task::ALL_STATUSES))],
            'comment' => 'nullable',
//            'parent_id' => 'required|numeric',
            'task_log.*.status' => ['required', Rule::in(array_keys(TaskLog::ALL_STATUSES))],
            'task_log.*.date_refresh_plan' => ['nullable','date'],
            'task_log.*.date_refresh_fact' => ['nullable','date'],
            'task_log.*.trouble' => 'required|max:255',
            'task_log.*.what_to_do' => 'max:255',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Поле обязательно для заполнения',
        ];
    }

     public function store(Task $task)
    {
        $data = $this->validated();

        DB::transaction(function () use ($task, $data) {
            $task->execute = $data['execute'];
            $task->status = $data['status'];
            $task->comment = $data['comment'];
            $task->save();

            if(isset($data['task_log'])){
                foreach ($data['task_log'] as $id => $taskLogData) {
                    $taskLog = TaskLog::findOrFail($id);
                    $taskLog->status = $taskLogData['status'];
                    $taskLog->date_refresh_plan = $taskLogData['date_refresh_plan'];
                    $taskLog->date_refresh_fact = $taskLogData['date_refresh_fact'];
                    $taskLog->trouble = $taskLogData['trouble'];
                    $taskLog->what_to_do = $taskLogData['what_to_do'];

                    $taskLog->save();
                }
            }
        });
    }
}
