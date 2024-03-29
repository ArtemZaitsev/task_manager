<?php

namespace App\Orchid\Screens\Component\Metasystem;

use App\Models\Component\Metasystem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class MetasystemEditScreen  extends Screen
{
    public const ROUTE_NAME = "platform.metasystem.edit";

    public $entity;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Metasystem $entity): iterable
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
        return "Верхнеуровневая система";
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
            ])
        ];
    }


    public function createOrUpdate(Metasystem $entity, Request $request)
    {

        $data = $request->validate([
            'entity.title' => [
                'required',
                Rule::unique(Metasystem::class, 'title')
                    ->ignore($entity),
            ],

        ]);

        $entity->fill($data['entity'])->save();


        Alert::info('Данные успешно добавлены.');

        return redirect()->route(MetasystemListScreen::ROUTE_NAME);
    }

    public function remove(Metasystem $entity)
    {
        $entity->delete();

        Alert::info('Данные успешно удалены.');
        return redirect()->route(MetasystemListScreen::ROUTE_NAME);
    }
}
