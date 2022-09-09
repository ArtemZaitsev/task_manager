<?php

namespace App\Lib\Grid\Field;

class Field
{
    public function __construct(
        private string $name,
        private string $label,
        private bool $needDisplay
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isNeedDisplay(): bool
    {
        return $this->needDisplay;
    }

    public function setNeedDisplay(bool $needDisplay): void
    {
        $this->needDisplay = $needDisplay;
    }



}
