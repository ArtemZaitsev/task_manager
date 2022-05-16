<?php

namespace App\Http\Controllers\Task\Request;

use App\BuisinessLogick\TaskVoter;
use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class  TaskEditRequest extends BaseTaskRequest
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

    protected function prepareForValidation()
    {
        $task = $this->currentTask();
        $voter = new TaskVoter();
        $role = $voter->editRole($task);

        if ($role === 'performer') {

        } elseif ($role === 'planer') {
           $this->setDefaultFields();
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $task = $this->currentTask();

        $voter = new TaskVoter();
        return $this->rules[$voter->editRole($task)];
    }

    private function currentTask(): Task
    {
        $id = $this->route()->parameter('id');
        $task = Task::query()->findOrFail($id);
        return $task;
    }
}
