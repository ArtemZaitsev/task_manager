<?php

namespace App\Models\Component;

class Component3dStatus
{
    public const NOT_REQUIRED = 1;
    public const DOES_NOT_HAVE = 2;
    public const IN_PROGRESS = 3;
    public const DONE = 4;
    public const NOT_SPECIFIED = 5;

    public const LABELS = [
        self::NOT_REQUIRED => 'Не требуется',
        self::DOES_NOT_HAVE => 'Нет',
        self::IN_PROGRESS => 'В процессе',
        self::DONE => 'Готово',
        self::NOT_SPECIFIED => 'Не указано',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
