<?php

namespace App\Models\Component;

class ComponentType
{
    public const ASSEMBLY_UNIT = 1;
    public const DETAIL = 2;

    public const LABELS = [
        self::ASSEMBLY_UNIT => 'Сборочная единица',
        self::DETAIL => 'Деталь',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
