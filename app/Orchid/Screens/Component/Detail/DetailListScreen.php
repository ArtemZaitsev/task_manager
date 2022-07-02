<?php

namespace App\Orchid\Screens\Component\Detail;

use App\Models\Component\Detail;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class DetailListScreen  extends Screen
{
    public const ROUTE_NAME = "platform.detail.list";

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'details' => Detail::query()
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
        return 'Компоненты';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новый компонент')
                ->icon('pencil')
                ->route(DetailEditScreen::ROUTE_NAME)
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
            DetailListLayout::class
        ];
    }
}
