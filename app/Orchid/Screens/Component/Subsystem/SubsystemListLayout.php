<?php

namespace App\Orchid\Screens\Component\Subsystem;

use App\Models\Component\Detail;
use App\Models\Component\PhysicalObject;
use App\Models\Component\Subsystem;
use App\Models\Component\System;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SubsystemListLayout extends Table
{
    protected $target = 'entity';

    protected function columns(): iterable
    {
        return [

            TD::make('title', 'Название')
                ->filter(Input::make())
                ->render(function (Subsystem $entity) {
                    return Link::make($entity->title)
                        ->route(SubsystemEditScreen::ROUTE_NAME, $entity);
                }),
            TD::make('system_id', 'Название Системы')
                ->render(function (Subsystem $entity) {
                    if ($entity->system === null) {
                        return "";
                    }
                    return $entity->system->title;
                }),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
