<?php

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use App\Models\User;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProjectListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'projects';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Название проекта')
                ->render(function (Project $project) {
                    return Link::make($project->title)
                        ->route('platform.project.edit', $project);
                }),
            TD::make('head_id', 'Руководители проекта')
                ->render(function (Project $project) {
                    $heads = $project->heads()->get()->all();
                    $heads = array_map(fn(User $head)=>$head->label(),$heads);
                    $label = implode("</br>", $heads);
                    return $label;
                }),
            TD::make('planer_id', 'Планер проекта')
                ->render(function (Project $project) {
                    return $project->planer?->label;
                }),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
