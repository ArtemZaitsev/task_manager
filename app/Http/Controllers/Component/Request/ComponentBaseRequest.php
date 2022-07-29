<?php

namespace App\Http\Controllers\Component\Request;

use App\BuisinessLogick\ComponentVoter;
use App\Models\Component\Component;
use App\Models\Component\ComponentSourceType;
use App\Models\Component\PhysicalObject;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\Role;

class ComponentBaseRequest extends FormRequest
{
    protected array $rules = [];

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->rules[ComponentVoter::ROLE_CONSTRUCTOR] = [
            //todo add rules
        ];
        $this->rules[ComponentVoter::ROLE_PLANER] = array_merge($this->rules[ComponentVoter::ROLE_CONSTRUCTOR], [
            'title' => ['required', 'max:255'],
            'identifier' => ['nullable', 'max:255'],
            'constructor_id' => ['nullable', Rule::exists(User::class, 'id')],
            'source_type' => ['nullable', Rule::in(ComponentSourceType::values())],
            '3d_date_plan' => ['nullable', 'date'],
            'physical_object_id' => ['nullable', Rule::exists(PhysicalObject::class, 'id')],
            'relative_component_id' => ['nullable', Rule::exists(Component::class, 'id')]
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'required' => 'Поле обязательно для заполнения',
        ];
    }

    public function store(Component $entity): void
    {
        $data = $this->validated();

        DB::transaction(function () use ($entity, $data) {
            $entity->fill($data);
            $entity->save();

            if (isset($data['physical_objects'])) {
                $changes = $entity->physicalObjects()->sync($data['physical_objects']);
            }
        });
    }
}
