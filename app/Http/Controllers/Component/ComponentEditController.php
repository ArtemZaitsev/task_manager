<?php

namespace App\Http\Controllers\Component;

use App\BuisinessLogick\ComponentVoter;
use App\Http\Controllers\Component\Request\ComponentEditRequest;
use App\Http\Controllers\Controller;
use App\Models\Component\Component;
use App\Models\Component\PhysicalObject;
use App\Models\User;
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

        if ($request->query->has('back')) {
            $backUrl = $request->query->get('back');
            $response = redirect()->to($backUrl);
        } else {
            $response = redirect()->route(ComponentController::ROUTE_NAME);
        }

        return $response->with('success', __('messages.task_edit_success'));
    }


}
