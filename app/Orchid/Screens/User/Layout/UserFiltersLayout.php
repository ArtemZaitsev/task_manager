<?php

namespace App\Orchid\Screens\User\Layout;

use App\Orchid\Filters\RoleFilter;
use App\Orchid\Screens\User\UserGroupFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class UserFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            RoleFilter::class,
            UserGroupFilter::class,
        ];
    }
}
