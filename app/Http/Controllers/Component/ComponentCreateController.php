<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Component\Request\ComponentAddRequest;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\Component;
use Illuminate\Http\Request;

class ComponentCreateController extends Controller
{
    public const INDEX_ACTION = 'component.create.index';
    public const PROCESS_FORM_ACTION = 'component.create.processForm';

    public function __construct(
        private ComponentControllerViewBuilder $viewBuilder
    )
    {

    }

    public function index(Request $request)
    {
        $entity = new Component();

        return view('component.edit', array_merge([
                'title' => "Создание компонента",
                'fieldsToEdit' => null,
            ], $this->viewBuilder->viewData($entity))
        );
    }

    public function processForm(ComponentAddRequest $request)
    {
        $entity = new Component();
        $request->store($entity);

        return RedirectUtils::redirectBack($request, ComponentController::ROUTE_NAME);
    }
}
