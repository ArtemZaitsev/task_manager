<?php

namespace App\Orchid\Screens\Direction;

use App\Models\Direction;
use App\Orchid\Screens\Direction\DirectionListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class DirectionListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'directions' => Direction::query()
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
        return 'Список направлений';
    }

    public function description(): ?string
    {
        return "Все направления";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новое направление')
                ->icon('pencil')
                ->route('platform.direction.edit')
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
            DirectionListLayout::class
        ];
    }
}
