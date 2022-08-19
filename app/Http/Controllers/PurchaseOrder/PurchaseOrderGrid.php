<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\Http\Controllers\AbstractDocument\AbstractDocumentGrid;
use App\Models\Component\PurchaseOrder;
use Illuminate\Database\Eloquent\Builder;

class PurchaseOrderGrid extends AbstractDocumentGrid
{
    public function __construct()
    {
        parent::__construct(
            'purchase_order',
            PurchaseOrderEditController::INDEX_ACTION,
            PurchaseOrderDeleteController::ROUTE_NAME
        );
    }

    protected function baseQuery(): Builder
    {
        return PurchaseOrder::query();
    }
}

