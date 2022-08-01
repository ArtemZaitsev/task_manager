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


    public function needDisplay(): bool {
        $fields = session()->get(ComponentDisplayFieldsController::COMPONENTS_FIELDS_SESSION_NAME, null);
        if($fields === null) {
            return true;
        }
        return in_array($this->name, $fields);
    }

}
