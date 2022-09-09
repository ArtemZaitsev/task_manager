<?php

namespace App\Orchid\Screens\Component\System;

use App\Models\Component\Detail;
use App\Models\Component\PhysicalObject;
use App\Models\Component\System;
use App\Orchid\Screens\Component\Detail\DetailEditScreen;
use App\Orchid\Screens\Component\Detail\DetailListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class SystemListScreen extends Screen
{
    public const ROUTE_NAME = "platform.system.list";

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'entity' => System::query()
                ->filters()
                ->paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Системы';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новую систему')
                ->icon('pencil')
                ->route(SystemEditScreen::ROUTE_NAME)
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
            SystemListLayout::class
        ];
    }
}
