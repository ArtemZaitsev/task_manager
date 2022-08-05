<?php

namespace App\Models\Component;

class ComponentSourceType
{
    public const MAKE = 1;
    public const BUY = 2;

    public const LABELS = [
        self::MAKE => 'Изготовление',
        self::BUY => 'Покупка',
        ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
