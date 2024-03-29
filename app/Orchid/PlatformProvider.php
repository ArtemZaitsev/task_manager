<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Component\PhysicalObject;

use App\Orchid\Screens\Component\Metasystem\MetasystemListScreen;
use App\Orchid\Screens\Component\PhysicalObject\PhysicalObjectListScreen;
use App\Orchid\Screens\Component\Subsystem\SubsystemListScreen;
use App\Orchid\Screens\Component\System\SystemListScreen;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [

            Menu::make('Email sender')
                ->icon('envelope-letter')
                ->route('platform.email')
                ->title('Tools'),


            Menu::make('Направления')
                ->icon('envelope-letter')
                ->route('platform.direction.list')
                ->title('Структура команды'),

            Menu::make('Группы')
                ->icon('envelope-letter')
                ->route('platform.group.list'),

            Menu::make('Подгруппы')
                ->icon('envelope-letter')
                ->route('platform.subgroup.list'),

            Menu::make('Проекты')
                ->icon('envelope-letter')
                ->route('platform.project.list')
                ->title('Структура проекта'),
            Menu::make('Семейства продуктов')
                ->icon('envelope-letter')
                ->route('platform.family.list'),
            Menu::make('Продукты')
                ->icon('envelope-letter')
                ->route('platform.product.list'),


            Menu::make('Аудит')
                ->icon('envelope-letter')
                ->route('platform.audit.list')
                ->title('Аудит'),

            Menu::make('Объекты')
                ->icon('envelope-letter')
                ->route(PhysicalObjectListScreen::ROUTE_NAME)
                ->title('Состав'),

            Menu::make('Верхнеуровневые системы')
                ->icon('envelope-letter')
                ->route(MetasystemListScreen::ROUTE_NAME),
            Menu::make('Системы')
                ->icon('envelope-letter')
                ->route(SystemListScreen::ROUTE_NAME),
            Menu::make('Подсистемы')
                ->icon('envelope-letter')
                ->route(SubsystemListScreen::ROUTE_NAME),

//            Menu::make('Example screen')
//                ->icon('monitor')
//                ->route('platform.example')
//                ->title('Navigation')
//                ->badge(function () {
//                    return 6;
//                }),
//
//            Menu::make('Dropdown menu')
//                ->icon('code')
//                ->list([
//                    Menu::make('Sub element item 1')->icon('bag'),
//                    Menu::make('Sub element item 2')->icon('heart'),
//                ]),
//
//            Menu::make('Basic Elements')
//                ->title('Form controls')
//                ->icon('note')
//                ->route('platform.example.fields'),
//
//            Menu::make('Advanced Elements')
//                ->icon('briefcase')
//                ->route('platform.example.advanced'),
//
//            Menu::make('Text Editors')
//                ->icon('list')
//                ->route('platform.example.editors'),
//
//            Menu::make('Overview layouts')
//                ->title('Layouts')
//                ->icon('layers')
//                ->route('platform.example.layouts'),
//
//            Menu::make('Chart tools')
//                ->icon('bar-chart')
//                ->route('platform.example.charts'),
//
//            Menu::make('Cards')
//                ->icon('grid')
//                ->route('platform.example.cards')
//                ->divider(),
//

            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access rights')),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.tasks', 'Задачи')
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
