<?php

namespace App\Http\Controllers\Component\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface Filter
{
    public function name(): string;

    public function template(): string;

    public function templateData(Request $request): array;

    public function apply(Builder $query, mixed $data): void;
}
