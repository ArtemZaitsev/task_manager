<?php

namespace App\Lib\Grid;

use App\Http\Controllers\Component\ComponentDisplayFieldsController;
use App\Http\Controllers\Component\Filter\Filter;
use function session;

class GridColumn
{
    public function __construct(
        private string  $name,
        private string  $label,
        private         $renderer,
        private ?string $orderField = null,
        private ?Filter $filter = null,
        private bool $displayDefault = true,
        private bool $needExport = true

    )
    {
    }

    public function render($data)
    {
        $renderer = $this->renderer;
        return $renderer($data);
    }

    public function renderExcel($data){
        return strip_tags($this->render($data));
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

    public function isDisplayDefault(): bool
    {
        return $this->displayDefault;
    }

    public function isNeedExport(): bool
    {
        return $this->needExport;
    }



}