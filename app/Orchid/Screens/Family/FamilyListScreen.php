<?php

namespace App\Orchid\Screens\Family;

use App\Models\Family;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class FamilyListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'families' => Family::query()
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
        return 'Список семейств продуктов';
    }

    public function description(): ?string
    {
        return "Все семейства продуктов";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новое семейство продуктов')
                ->icon('pencil')
                ->route('platform.family.edit')
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
            FamilyListLayout::class
        ];
    }
}
