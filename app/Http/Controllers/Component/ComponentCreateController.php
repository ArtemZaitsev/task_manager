<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Component\Request\ComponentAddRequest;
use App\Http\Controllers\Controller;
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

        if ($request->query->has('back')) {
            $backUrl = $request->query->get('back');
            $response = redirect()->to($backUrl);
        } else {
            $response = redirect()->route('tasks.list');
        }

        return $response->with('success', __('messages.task_add_success'));
    }
}
