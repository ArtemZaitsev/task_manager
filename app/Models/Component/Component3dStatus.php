<?php

namespace App\Models\Component;

class Component3dStatus
{
    public const NOT_REQUIRED = 1;
    public const DOES_NOT_DONE = 2;
    public const IN_PROGRESS = 3;
    public const DONE = 4;

    public const LABELS = [
        self::NOT_REQUIRED => 'Не требуется',
        self::DOES_NOT_DONE => 'Нет 3D',
        self::IN_PROGRESS => 'В процессе',
        self::DONE => 'Готово',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
