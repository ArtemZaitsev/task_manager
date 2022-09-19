<?php

namespace App\Http\Controllers\TechnicalTaskCalculation;

use App\BuisinessLogick\PlanerService;
use App\Http\Controllers\AbstractDocument\AbstractDocumentGrid;
use App\Models\Component\TechnicalTaskCalculation;
use Illuminate\Database\Eloquent\Builder;

class TechnicalTaskCalculationGrid  extends AbstractDocumentGrid
{
    public function __construct(PlanerService $planerService)
    {
        parent::__construct(
            $planerService,
            'ttc',
            TechnicalTaskCalculationEditController::INDEX_ACTION,
            TechnicalTaskCalculationDeleteController::ROUTE_NAME,
            TechnicalTaskCalculationFileDownloadController::INDEX_ACTION
        );
    }

    protected function baseQuery(): Builder
    {
        return TechnicalTaskCalculation::query();
    }
}
