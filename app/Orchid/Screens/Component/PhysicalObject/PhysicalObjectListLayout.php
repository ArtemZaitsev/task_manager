<?php

namespace App\Orchid\Screens\Component\PhysicalObject;

use App\Models\Component\Detail;
use App\Models\Component\PhysicalObject;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PhysicalObjectListLayout extends Table
{
    protected $target = 'entity';

    protected function columns(): iterable
    {
        return [

            TD::make('name', 'Название объекта')
                ->filter(Input::make())
                ->render(function (PhysicalObject $entity) {
                    return Link::make($entity->name)
                        ->route(PhysicalObjectEditScreen::ROUTE_NAME, $entity);
                }),
            TD::make('target', 'Назначение объекта')
                ->filter(Input::make())
                ->render(function (PhysicalObject $entity) {
                    return PhysicalObject::ALL_TARGETS[$entity->target] ?? "";
                }),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
