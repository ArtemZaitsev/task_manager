<?php

namespace App\Orchid\Screens\Group;

use App\Models\Group;
use App\Orchid\Screens\Group\GroupListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class GroupListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'groups'=>Group::paginate(20)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Список групп';
    }
    public function description(): ?string
    {
        return "Все группы";
    }
    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новую группу')
                ->icon('pencil')
                ->route('platform.group.edit')
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
            GroupListLayout::class
        ];
    }
}
