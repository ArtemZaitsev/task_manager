<?php

namespace App\Http\Controllers\TechnicalTaskCalculation;

use App\Http\Controllers\AbstractDocument\AbstractDocumentGrid;
use App\Models\Component\TechnicalTaskCalculation;
use Illuminate\Database\Eloquent\Builder;

class TechnicalTaskCalculationGrid  extends AbstractDocumentGrid
{
    public function __construct()
    {
        parent::__construct(
            'ttc',
            TechnicalTaskCalculationEditController::INDEX_ACTION,
            TechnicalTaskCalculationDeleteController::ROUTE_NAME
        );
    }

    protected function baseQuery(): Builder
    {
        return TechnicalTaskCalculation::query();
    }
}
