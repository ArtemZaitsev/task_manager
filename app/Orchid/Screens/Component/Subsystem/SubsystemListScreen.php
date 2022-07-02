<?php

namespace App\Orchid\Screens\Component\Subsystem;

use App\Models\Component\Subsystem;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class SubsystemListScreen extends Screen
{
    public const ROUTE_NAME = "platform.subsystem.list";

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'subsystems' => Subsystem::query()
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
        return 'Подсистемы';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новую подсистему')
                ->icon('pencil')
                ->route(SubsystemEditScreen::ROUTE_NAME)
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
            SubsystemListLayout::class
        ];
    }
}

