<?php

namespace App\Models\Component;

class ComponentManufactorStartWay
{

    public const TEAMCENTER = 1;
    public const SZ = 2;
    public const MODEL = 3;
    public const NOT_REQUIRED = 4;

    public const LABELS = [
        self::TEAMCENTER => 'Тимцентр',
        self::SZ => 'СЗ + чертежи',
        self::MODEL => '3d модель',
        self::NOT_REQUIRED => 'Не требуется',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}

