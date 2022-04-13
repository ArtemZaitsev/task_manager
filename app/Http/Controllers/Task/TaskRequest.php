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

class  TaskRequest extends FormRequest
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
        if (!$this->has('project')) {
            $this->request->set('project', []);
        };
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project' => 'required|array|min:1',
            'project.*' => Rule::exists(Project::class, 'id'),
            'family' => [
                'array',
                function ($attribute, $value, $fail){
                    $families = Family::whereIn('id', $value)->get()->toArray();
                    $familiesProjects = array_map(fn($family) => $family['project_id'], $families);
                    $projects = $this->get('project');
                    $invalidProjects = array_filter($familiesProjects,fn($projectId) => !in_array($projectId,
                        $projects));
                    if(count($invalidProjects)>0){
                        $fail('Семейства не соответствуют выбранным проектам');
                    }
                },
            ],
            'family.*' => Rule::exists(Family::class, 'id'),
            'product' => [
                'array',
                function ($attribute, $value, $fail) {
                    $products = Product::whereIn('id', $value)->get()->toArray();
                    $productsFamilies = array_map(fn($product) => $product['family_id'], $products);
                    $families = $this->get('family');
                    $invalidFamilies = array_filter($productsFamilies,fn($familyId) => !in_array($familyId,
                        $families));
                    if(count($invalidFamilies)>0){
                        $fail('Продукты не соответствуют выбранным семействам.');
                    }
                },
            ],
            'product.*' => Rule::exists(Product::class, 'id'),
            'base' => 'nullable|max:255',
            'setting_date' => 'nullable|date',
            'task_creator' => 'nullable|max:255',
            'priority' => ['nullable', Rule::in(array_keys(Task::All_PRIORITY))],
            'type' => ['nullable', Rule::in(array_keys(Task::All_TYPE))],
            'theme' => 'nullable|max:255',
            'main_task' => 'nullable|max:255',
            'name' => 'required|max:255',
            'user_id' => ['required', Rule::exists(User::class, 'id')],
            'coperformers' => 'nullable|array',
            'coperformers.*' => Rule::exists(User::class, 'id'),
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'execute' => ['nullable', Rule::in(array_keys(Task::ALL_EXECUTIONS))],
            'status' => ['required', Rule::in(array_keys(Task::ALL_STATUSES))],
            'comment' => 'nullable',
//            'parent_id' => 'required|numeric',
            'task_log.*.status' => ['required', Rule::in(array_keys(TaskLog::ALL_STATUSES))],
            'task_log.*.date_refresh_plan' => 'date',
            'task_log.*.date_refresh_fact' => 'date',
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
    public function store(Task $task){
        $data = $this->validated();
        /** @var  User $user */
        $user = User::findOrFail($data['user_id']);
        DB::transaction(function () use ($task, $data, $user) {
            $task->base = $data['base'];
            $task->setting_date = $data['setting_date'];
            $task->task_creator = $data['task_creator'];
            $task->priority = $data['priority'];
            $task->type = $data['type'];
            $task->theme = $data['theme'];
            $task->main_task = $data['main_task'];
            $task->name = $data['name'];
            $task->user()->associate($user);
            $task->start_date = $data['start_date'];
            $task->end_date = $data['end_date'];
            $task->execute = $data['execute'];
            $task->status = $data['status'];
            $task->comment = $data['comment'];
            //        $task->parent()->associate($parentTask);
            $task->save();
            $task->coperformers()->sync($data['coperformers'] ?? []);
            $task->projects()->sync($data['project'] ?? []);
            $task->families()->sync($data['family'] ?? []);
            $task->products()->sync($data['product'] ?? []);

            if (isset($data['task_log'])) {
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
