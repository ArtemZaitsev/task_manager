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

abstract class BaseTaskRequest extends FormRequest
{
    protected array $rules = [];

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->rules [TaskVoter::ROLE_PERFORMER] = [
            'end_date_plan' => 'nullable|date',
            'end_date_fact' => 'nullable|date',
            'execute' => ['nullable', Rule::in(array_keys(Task::ALL_EXECUTIONS))],
            'status' => ['required', Rule::in(array_keys(Task::ALL_STATUSES))],
            'comment' => 'nullable',
            'task_log.*.id' => ['nullable'],
            'task_log.*.status' => ['required', Rule::in(array_keys(TaskLog::ALL_STATUSES))],
            'task_log.*.date_refresh_plan' => ['nullable', 'date'],
            'task_log.*.date_refresh_fact' => ['nullable', 'date'],
            'task_log.*.trouble' => ['required', 'max:255'],
            'task_log.*.what_to_do' => ['nullable', 'max:255'],
        ];
        $this->rules[TaskVoter::ROLE_PLANER] = array_merge($this->rules[TaskVoter::ROLE_PERFORMER], [
            'project' => 'required|array|min:1',
            'project.*' => Rule::exists(Project::class, 'id'),
            'family' => [
                'array',
                function ($attribute, $value, $fail) {
                    $families = Family::whereIn('id', $value)->get()->toArray();
                    $familiesProjects = array_map(fn($family) => $family['project_id'], $families);
                    $projects = $this->get('project');
                    $invalidProjects = array_filter($familiesProjects, fn($projectId) => !in_array($projectId,
                        $projects));
                    if (count($invalidProjects) > 0) {
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
                    $invalidFamilies = array_filter($productsFamilies, fn($familyId) => !in_array($familyId,
                        $families));
                    if (count($invalidFamilies) > 0) {
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
            'name' => 'required',
            'user_id' => ['required', Rule::exists(User::class, 'id')],
            'coperformers' => 'nullable|array',
            'coperformers.*' => Rule::exists(User::class, 'id'),
            'end_date' => 'required|date',
        ]);

    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    protected function setDefaultFields() {
        $fields = ['project', 'coperformers', 'family', 'product'];
        foreach ($fields as $field) {
            if (!$this->has($field)) {
                $this->request->set($field, []);
            };
        }
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

        $taskLogsIdMap = [];
        foreach ($task->logs as $log) {
            $taskLogsIdMap[$log->id] = $log;
        }

        DB::transaction(function () use ($task, $data, $taskLogsIdMap) {
            $task->fill($data);
            $task->save();

            if (isset($data['coperformers'])) {
                $task->coperformers()->sync($data['coperformers']);
            }
            if (isset($data['project'])) {
                $task->projects()->sync($data['project']);
            }
            if (isset($data['family'])) {
                $task->families()->sync($data['family']);
            }
            if (isset($data['product'])) {
                $task->products()->sync($data['product']);
            }


            $requestTaskLogs = $data['task_log'] ?? [];
            foreach ($requestTaskLogs as $row) {
                if (empty($row['id'])) {
                    $taskLog = new TaskLog();
                    $taskLog->fill($row);
                    $taskLog->task_id = $task->id;
                    $taskLog->save();
                } else {
                    /** @var TaskLog $existingLog */
                    $existingLog = $taskLogsIdMap[$row['id']] ?? null;
                    if ($existingLog !== null) {
                        $existingLog->fill($row);
                        $existingLog->save();
                    }
                }
            }

            $ids = array_map(fn($data) => $data['id'], $requestTaskLogs);
            $ids = array_filter($ids);

            foreach (array_keys($taskLogsIdMap) as $existingId) {
                if (!in_array($existingId, $ids)) {
                    $taskLogsIdMap[$existingId]->delete();
                }
            }


        });
    }


}