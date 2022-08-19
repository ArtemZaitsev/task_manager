<?php

namespace App\Http\Controllers\TechnicalTaskCalculation;

use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\PurchaseOrder;
use App\Models\Component\TechnicalTaskCalculation;
use Illuminate\Http\Request;

class TechnicalTaskCalculationDeleteController extends Controller
{
    public const ROUTE_NAME = 'ttc.delete';

    public function index(Request $request, $id)
    {
        try {
            $entity = TechnicalTaskCalculation::query()->findOrFail($id);
            $entity->delete();
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при удалении: ' . $e->getMessage());
        }

        return RedirectUtils::redirectBack($request, self::ROUTE_NAME);
    }
}
