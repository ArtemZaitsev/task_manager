<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User\Layout;

use App\Models\Direction;
use App\Models\Group;
use App\Models\Subgroup;
use App\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;
use function __;

class UserEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Имя')
                ->placeholder('Имя'),
            Input::make('user.surname')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Фамилия')
                ->placeholder('Фамилия'),
            Input::make('user.patronymic')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Отчество')
                ->placeholder('Отчество'),
            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),
            CheckBox::make('user.enable')
                ->sendTrueOrFalse()
                ->title(__('Включен')),
            Relation::make('user.direction_id')
                ->title('Направление')
                ->fromModel(Direction::class, 'title')
                ->displayAppend('label'),
            Relation::make('user.group_id')
                ->title('Группа')
                ->fromModel(Group::class, 'title')
                ->displayAppend('fullName'),
            Relation::make('user.subgroup_id')
                ->title('Подгруппа')
                ->fromModel(Subgroup::class, 'title')
                ->displayAppend('fullName'),

        ];
    }
}
