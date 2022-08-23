<?php

namespace App\Models\Component;

class ComponentPurchaserStatus
{
    public const NOT_REQUIRED = 1;
    public const DOES_NOT_DONE = 2;
    public const DECOR_PURCHASE_REQUEST = 3;
    public const AGREEMENT = 4;
    public const IN_PROGRESS = 5;
    public const DONE = 6;

    public const LABELS = [
        self::NOT_REQUIRED => 'Не требуется',
        self::DOES_NOT_DONE => 'Заявка не оформлена',
        self::DECOR_PURCHASE_REQUEST => 'Оформление заявки',
        self::AGREEMENT => 'Согласование заявки',
        self::IN_PROGRESS => 'В процессе',
        self::DONE => 'Готово',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
