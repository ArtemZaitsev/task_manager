<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\BuisinessLogick\Voter\PurchaseOrderVoter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderListController extends Controller
{
    public const ROUTE_NAME = 'purchase_order.list';

    const RECORDS_PER_PAGE = 20;

    public function __construct(
        private PurchaseOrderGrid $grid,
        private PurchaseOrderVoter $voter
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->grid->buildQuery($request);

        $components = $query->paginate(self::RECORDS_PER_PAGE)->withQueryString();

        return view('abstract_document.list', [
            'title' => 'Список заявок',
            'data' => $components,
            'grid' => $this->grid,
            'voter' => $this->voter,
            'links' => [
                'create' => route(PurchaseOrderCreateController::INDEX_ACTION, ['back' => url()->full()]),
                'reset' => route(self::ROUTE_NAME)
            ]
        ]);
    }
}
