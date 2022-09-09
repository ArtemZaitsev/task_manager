<?php

namespace App\Http\Controllers\AbstractDocument;

use App\Http\Controllers\Component\Filter\DateFilter;
use App\Http\Controllers\Component\Filter\StringFilter;
use App\Lib\Grid\AbstractGrid;
use App\Lib\Grid\GridColumn;
use App\Models\Component\AbstractDocument;
use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class AbstractDocumentGrid extends AbstractGrid
{
    public function __construct(
        string           $gridName,
        protected string $editRoute,
        protected string $deleteRoute)
    {
        parent::__construct($gridName);
    }

    protected function buildColumns(): array
    {
       return [

           new GridColumn(
               'actions',
               'Действия',
               fn(AbstractDocument $entity) => view('lib.buttons.buttons', [
                       'buttons' => [
                           [
                               'template' => 'lib.buttons.edit_button',
                               'templateData' => [
                                   'url' => route($this->editRoute, [
                                       'id' => $entity->id,
                                       'back' => url()->full()
                                   ])
                               ]
                           ],
                           [
                               'template' => 'lib.buttons.delete_button',
                               'templateData' => [
                                   'url' => route($this->deleteRoute, [
                                       'id' => $entity->id,
                                       'back' => url()->full()
                                   ])
                               ]
                           ]
                       ]
                   ]
               )->toHtml(),
               null,
               null,
               true
           ),
           new GridColumn(
               'number',
               'Номер',
               fn(AbstractDocument $entity) => $entity->number,
               'number',
               new StringFilter('number'),
               true
           ),
           new GridColumn(
               'title',
               'Название',
               fn(AbstractDocument $entity) => $entity->title,
               'title',
               new StringFilter('title'),
               true
           ),
           new GridColumn(
               'date',
               'Дата',
               fn(AbstractDocument $entity) => DateUtils::dateToDisplayFormat($entity->date),
               'date',
               new DateFilter('date'),
               true
           ),
           new GridColumn(
               'file_path',
               'Файл',
               fn(AbstractDocument $entity) => $entity->file_path === null ? '' :
                   sprintf('<a href="%s" target="_blank">%s</a>',
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
        $query = $this->baseQuery();

        $this->applyFilters($query, $request);
        $this->applySort($query, $request);

        return $query;
    }

    protected abstract function baseQuery(): Builder;
}
