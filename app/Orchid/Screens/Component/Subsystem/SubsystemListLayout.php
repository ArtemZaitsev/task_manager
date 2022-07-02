<?php

namespace App\Orchid\Screens\Component\Subsystem;

use App\Models\Component\Subsystem;
use App\Models\Component\System;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SubsystemListLayout extends Table
{
    protected $target = 'subsystems';

    protected function columns(): iterable
    {
        return [
            TD::make('number', 'Идентификатор')
                ->filter(Input::make()),

            TD::make('name', 'Название подсистемы')
                ->filter(Input::make())
                ->render(function (Subsystem $entity) {
                    return Link::make($entity->name)
                        ->route(SubsystemEditScreen::ROUTE_NAME, $entity);
                }),
            TD::make('head_id', 'Руководитель подсистемы')
                ->render(function (Subsystem $entity) {
                    return $entity?->head?->label;
                }),

            TD::make('subsystem_id', 'Система')
                ->render(function (Subsystem $entity) {
                    return $entity->system?->name;
                }),



            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
