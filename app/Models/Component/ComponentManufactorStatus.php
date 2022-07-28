<?php

namespace App\Models\Component;

class ComponentManufactorStatus
{
    public const NOT_REQUIRED = 1;
    public const DOES_NOT_HAVE = 2;
    public const IN_PROGRESS = 3;
    public const DONE = 4;

    public const LABELS = [
        self::NOT_REQUIRED => 'не требуется',
        self::DOES_NOT_HAVE => 'нет',
        self::IN_PROGRESS => 'в процессе',
        self::DONE => 'готово',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
