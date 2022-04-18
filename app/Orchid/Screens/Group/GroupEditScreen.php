<?php

namespace App\Orchid\Screens\Group;


use App\Models\Direction;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use function redirect;

class GroupEditScreen extends Screen
{
    public $group;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Group $group): iterable
    {
        return [
            'group' => $group,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->group->exist ? 'Редактирование группы' : 'Создание группы';
    }

    public function description(): ?string
    {
        return "Группы";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create post')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->group->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->group->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->group->exists)
                ->disabled($this->group->subgroups->count() > 0),
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
            Layout::rows([
                Input::make('group.title')
                    ->title('Название группы')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this post.'),

                Relation::make('group.direction_id')
                    ->title('Направление')
                    ->fromModel(Direction::class, 'title'),

                Relation::make('group.head_id')
                    ->title('Руководитель группы')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),
            ])
        ];
    }

    public function createOrUpdate(Group $group, Request $request)
    {
        $data = $request->validate([
            'group.title' => [
                'required',
                Rule::unique(Group::class, 'title')->ignore($group),
            ],
            'group.head_id' => [
                'nullable',
                Rule::exists(User::class, 'id')
            ],
            'group.direction_id' => [
                'required',
                Rule::exists(Direction::class, 'id')
            ]

        ]);

        if (!isset($data['group']['head_id'])) {
            $data['group']['head_id'] = null;
        }

        $group->fill($data['group'])->save();

        Alert::info('You have successfully created an post.');

        return redirect()->route('platform.group.list');
    }

    public function remove(Group $group)
    {
        $group->delete();

        Alert::info('You have successfully deleted the post.');
        return redirect()->route('platform.group.list');
    }
}
