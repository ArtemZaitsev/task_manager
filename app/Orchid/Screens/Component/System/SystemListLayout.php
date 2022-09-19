<?php

namespace App\Orchid\Screens\Component\System;

use App\Models\Component\System;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SystemListLayout extends Table
{
    protected $target = 'entity';

    protected function columns(): iterable
    {
        return [

            TD::make('title', 'Название')
                ->filter(Input::make())
                ->render(function (System $entity) {
                    return Link::make($entity->title)
                        ->route(SystemEditScreen::ROUTE_NAME, $entity);
                }),
            TD::make('metasystem_id', 'Название верхнеуровневой системы')
                ->render(function (System $entity) {
                    if ($entity->metasystem === null) {
                        return "";
                    }
                    return $entity->metasystem->title;
                }),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
