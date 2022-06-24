<?php

namespace App\Orchid\Screens\Audit;

use App\Models\Audit;
use Orchid\Screen\Screen;

class AuditListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'audit'=> Audit::query()
            ->filters()
            ->paginate(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'История изменений';
    }

    public function description(): ?string
    {
        return "Все изменения";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

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
            AuditListLayout::class,
        ];
    }
}
