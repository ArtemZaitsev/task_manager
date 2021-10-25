<?php

namespace App\Http\Controllers\Filters;

class DateFilter
{
    const MODE_TODAY = "today";
    const MODE_RANGE = "range";
    const ALL_MODES = [self::MODE_TODAY, self::MODE_RANGE];
}
