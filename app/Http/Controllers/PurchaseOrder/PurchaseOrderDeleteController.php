<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\PurchaseOrder;
use App\Models\Component\Sz;
use Illuminate\Http\Request;

class PurchaseOrderDeleteController  extends Controller
{
    public const ROUTE_NAME = 'purchase_order.delete';

    public function index(Request $request, $id)
    {
        try {
            $entity = PurchaseOrder::query()->findOrFail($id);
            $entity->delete();
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при удалении: ' . $e->getMessage());
        }

        return RedirectUtils::redirectBack($request, self::ROUTE_NAME);
    }
}
