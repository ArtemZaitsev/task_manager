<?php

namespace App\Orchid\Screens\Subgroup;

use App\Models\Subgroup;
use App\Orchid\Screens\Subgroup\SubgroupListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class SubgroupListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'subgroups' => Subgroup::query()
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
        return 'Список подгрупп';
    }

    public function description(): ?string
    {
        return "Все подгруппы";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новую подгруппу')
                ->icon('pencil')
                ->route('platform.subgroup.edit')
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
            SubgroupListLayout::class
        ];
    }
}
