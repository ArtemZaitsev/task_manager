<?php

namespace App\Orchid\Screens\Product;

use App\Models\Family;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ProductEditScreen extends Screen
{
    public $product;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Product $product): iterable
    {
        return [
            'product' => $product,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->product->exist ? 'Редактирование продукта' : 'Создание продукта';

    }

    public function description(): ?string
    {
        return "Продукты";
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
                ->canSee(!$this->product->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->product->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->product->exists),
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
                Input::make('product.title')
                    ->title('Название продукта')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this post.'),

                Relation::make('product.family_id')
                    ->title('Семейство')
                    ->fromModel(Family::class, 'title'),

                Relation::make('product.heads.')
                    ->title('Руководители продукта')
                    ->fromModel(User::class, 'surname')
                    ->multiple()
                    ->displayAppend('label'),

                Relation::make('product.planer_id')
                    ->title('Планер продукта')
                    ->fromModel(User::class, 'surname')
                    ->displayAppend('label'),
            ])
        ];
    }

    public function createOrUpdate(Product $product, Request $request)
    {

        $request->validate([
            'product.heads' => 'array',
            'product.heads.*' => Rule::exists(User::class, 'id'),
            'product.planer_id' => [
                'nullable',
                'integer',
                Rule::exists(User::class, 'id'),
            ],
            'product.title' => [
                'required',
                Rule::unique(Product::class, 'title')->ignore($product),
            ]

        ]);

        DB::transaction(function () use ($request, $product) {
            $product->fill($request->get('product'))->save();
            $product->heads()->sync($request->get('product')['heads'] ?? []);
        });

        Alert::info('Вы успешно поработали над продуктом.');

        return redirect()->route('platform.product.list');
    }

    public function remove(Product $product)
    {
        $product->delete();

        Alert::info('You have successfully deleted the post.');
        return redirect()->route('platform.product.list');
    }
}
