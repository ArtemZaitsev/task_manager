<?php

namespace App\Orchid\Screens\Direction;

use App\Models\Direction;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class DirectionListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'directions';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Название направления')
                ->filter(Input::make())
                ->render(function (Direction $direction) {
                    return Link::make($direction->title)
                        ->route('platform.direction.edit', $direction);
                }),
            TD::make('head_id', 'Руководитель направления')
                ->render(function (Direction $direction) {
                    return $direction->head?->label;
                }),
            TD::make('planer_id', 'Планер направления')
                ->render(function (Direction $direction) {
                    return $direction->planer?->label;
                }),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
