<?php

namespace App\Http\Controllers\Sz;

use App\BuisinessLogick\PlanerService;
use App\Http\Controllers\AbstractDocument\AbstractDocumentGrid;
use App\Lib\Grid\GridColumn;
use App\Models\Component\PhysicalObject;
use App\Models\Component\Sz;
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
        return [
            ... parent::buildColumns(),
            new GridColumn(
                'initiator_id',
                'Инициатор',
                fn(Sz $entity) => $entity->initiator?->label(),
                'initiator_id',
                null,
                true
            ),
            new GridColumn(
                'target_user_id',
                'Адресат',
                fn(Sz $entity) => $entity->targetUser?->label(),
                'target_user_id',
                null,
                true
            ),
            new GridColumn(
                'objects',
                'Объекты',
                fn(Sz $entity) => implode('<br/>',
                    array_map(
                        fn(PhysicalObject $object) => $object->label(),
                        $entity->physicalObjects->all()
                    )
                ),
                null,
                null,
                true
            ),
        ];
    }

    protected function baseQuery(): Builder
    {
        return Sz::query();
    }
}
