<?php

namespace App\Models\Component;

class ComponentPurchaserStatus
{
    public const NOT_REQUIRED = 1;
    public const DOES_NOT_DONE = 2;
    public const AGREE = 3;
    public const COMPONENT_CHOOSED = 4;
    public const IN_PROGRESS = 5;
    public const DONE = 6;

    public const LABELS = [
        self::NOT_REQUIRED => 'Не требуется',
        self::DOES_NOT_DONE => 'Не проработана',
        self::COMPONENT_CHOOSED => 'Компонент выбран',
        self::AGREE => 'Согласование компонента',
        self::IN_PROGRESS => 'В процессе',
        self::DONE => 'Готово',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
