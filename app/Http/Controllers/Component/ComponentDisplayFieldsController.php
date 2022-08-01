<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComponentDisplayFieldsController extends Controller
{
    public const ROUTE_NAME = 'component.save_fields';
    const COMPONENTS_FIELDS_SESSION_NAME = 'components_fields';

    public function processForm(Request $request)
    {
        $requestFields = array_keys($request->request->get('fields'));

        $request->session()->put(self::COMPONENTS_FIELDS_SESSION_NAME, $requestFields);

        return response()->json([
            'success' => true
        ]);
    }
}
