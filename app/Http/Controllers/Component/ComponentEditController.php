<?php

namespace App\Http\Controllers\Component;

use App\BuisinessLogick\ComponentVoter;
use App\Http\Controllers\Component\Request\ComponentEditRequest;
use App\Http\Controllers\Controller;
use App\Models\Component\Component;
use App\Models\Component\ComponentManufactorStatus;
use App\Models\Component\ComponentPurchaserStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComponentEditController extends Controller
{
    public const EDIT_ACTION = 'component.edit';
    public const INDEX_ACTION = 'component.edit.index';

    public function __construct(
        private ComponentVoter                 $voter,
        private ComponentControllerViewBuilder $viewBuilder
    )
    {
    }

    public function index(Request $request, $id)
    {
        $entity = Component::findOrFail($id);

        $fieldsToEdit = match ($this->voter->editRole($entity)) {
            ComponentVoter::ROLE_PLANER => null,
            ComponentVoter::ROLE_CONSTRUCTOR => [
                'quantity_in_object',
                'entry_level',
                'source_type',
                'version',
                'type',
                '3d_status',
                '3d_date_plan',
                'dd_status',
                'dd_date_plan',
                'calc_status',
                'calc_date_plan',
                'constructor_priority',
                'constructor_comment',
                'manufactor_start_way'
            ],
            ComponentVoter::ROLE_MANUFACTOR =>[
                'manufactor_status',
                'manufactor_date_plan',
                'manufactor_sz_files',
                'manufactor_sz_date',
                'manufactor_sz_quantity',
                'manufactor_priority',
                'manufactor_comment',
            ],
            ComponentVoter::ROLE_PURCHASER =>[
                'purchase_status',
                'purchase_date_plan',
                'purchase_request_files',
                'purchase_request_date',
                'purchase_request_quantity',
                'purchase_request_priority',
                'purchase_comment',
            ],
            default => []
        };

        return view('component.edit', array_merge([
            'title' => 'Редактирование компонента',
            'fieldsToEdit' => $fieldsToEdit,
        ], $this->viewBuilder->viewData($entity)
        ));
    }

    public function processForm(ComponentEditRequest $request, $id)
    {
        $entity = Component::findOrFail($id);
        $request->store($entity);

        return RedirectUtils::redirectBack($request, ComponentController::ROUTE_NAME);
    }


}
