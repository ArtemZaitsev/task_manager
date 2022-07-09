<?php

namespace App\Orchid\Screens\Component\PhysicalObject;

use App\Models\Component\Detail;
use App\Models\Component\PhysicalObject;
use App\Orchid\Screens\Component\Detail\DetailEditScreen;
use App\Orchid\Screens\Component\Detail\DetailListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class PhysicalObjectListScreen extends Screen
{
    public const ROUTE_NAME = "platform.physical_object.list";

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'entity' => PhysicalObject::query()
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
        return 'Объекты';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новый объект')
                ->icon('pencil')
                ->route(PhysicalObjectEditScreen::ROUTE_NAME)
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
            PhysicalObjectListLayout::class
        ];
    }
}
