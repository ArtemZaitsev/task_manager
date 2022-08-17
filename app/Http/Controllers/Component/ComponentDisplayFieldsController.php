<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComponentDisplayFieldsController extends Controller
{
    public const ROUTE_NAME = 'component.save_fields';

    public function __construct(private ComponentGrid $grid)
    {
    }

    public function processForm(Request $request)
    {
        $requestFields = array_keys($request->request->get('fields'));

        $this->grid->saveFields($requestFields);

        return response()->json([
            'success' => true
        ]);
    }
}
