<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\BuisinessLogick\PlanerService;
use App\Http\Controllers\AbstractDocument\AbstractDocumentGrid;
use App\Models\Component\PurchaseOrder;
use Illuminate\Database\Eloquent\Builder;

class PurchaseOrderGrid extends AbstractDocumentGrid
{
    public function __construct(PlanerService $planerService)
    {
        parent::__construct(
            $planerService,
            'purchase_order',
            PurchaseOrderEditController::INDEX_ACTION,
            PurchaseOrderDeleteController::ROUTE_NAME,
            PurchaseOrderDownloadController::INDEX_ACTION,
        );
    }

    protected function baseQuery(): Builder
    {
        return PurchaseOrder::query();
    }


}

