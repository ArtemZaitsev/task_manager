<?php

namespace App\Orchid\Screens\Component\System;

use App\Models\Component\System;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;

use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class SystemEditScreen extends Screen
{
    public const ROUTE_NAME = "platform.system.edit";

    public $entity;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(System $entity): iterable
    {
        return [
            'entity' => $entity,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->entity->id !== null ? 'Редактирование системы' : 'Создание системы';
    }

    public function description(): ?string
    {
        return "Системы";
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
                ->canSee(!$this->entity->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->entity->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->entity->exists),
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
                Input::make('entity.name')
                    ->title('Название системы')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this post.'),

                Input::make('entity.number')
                    ->title('Идентификатор')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this post.'),

                Relation::make('entity.head_id')
                    ->title('Руководитель системы')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),
            ])
        ];
    }


    public function createOrUpdate(System $entity, Request $request)
    {

        $data = $request->validate([
            'entity.name' => [
                'required',
                Rule::unique(System::class, 'name')
                    ->ignore($entity),
            ],
            'entity.head_id' => [
                'nullable',
                Rule::exists(User::class, 'id')
            ],
            'entity.number' => [
                'nullable',
                Rule::unique(System::class, 'number')
                    ->ignore($entity),
            ],
        ]);

        if (!isset($data['entity']['head_id'])) {
            $data['entity']['head_id'] = null;
        }

        $entity->fill($data['entity'])->save();

        Alert::info('You have successfully created an post.');

        return redirect()->route(SystemListScreen::ROUTE_NAME);
    }

    public function remove(System $entity)
    {
        $entity->delete();

        Alert::info('You have successfully deleted the post.');
        return redirect()->route('platform.system.list');
    }
}
