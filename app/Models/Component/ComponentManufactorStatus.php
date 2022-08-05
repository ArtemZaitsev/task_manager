<?php

namespace App\Models\Component;

class ComponentManufactorStatus
{
    public const NOT_REQUIRED = 1;
    public const DOES_NOT_HAVE = 2;
    public const NOT_DD = 3;
    public const KTU = 4;
    public const IN_PROGRESS = 5;
    public const DONE = 6;


    public const LABELS = [
        self::NOT_REQUIRED => 'Не требуется',
        self::DOES_NOT_HAVE => 'Нет',
        self::NOT_DD => 'КД не передано',
        self::KTU => 'Разработка КТУ',
        self::IN_PROGRESS => 'В процессе',
        self::DONE => 'Готово',

    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
