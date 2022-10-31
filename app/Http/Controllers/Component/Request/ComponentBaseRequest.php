<?php

namespace App\Http\Controllers\Component\Request;

use App\BuisinessLogick\AuditService;
use App\BuisinessLogick\Voter\ComponentVoter;
use App\Models\Component\Component;
use App\Models\Component\Component3dStatus;
use App\Models\Component\ComponentCalcStatus;
use App\Models\Component\ComponentDdStatus;
use App\Models\Component\ComponentManufactorStartWay;
use App\Models\Component\ComponentManufactorStatus;
use App\Models\Component\ComponentPurchaserStatus;
use App\Models\Component\ComponentSourceType;
use App\Models\Component\ComponentStatus;
use App\Models\Component\ComponentType;
use App\Models\Component\ComponentVersion;
use App\Models\Component\DrawingFile;
use App\Models\Component\Metasystem;
use App\Models\Component\PhysicalObject;
use App\Models\Component\PurchaseOrder;
use App\Models\Component\Subsystem;
use App\Models\Component\System;
use App\Models\Component\Sz;
use App\Models\Component\TechnicalTaskCalculation;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ComponentBaseRequest extends FormRequest
{
    protected array $rules = [];

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->rules[ComponentVoter::ROLE_CONSTRUCTOR] = [
            'quantity_in_object' => ['nullable', 'numeric'],
            'source_type' => ['nullable', Rule::in(ComponentSourceType::values())],
            'version' => ['nullable', Rule::in(ComponentVersion::values())],
            'type' => ['nullable', Rule::in(ComponentType::values())],
            '3d_status' => ['nullable', Rule::in(Component3dStatus::values())],
            '3d_date_plan' => ['nullable', 'date'],
            'dd_status' => ['nullable', Rule::in(ComponentDdStatus::values())],
            'dd_date_plan' => ['nullable', 'date'],
            'calc_status' => ['nullable', Rule::in(ComponentCalcStatus::values())],
            'calc_date_plan' => ['nullable', 'date'],
            'constructor_priority' => ['nullable', 'numeric'],
            'constructor_comment' => ['nullable', 'string'],
            'manufactor_start_way' => ['nullable', Rule::in(ComponentManufactorStartWay::values())],
            'sz_id' => ['nullable', Rule::exists(Sz::class, 'id')],
            'technical_task_calculation_id' => ['nullable', Rule::exists(TechnicalTaskCalculation::class, 'id')],
            'drawing_files_id' => ['nullable', Rule::exists(DrawingFile::class, 'id')],
            'status' => [Rule::in(array_keys(ComponentStatus::LABELS))],
        ];
        $this->rules[ComponentVoter::ROLE_MANUFACTOR] = [
            'manufactor_status' => ['nullable', Rule::in(ComponentManufactorStatus::values())],
            'manufactor_date_plan' => ['nullable', 'date'],
            'manufactor_sz_quantity' => ['nullable', 'numeric'],
            'manufactor_priority' => ['nullable', 'numeric'],
            'manufactor_comment' => ['nullable', 'string'],
        ];

        $this->rules[ComponentVoter::ROLE_PURCHASER] = [
            'purchase_status' => ['nullable', Rule::in(ComponentPurchaserStatus::values())],
            'purchase_date_plan' => ['nullable', 'date'],
            'purchase_request_quantity' => ['nullable', 'numeric'],
            'purchase_request_priority' => ['nullable', 'numeric'],
            'purchase_comment' => ['nullable', 'string'],
            'purchase_order_id' => ['nullable', Rule::exists(PurchaseOrder::class, 'id')],
        ];

        $this->rules[ComponentVoter::ROLE_PLANER] = array_merge(
            $this->rules[ComponentVoter::ROLE_CONSTRUCTOR],
            $this->rules[ComponentVoter::ROLE_PURCHASER],
            $this->rules[ComponentVoter::ROLE_MANUFACTOR],
            [
                'purchaser_id' => ['nullable', Rule::exists(User::class, 'id')],
                'manufactor_id' => ['nullable', Rule::exists(User::class, 'id')],
                'title' => ['required', 'max:255'],
                'identifier' => ['nullable', 'max:255'],
                'is_highlevel' => ['required', 'boolean'],
                'metasystem_id' => ['nullable', Rule::exists(Metasystem::class, 'id')],
                'system_id' => ['nullable', Rule::exists(System::class, 'id')],
                'subsystem_id' => ['nullable', Rule::exists(Subsystem::class, 'id')],
                'constructor_id' => ['nullable', Rule::exists(User::class, 'id')],
                'physical_object_id' => ['nullable', Rule::exists(PhysicalObject::class, 'id')],
                'relative_component_id' => ['nullable', Rule::exists(Component::class, 'id')],
                'tz_files' => ['nullable', 'string'],
                'tz_date' => ['nullable', 'date'],
            ]);
        $this->rules[ComponentVoter::ROLE_ADMIN] = $this->rules[ComponentVoter::ROLE_PLANER];
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
        $auditService = $this->container->get(AuditService::class);
        $data = $this->validated();

        DB::transaction(function () use ($auditService, $entity, $data) {
            $entity->fill($data);
            $dirty = $entity->isDirty();
            $entity->save();

            if ($dirty) {
                $auditService->editEntity($entity);
            }

            if (isset($data['physical_objects'])) {
                $changes = $entity->physicalObjects()->sync($data['physical_objects']);
            }
        });
    }
}
