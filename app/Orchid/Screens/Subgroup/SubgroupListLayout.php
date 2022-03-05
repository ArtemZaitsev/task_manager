<?php

namespace App\Orchid\Screens\Subgroup;

use App\Models\Subgroup;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SubgroupListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'subgroups';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('direction_id', 'Название направления')
                ->render(function (Subgroup $subgroup) {
                    return $subgroup->group->direction->title;
                }),

            TD::make('group_id', 'Название группы')
                ->render(function (Subgroup $subgroup) {
                    return $subgroup->group->title;
                }),

            TD::make('title', 'Название подгруппы')
                ->render(function (Subgroup $subgroup) {
                    return Link::make($subgroup->title)
                        ->route('platform.subgroup.edit', $subgroup);
                }),
            TD::make('head_id', 'Руководитель подгруппы')
                ->render(function (Subgroup $subgroup) {
                    return $subgroup?->head?->label;
                }),



            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
