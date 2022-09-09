<?php

namespace App\Orchid\Screens\Component\Subsystem;

use App\Models\Component\Subsystem;
use App\Models\Component\System;
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
        return $this->entity->id !== null ? 'Редактирование' : 'Создание';
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
            Button::make('Создать')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->entity->exists),

            Button::make('Изменить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->entity->exists),

            Button::make('Удалить')
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
                Input::make('entity.title')
                    ->title('Название'),
                Relation::make('entity.system_id')
                    ->title('Системы')
                    ->fromModel(System::class, 'title'),
            ]),

        ];
    }


    public function createOrUpdate(Subsystem $entity, Request $request)
    {

        $data = $request->validate([
            'entity.title' => [
                'required',
                Rule::unique(Subsystem::class, 'title')
                    ->ignore($entity),
            ],
            'entity.system_id' => [
                'nullable',
                Rule::exists(System::class, 'id')
            ],
        ]);

        $entity->fill($data['entity'])->save();


        Alert::info('Данные успешно добавлены.');

        return redirect()->route(SubsystemListScreen::ROUTE_NAME);
    }

    public function remove(System $entity)
    {
        $entity->delete();

        Alert::info('Данные успешно удалены.');
        return redirect()->route(SubsystemListScreen::ROUTE_NAME);
    }
}
