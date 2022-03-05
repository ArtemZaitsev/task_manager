<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('project_id', 'Название проекта')
                ->render(function (Product $product) {
                    return $product?->family?->project?->title;
                }),

            TD::make('family_id', 'Название семейства')
                ->render(function (Product $product) {
                    return $product?->family?->title;
                }),

            TD::make('title', 'Название продукта')
                ->render(function (Product $product) {
                    return Link::make($product->title)
                        ->route('platform.product.edit', $product);
                }),
            TD::make('head_id', 'Руководитель продукта')
                ->render(function (Product $product) {
                    return $product?->head?->label;
                }),
            TD::make('planer_id', 'Планер продукта')
                ->render(function (Product $product) {
                    return $product?->planer?->label;
                }),



            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
