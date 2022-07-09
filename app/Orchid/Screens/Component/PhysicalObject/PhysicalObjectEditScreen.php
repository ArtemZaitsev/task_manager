<?php

namespace App\Orchid\Screens\Component\PhysicalObject;

use App\Models\Component\Detail;
use App\Models\Component\PhysicalObject;
use App\Models\Component\Subsystem;
use App\Models\Component\System;
use App\Models\User;
use App\Orchid\Screens\Component\Detail\DetailListScreen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class PhysicalObjectEditScreen  extends Screen
{
    public const ROUTE_NAME = "platform.physical_object.edit";

    public $entity;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(PhysicalObject $entity): iterable
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
        return $this->entity->id !== null ? 'Редактирование объекта' : 'Создание объекта';
    }

    public function description(): ?string
    {
        return "Объекты";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Создать объект')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->entity->exists),

            Button::make('Изменить объект')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->entity->exists),

            Button::make('Удалить объект')
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
                    ->title('Название объект'),

                Select::make('entity.target')
                    ->options(PhysicalObject::ALL_TARGETS)
                    ->title('Назначение'),
            ])
        ];
    }


    public function createOrUpdate(PhysicalObject $entity, Request $request)
    {

        $data = $request->validate([
            'entity.name' => [
                'required',
                Rule::unique(PhysicalObject::class, 'name')
                    ->ignore($entity),
            ],
            'entity.target' => [
                'nullable',
                Rule::in(array_keys(PhysicalObject::ALL_TARGETS)),
            ],
        ]);

        if (!isset($data['entity']['target'])) {
            $data['entity']['target'] = null;
        }

        $entity->fill($data['entity'])->save();


        Alert::info('Объект успешно создан.');

        return redirect()->route(PhysicalObjectListScreen::ROUTE_NAME);
    }

    public function remove(PhysicalObject $entity)
    {
        $entity->delete();

        Alert::info('Объект успешно удален.');
        return redirect()->route(PhysicalObjectListScreen::ROUTE_NAME);
    }
}
