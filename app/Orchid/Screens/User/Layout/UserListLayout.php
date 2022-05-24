<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User\Layout;


use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use function __;

class UserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'users';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('direction', 'Направление')
                ->sort()
                ->filter(Input::make())
                ->render(function (User $user) {
                    return $user->direction?->title;
                }),
            TD::make('group', 'Группа')
                ->sort()
                ->filter(Input::make())
                ->render(function (User $user) {
                    return $user->group?->title;
                }),
            TD::make('subgroup', 'Подгруппа')
                ->sort()
                ->filter(Input::make())
                ->render(function (User $user) {
                    return $user->subgroup?->title;
                }),

            TD::make('name', __('Name'))
                ->sort()
                ->filter(Input::make())
                ->cantHide()
                ->filter(Input::make())
                ->render(function (User $user) {
                    return new Persona($user->presenter());
                }),

            TD::make('email', __('Email'))
                ->sort()
                ->filter(Input::make())
                ->render(function (User $user) {
                    return ModalToggle::make($user->email)
                        ->modal('asyncEditUserModal')
                        ->modalTitle($user->presenter()->title())
                        ->method('saveUser')
                        ->asyncParameters([
                            'user' => $user->id,
                        ]);
                }),

            TD::make('enable', __('Enable'))
                ->sort()
                ->render(function (User $user) {
                    if ($user->enable === 1) {
                        return '<span class="text-success">Да</span>';
                    }
                    return '<span class="text-danger">Нет</span>';
                }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (User $user) {
                    return $user->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (User $user) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list(array_filter([

                            $user->enable ?
                                Link::make('Войти под пользователем')
                                    ->route('impersonate', $user->id)
                                    ->icon('pencil')
                                : null,

                            Link::make(__('Edit'))
                                ->route('platform.systems.users.edit', $user->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                                ->method('remove', [
                                    'id' => $user->id,
                                ]),
                        ]));
                }),
        ];
    }
}
