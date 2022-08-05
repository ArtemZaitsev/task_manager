<?php

namespace App\Models\Component;

class ComponentPurchaserStatus
{
    public const NOT_REQUIRED = 1;
    public const DOES_NOT_DONE = 2;
    public const IN_PROGRESS = 3;
    public const DONE = 4;
    public const COMPONENT_CHOOSED = 5;
    public const AGREE = 6;

    public const LABELS = [


        self::IN_PROGRESS => 'В процессе',
        self::DONE => 'Готово',
        self::DOES_NOT_DONE => 'Не проработана',
        self::NOT_REQUIRED => 'Не требуется',
        self::COMPONENT_CHOOSED => 'Компонент выбран',
        self::AGREE => 'Согласование',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
