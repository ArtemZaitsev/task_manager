<?php

namespace App\Orchid\Screens\Component\Detail;

use App\Models\Component\Detail;
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

class DetailEditScreen extends Screen
{
    public const ROUTE_NAME = "platform.detail.edit";

    public $entity;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Detail $entity): iterable
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
        return $this->entity->id !== null ? 'Редактирование компонента' : 'Создание компонента';
    }

    public function description(): ?string
    {
        return "Компоненты";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Создать компонент')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->entity->exists),

            Button::make('Изменить компонент')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->entity->exists),

            Button::make('Удалить компонент')
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
                    ->title('Название компонента'),

                Input::make('entity.number')
                    ->title('Идентификатор'),


                Relation::make('entity.head_id')
                    ->title('Ответственный за компонент')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),

                Relation::make('entity.subsystem_id')
                    ->title('Подсистема')
                    ->fromModel(Subsystem::class, 'name')
                    ->displayAppend('name'),

                Relation::make('entity.system_id')
                    ->title('Система')
                    ->fromModel(System::class, 'name')
                    ->displayAppend('name'),
            ])
        ];
    }


    public function createOrUpdate(Detail $entity, Request $request)
    {

        $data = $request->validate([
            'entity.name' => [
                'required',
                Rule::unique(Detail::class, 'name')
                    ->ignore($entity),
            ],
            'entity.head_id' => [
                'nullable',
                Rule::exists(User::class, 'id')
            ],
            'entity.subsystem_id' => [
                'nullable',
                Rule::exists(Subsystem::class, 'id')
            ],
            'entity.system_id' => [
                'nullable',
                Rule::exists(System::class, 'id'),
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('entity.system_id') !== null &&
                        $request->input('entity.subsystem_id') !== null) {
                        $fail('Нельзя задать одновременно систему и подсистему');
                    }
                }
            ],
            'entity.number' => [
                'nullable',
                Rule::unique(Detail::class, 'number')
                    ->ignore($entity),
            ],
        ]);

        if (!isset($data['entity']['head_id'])) {
            $data['entity']['head_id'] = null;
        }
        if (!isset($data['entity']['system_id'])) {
            $data['entity']['system_id'] = null;
        }
        if (!isset($data['entity']['subsystem_id'])) {
            $data['entity']['subsystem_id'] = null;
        }

        $entity->fill($data['entity'])->save();

        Alert::info('Компонент успешно создан.');

        return redirect()->route(DetailListScreen::ROUTE_NAME);
    }

    public function remove(Detail $entity)
    {
        $entity->delete();

        Alert::info('Компонент успешно удален.');
        return redirect()->route();
    }
}
