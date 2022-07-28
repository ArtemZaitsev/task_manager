<?php

namespace App\Models\Component;

class ComponentCalcStatus
{
    public const NOT_REQUIRED = 1;
    public const PLANNED = 2;
    public const WRITING_TZ = 3;
    public const IN_PROGRESS = 4;
    public const DONE = 5;

    public const LABELS = [
        self::NOT_REQUIRED => 'не требуется',
        self::PLANNED => 'запланирован',
        self::WRITING_TZ => 'написание ТЗ',
        self::IN_PROGRESS => 'в процессе',
        self::DONE => 'готово',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }
}
