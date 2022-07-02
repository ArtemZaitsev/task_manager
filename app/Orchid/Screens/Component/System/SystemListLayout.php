<?php

namespace App\Orchid\Screens\Component\System;

use App\Models\Component\System;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SystemListLayout extends Table
{
    protected $target = 'systems';

    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Название системы')
                ->filter(Input::make())
                ->render(function (System $system) {
                    return Link::make($system->name)
                        ->route(SystemEditScreen::ROUTE_NAME, $system);
                }),
            TD::make('head_id', 'Руководитель системы')
                ->render(function (System $system) {
                    return $system?->head?->label;
                }),

            TD::make('number', 'Название системы')
                ->filter(Input::make()),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
