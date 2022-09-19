<?php

namespace App\Orchid\Screens\Component\Metasystem;

use App\Models\Component\Metasystem;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class MetasystemListScreen extends Screen
{
    public const ROUTE_NAME = "platform.metasystem.list";

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'entity' => Metasystem::query()
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
        return 'Верхнеуровневые системы';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новую верхнеуровневую систему')
                ->icon('pencil')
                ->route(MetasystemEditScreen::ROUTE_NAME)
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
            MetasystemListLayout::class
        ];
    }
}
