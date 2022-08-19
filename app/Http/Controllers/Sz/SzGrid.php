<?php

namespace App\Http\Controllers\Sz;

use App\Http\Controllers\AbstractDocument\AbstractDocumentGrid;
use App\Models\Component\Sz;
use Illuminate\Database\Eloquent\Builder;

class SzGrid extends AbstractDocumentGrid
{
    public function __construct()
    {
        parent::__construct(
            'sz',
            SzEditController::INDEX_ACTION,
            SzDeleteController::ROUTE_NAME
        );
    }

    protected function baseQuery(): Builder
    {
        return Sz::query();
    }
}
