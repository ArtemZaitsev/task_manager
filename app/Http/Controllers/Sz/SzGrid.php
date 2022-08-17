<?php

namespace App\Http\Controllers\Sz;

use App\Http\Controllers\Component\Filter\DateFilter;
use App\Http\Controllers\Component\Filter\StringFilter;
use App\Lib\Grid\AbstractGrid;
use App\Lib\Grid\GridColumn;
use App\Models\Component\Component;
use App\Models\Component\Sz;
use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SzGrid extends AbstractGrid
{
    public function __construct()
    {
        parent::__construct('sz');

        $this->columns = [

            new GridColumn(
                'actions',
            'Действия',
                fn(Sz $entity) => view('lib.buttons.delete_button', [
                    'url' => route(SzDeleteController::ROUTE_NAME, [
                        'id' => $entity->id,
                        'back' => url()->full()
                    ])
                ])->toHtml(),
            null,
            null,
            true
            ),
            new GridColumn(
                'number',
                'Номер',
                fn(Sz $entity) => $entity->number,
                'number',
                new StringFilter('number'),
                true
            ),
            new GridColumn(
                'title',
                'Название',
                fn(Sz $entity) => $entity->title,
                'title',
                new StringFilter('title'),
                true
            ),
            new GridColumn(
                'date',
                'Дата',
                fn(Sz $entity) => DateUtils::dateToDisplayFormat($entity->date),
                'date',
                new DateFilter('date'),
                true
            ),
            new GridColumn(
                'file_path',
                'Файл',
                fn(Sz $entity) => sprintf('<a href="%s" target="_blank">%s</a>',
                    '/files/' . $entity->file_path,
                    'Файл'),
                null,
                null,
                true
            ),
        ];
    }

    public function buildQuery(Request $request): Builder
    {
        $query = Sz::query();

        $this->applyFilters($query, $request);
        $this->applySort($query, $request);

        return $query;
    }


}
