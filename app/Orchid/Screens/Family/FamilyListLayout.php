<?php

namespace App\Orchid\Screens\Family;

use App\Models\Family;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FamilyListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'families';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('project_id', 'Название проекта')
                ->render(function (Family $family) {
                    if ($family->project === null) {
                        return "";
                    }
                    return $family->project->title;
                }),
            TD::make('title', 'Название семейства продуктов')
                ->render(function (Family $family) {
                    return Link::make($family->title)
                        ->route('platform.family.edit', $family);
                }),


            TD::make('head_id', 'Руководитель семейства продуктов')
                ->render(function (Family $family) {
                    return $family->head?->label;
                }),
            TD::make('head_id', 'Планер семейства продуктов')
                ->render(function (Family $family) {
                    return $family->planer?->label;
                }),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
