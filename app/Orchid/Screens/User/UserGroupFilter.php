<?php

namespace App\Orchid\Screens\User;

use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use function React\Promise\map;

class UserGroupFilter extends Filter
{
    /**
     * @return string
     */
    public function name(): string
    {
        return 'Группы';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['groups'];
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereIn('group_id', $this->request->get('groups'));
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        $groups = $this->request->get('groups') ?? [];
        $groups = array_map(fn($group) => (int)$group, $groups);

        return [
            Select::make('groups')
                ->fromModel(Group::class, 'title')
                ->empty()
                ->value($groups)
                ->title('Группы')
                ->multiple(),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
//        return $this->name() . ': ' . Role::where('slug', $this->request->get('role'))->first()->name;

        $groups = $this->request->get('groups');

        $dbGroups = Group::query()->whereIn('id', $groups)->get()->toArray();
        $dbGroups = array_map(fn(array $dbGroup) => $dbGroup['title'], $dbGroups);
        $groupStr = implode(", ", $dbGroups);
        return $this->name() . ': ' . $groupStr;
    }
}
