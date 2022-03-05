<?php

namespace App\Orchid\Screens\Group;

use App\Models\Group;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class GroupListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'groups';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('direction_id', 'Название Направления')
                ->render(function (Group $group) {
                    if ($group->direction === null) {
                        return "";
                    }
                    return $group->direction->title;
                }),
            TD::make('title', 'Название группы')
                ->render(function (Group $group) {
                    return Link::make($group->title)
                        ->route('platform.group.edit', $group);
                }),


            TD::make('head_id', 'Руководитель группы')
                ->render(function (Group $group) {
                    if ($group->head === null) {
                        return "";
                    }
                    return $group->head->label;
                }),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
