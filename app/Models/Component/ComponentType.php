<?php

namespace App\Models\Component;

class ComponentType
{
    public const ASSEMBLY_UNIT = 1;
    public const DETAIL = 2;
    public const PURCHASED_ITEM = 3;
    public const STANDART_PRODUCT = 4;
    public const OTHER_PRODUCT = 5;

    public const LABELS = [
        self::ASSEMBLY_UNIT => 'Сборочная единица',
        self::DETAIL => 'Деталь',
        self::PURCHASED_ITEM => 'Покупное изделие',
        self::STANDART_PRODUCT => 'Стандартное изделие',
        self::OTHER_PRODUCT => 'Прочие изделия',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
