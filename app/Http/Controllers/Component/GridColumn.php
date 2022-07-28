<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Component\Filter\Filter;

class GridColumn
{
    public function __construct(
        private string  $name,
        private string  $label,
        private         $renderer,
        private ?string $orderField = null,
        private ?Filter $filter = null
    )
    {
    }

    public function render($data)
    {
        $renderer = $this->renderer;
        return $renderer($data);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getOrderField(): ?string
    {
        return $this->orderField;
    }

    public function getFilter(): ?Filter
    {
        return $this->filter;
    }



}
