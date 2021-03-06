<?php

namespace App\Models\Component;

class ComponentVersion
{
    public const NEW = 1;
    public const COM = 2;
    public const CO = 3;

    public const LABELS = [
        self::NEW => 'Новый',
        self::COM => 'Доработанный',
        self::CO => 'Заимствованный',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
