<?php

namespace App\Orchid\Screens\Subgroup;

use App\Models\Group;
use App\Models\Subgroup;

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

class SubgroupEditScreen extends Screen
{

    public $subgroup;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Subgroup $subgroup): iterable
    {
        return [
            'subgroup' => $subgroup,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->subgroup->exist ? 'Редактирование подгруппы' : 'Создание подгруппы';
    }

    public function description(): ?string
    {
        return "Подгруппы";
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
                ->canSee(!$this->subgroup->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->subgroup->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->subgroup->exists),
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
                Input::make('subgroup.title')
                    ->title('Название подгруппы')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this post.'),

                Relation::make('subgroup.group_id')
                    ->title('Группа')
                    ->fromModel(Group::class, 'title'),

                Relation::make('subgroup.head_id')
                    ->title('Руководитель подгруппы')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),
            ])
        ];
    }


    public function createOrUpdate(Subgroup $subgroup, Request $request)
    {

        $data = $request->validate([
            'subgroup.title' => [
                'required',
                Rule::unique(Subgroup::class, 'title')->ignore($subgroup),
            ],
            'subgroup.head_id' => [
                'nullable',
                Rule::exists(User::class, 'id')
            ],
            'subgroup.group_id' => [
                'required',
                Rule::exists(Group::class, 'id')
            ]

        ]);

        if (!isset($data['subgroup']['head_id'])) {
            $data['subgroup']['head_id'] = null;
        }

        $subgroup->fill($data['subgroup'])->save();

        Alert::info('You have successfully created an post.');

        return redirect()->route('platform.subgroup.list');
    }

    public function remove(Subgroup $subgroup)
    {
        $subgroup->delete();

        Alert::info('You have successfully deleted the post.');
        return redirect()->route('platform.subgroup.list');
    }
}
