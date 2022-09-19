<?php

namespace App\Orchid\Screens\Component\Metasystem;

use App\Models\Component\Metasystem;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class MetasystemListLayout extends Table
{
    protected $target = 'entity';

    protected function columns(): iterable
    {
        return [

            TD::make('title', 'Название')
                ->filter(Input::make())
                ->render(function (Metasystem $entity) {
                    return Link::make($entity->title)
                        ->route(MetasystemEditScreen::ROUTE_NAME, $entity);
                }),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
