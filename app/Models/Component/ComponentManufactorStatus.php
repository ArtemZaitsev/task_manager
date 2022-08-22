<?php

namespace App\Models\Component;

class ComponentManufactorStatus
{
    public const NOT_REQUIRED = 1;
    public const DD_NOT_TRANSMITTED = 2;
    public const KTU = 4;
    public const IN_PROGRESS = 5;
    public const DONE = 6;
    public const HAVE_NOT_SZ = 7;

    public const LABELS = [
        self::NOT_REQUIRED => 'Не требуется',
        self::HAVE_NOT_SZ => 'СЗ не запущена',
        self::DD_NOT_TRANSMITTED => 'КД не передано',
        self::KTU => 'Разработка КТУ',
        self::IN_PROGRESS => 'В процессе',
        self::DONE => 'Готово',

    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
