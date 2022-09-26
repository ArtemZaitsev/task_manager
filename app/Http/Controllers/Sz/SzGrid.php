<?php

namespace App\Http\Controllers\Sz;

use App\BuisinessLogick\PlanerService;
use App\Http\Controllers\AbstractDocument\AbstractDocumentGrid;
use App\Http\Controllers\Component\Filter\MultiSelectFilter;
use App\Lib\Grid\GridColumn;
use App\Lib\SelectUtils;
use App\Models\Component\PhysicalObject;
use App\Models\Component\Sz;
use App\Models\Direction;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SzGrid extends AbstractDocumentGrid
{
    public function __construct(PlanerService $planerService)
    {
        parent::__construct(
            $planerService,
            'sz',
            SzEditController::INDEX_ACTION,
            SzDeleteController::ROUTE_NAME,
            SzFileDownloadController::INDEX_ACTION
        );
    }

    protected function buildColumns(): array
    {
        $userSelectFilterData = SelectUtils::entityListToLabelMap(
            User::all()->all(),
            fn(User $entity) => $entity->label()
        );

        return [
            ... parent::buildColumns(),
            new GridColumn(
                'direction_initiator_id',
                'Направление',
                fn(Sz $entity) => $entity->initiator?->direction?->label(),
                null,
                null,
                true
            ),
            new GridColumn(
                'initiator_id',
                'Инициатор',
                fn(Sz $entity) => $entity->initiator?->label(),
                'initiator_id',
                new MultiSelectFilter('initiator_id', $userSelectFilterData),
                true
            ),
            new GridColumn(
                'target_user_id',
                'Адресат',
                fn(Sz $entity) => $entity->targetUser?->label(),
                'target_user_id',
                new MultiSelectFilter('target_user_id', $userSelectFilterData),
                true
            ),
        ];
    }

    protected function baseQuery(): Builder
    {
        return Sz::query();
    }
}
