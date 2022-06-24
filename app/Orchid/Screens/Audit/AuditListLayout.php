<?php

namespace App\Orchid\Screens\Audit;

use App\Models\Audit;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AuditListLayout extends Table
{
    protected $target = 'audit';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'id')
                ->filter(Input::make())
                ->render(function (Audit $entity) {
                    return ($entity->id);
                }),
            TD::make('created_at', 'Создан в')
                ->filter(Input::make())
                ->render(function (Audit $entity) {
                    return \Carbon\Carbon::parse($entity->created_at)->format('d.m.Y H:mm:ss');
                }),

            TD::make('user_id', 'Пользователь')
                ->filter(Input::make())
                ->render(function (Audit $entity) {
                    return ($entity->user->label());
                }),
            TD::make('event_type', 'Тип события')
                ->filter(Input::make())
                ->render(function (Audit $entity) {
                    return Audit::ALL_TYPES[$entity->event_type];
                }),
            TD::make('table_name', 'Название таблицы')
                ->filter(Input::make())
                ->render(function (Audit $entity) {
                    return ($entity->table_name);
                }),
            TD::make('entity_id', 'id записи')
                ->filter(Input::make())
                ->render(function (Audit $entity) {
                    return ($entity->entity_id);
                }),
            TD::make('meta_inf', 'Сведения')
                ->filter(Input::make())
                ->render(function (Audit $entity) {
                    return json_encode($entity->meta_inf);
                }),

        ];
    }
}
