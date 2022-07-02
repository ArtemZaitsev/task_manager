<?php

namespace App\Orchid\Screens\Component\Detail;

use App\Models\Component\Detail;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class DetailListLayout extends Table
{
    protected $target = 'details';

    protected function columns(): iterable
    {
        return [

            TD::make('number', 'Идентификатор')
                ->filter(Input::make()),

            TD::make('name', 'Название компонента')
                ->filter(Input::make())
                ->render(function (Detail $entity) {
                    return Link::make($entity->name)
                        ->route(DetailEditScreen::ROUTE_NAME, $entity);
                }),
            TD::make('head_id', 'Ответственный за компонент')
                ->render(function (Detail $entity) {
                    return $entity?->head?->label;
                }),

            TD::make('subsystem_id', 'Подсистема')
                ->render(function (Detail $entity) {
                    return $entity->subsystem?->name;
                }),

            TD::make('system_id', 'Система')
                ->render(function (Detail $entity) {
                    return $entity->system?->name;
                }),


            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
