<?php

namespace App\Models\Component;

final class ComponentStatus
{
    public const STATUS_NOT_STARTED = 1;
    public const STATUS_IN_PROGRESS = 2;
    public const STATUS_DONE = 3;

    public const LABELS = [
        self::STATUS_NOT_STARTED => 'Не начат',
        self::STATUS_IN_PROGRESS => 'В процессе',
        self::STATUS_DONE => 'Готово',
    ];
}
