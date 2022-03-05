<?php

namespace App\Orchid\Screens\Family;

use App\Models\Family;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class FamilyEditScreen extends Screen
{
    public $family;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Family $family): iterable
    {
        return [
            'family' => $family,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->family->exist ? 'Редактирование семейства продуктов' : 'Создание семейства продуктов';

    }

    public function description(): ?string
    {
        return "Семейство продуктов";
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
                ->canSee(!$this->family->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->family->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->family->exists),
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
                Input::make('family.title')
                    ->title('Название семейства продуктов')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this post.'),

                Relation::make('family.project_id')
                    ->title('Проект')
                    ->fromModel(Project::class, 'title'),

                Relation::make('family.head_id')
                    ->title('Руководитель семейства продуктов')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),

                Relation::make('family.planer_id')
                    ->title('Планер семейства продуктов')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),
            ])
        ];
    }

    public function createOrUpdate(Family $family, Request $request)
    {
        $request->validate([
            'family.title' => [
                'required',
                Rule::unique(Family::class, 'title')->ignore($family),
            ]

        ]);

        $family->fill($request->get('family'))->save();

        Alert::info('You have successfully created an post.');

        return redirect()->route('platform.family.list');
    }

    public function remove(Family $family)
    {
        $family->delete();

        Alert::info('You have successfully deleted the post.');
        return redirect()->route('platform.family.list');
    }

}
