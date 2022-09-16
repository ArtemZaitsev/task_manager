<?php

namespace App\Models\Component;

class ComponentCalcStatus
{
    public const NOT_REQUIRED = 1;
    public const DOES_NOT_DONE = 2;
    public const WRITING_TZ = 3;
    public const IN_PROGRESS = 4;
    public const DONE = 5;

    public const LABELS = [
        self::NOT_REQUIRED => 'Не требуется',
        self::DOES_NOT_DONE => 'Нет расчетов',
        self::WRITING_TZ => 'Написание ТЗ',
        self::IN_PROGRESS => 'В процессе',
        self::DONE => 'Готово',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
