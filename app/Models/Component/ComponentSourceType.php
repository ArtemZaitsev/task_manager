<?php

namespace App\Models\Component;

class ComponentSourceType
{
    public const MAKE = 1;
    public const BUY = 2;
    public const NOT_SPECIFIED = 3;

    public const LABELS = [
        self::MAKE => 'Изготовление',
        self::BUY => 'Покупка',
        self::NOT_SPECIFIED => 'Не указано',
        ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
