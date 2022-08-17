<?php

namespace App\Http\Controllers\Sz;

use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\Sz;
use Illuminate\Http\Request;

class SzDeleteController extends Controller
{
    public const ROUTE_NAME = 'sz.delete';

    public function index(Request $request, $id)
    {
        try {
            $entity = Sz::query()->findOrFail($id);
            $entity->delete();
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при удалении: ' . $e->getMessage());
        }

        return RedirectUtils::redirectBack($request, self::ROUTE_NAME);
    }
}
