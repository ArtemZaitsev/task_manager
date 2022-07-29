<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Controller;
use App\Models\Component\Component;
use Illuminate\Http\Request;

class ComponentDeleteController extends Controller
{
    public const ROUTE_NAME = 'component.delete';

    public function index(Request $request, $id)
    {
        $entity = Component::query()->findOrFail($id);
        $entity->delete();

        return RedirectUtils::redirectBack($request, ComponentController::ROUTE_NAME);
    }
}
