<?php

namespace App\Orchid\Screens\Component\Subsystem;


use App\Models\Component\Subsystem;
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

class SubsystemEditScreen extends Screen
{
    public const ROUTE_NAME = "platform.subsystem.edit";

    public $entity;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Subsystem $entity): iterable
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
        return $this->entity->id !== null ? 'Редактирование подсистемы' : 'Создание подсистемы';
    }

    public function description(): ?string
    {
        return "Подсистемы";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Создать подсистему')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->entity->exists),

            Button::make('Изменить подсистему')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->entity->exists),

            Button::make('Удалить подсистему')
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
                    ->title('Название подсистемы'),

                Input::make('entity.number')
                    ->title('Идентификатор'),

                Relation::make('entity.head_id')
                    ->title('Руководитель подсистемы')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),

                Relation::make('entity.system_id')
                    ->title('Система')
                    ->fromModel(System::class, 'name')
                    ->displayAppend('name'),
            ])
        ];
    }


    public function createOrUpdate(Subsystem $entity, Request $request)
    {

        $data = $request->validate([
            'entity.name' => [
                'required',
                Rule::unique(Subsystem::class, 'name')
                    ->ignore($entity),
            ],
            'entity.head_id' => [
                'nullable',
                Rule::exists(User::class, 'id')
            ],
            'entity.system_id' => [
                'required',
                Rule::exists(System::class, 'id')
            ],
            'entity.number' => [
                'nullable',
                Rule::unique(Subsystem::class, 'number')
                    ->ignore($entity),
            ],
        ]);

        if (!isset($data['entity']['head_id'])) {
            $data['entity']['head_id'] = null;
        }
        if (!isset($data['entity']['system_id'])) {
            $data['entity']['system_id'] = null;
        }

        $entity->fill($data['entity'])->save();

        Alert::info('Подсистема успешно создана.');

        return redirect()->route(SubsystemListScreen::ROUTE_NAME);
    }

    public function remove(Subsystem $entity)
    {
        $entity->delete();

        Alert::info('Подсистема успешно удалена.');
        return redirect()->route(SubsystemListScreen::ROUTE_NAME);
    }
}
