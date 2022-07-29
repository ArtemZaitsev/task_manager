<?php

namespace App\Http\Controllers\Component;

use App\BuisinessLogick\ComponentVoter;
use App\Http\Controllers\Component\Request\ComponentEditRequest;
use App\Http\Controllers\Controller;
use App\Models\Component\Component;
use Illuminate\Http\Request;

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
                '3d_status',
                '3d_plan',
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
