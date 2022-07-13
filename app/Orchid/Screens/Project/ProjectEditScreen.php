<?php

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use function redirect;

class ProjectEditScreen extends Screen
{
    public $project;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Project $project): iterable
    {
        return [
            'project' => $project,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->project->exist ? 'Редактирование проекта' : 'Создание проекта';;
    }

    public function description(): ?string
    {
        return "Проекты";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create post')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->project->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->project->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->project->exists),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('project.title')
                    ->title('Название проекта')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this post.'),


                Relation::make('project.heads.')
                    ->title('Руководители проекта')
                    ->fromModel(User::class, 'surname')
                    ->multiple()
                    ->displayAppend('label'),

                Relation::make('project.planer_id')
                    ->title('Планер проекта')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),

                Relation::make('project.watchers.')
                    ->title('Наблюдатели проекта')
                    ->fromModel(User::class, 'surname')
                    ->multiple()
                    ->displayAppend('label'),
            ])
        ];
    }

    public function createOrUpdate(Project $project, Request $request)
    {
        $request->validate([
            'project.heads' => 'required|array|min:1',
            'project.heads.*' => Rule::exists(User::class, 'id'),
            'project.watchers' => 'nullable|array',
            'project.watchers.*' => Rule::exists(User::class, 'id'),

            'project.planer_id' => [
                'nullable',
                'integer',
                Rule::exists(User::class, 'id'),
            ],
            'project.title' => [
                'required',
                Rule::unique(Project::class, 'title')->ignore($project),
            ],
        ]);
        DB::transaction(function () use ($request, $project) {
            $project->fill($request->get('project'))->save();
            $project->heads()->sync($request->input('project.heads') ?? []);
            $project->watchers()->sync($request->input('project.watchers') ?? []);
        });

        Alert::info('You have successfully created an post.');

        return redirect()->route('platform.project.list');
    }

    public function remove(Project $project)
    {
        $project->delete();

        Alert::info('You have successfully deleted the post.');
        return redirect()->route('platform.project.list');
    }
}
