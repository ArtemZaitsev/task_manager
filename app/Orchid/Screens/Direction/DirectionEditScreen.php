<?php

namespace App\Orchid\Screens\Direction;

use App\Models\Direction;
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

class DirectionEditScreen extends Screen
{
    public $direction;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Direction $direction): iterable
    {
        return [
            'direction' => $direction,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->direction->exist ? 'Редактирование направления' : 'Создание направления';
    }

    public function description(): ?string
    {
        return "Направления";
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
                ->canSee(!$this->direction->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->direction->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->direction->exists)
                ->disabled($this->direction->groups->count() > 0),
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
                Input::make('direction.title')
                    ->title('Название направления')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this post.'),


                Relation::make('direction.head_id')
                    ->title('Руководитель направления')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),

                Relation::make('direction.planers.')
                    ->title('Планеры направления')
                    ->fromModel(User::class, 'surname')
                    ->multiple()
                    ->displayAppend('label'),
            ])
        ];
    }
    public function createOrUpdate(Direction $entity, Request $request)
    {
        $request->validate([
            'direction.head_id' => [
                'required',
                'integer',
                Rule::exists(User::class, 'id')
            ],
            'direction.planers' => 'array',
            'direction.planers.*' => Rule::exists(User::class, 'id'),
            'direction.title' => [
                'required',
                Rule::unique(Direction::class, 'title')->ignore($entity),
            ]

        ]);

        DB::transaction(function () use ($request, $entity) {
            $entity->fill($request->get('direction'))->save();
            $entity->planers()->sync($request->input('direction.planers', []));
        });

        Alert::info('You have successfully created an post.');

        return redirect()->route('platform.direction.list');
    }

    public function remove(Direction $direction)
    {
        $direction->delete();

        Alert::info('You have successfully deleted the post.');
        return redirect()->route('platform.direction.list');
    }
}
