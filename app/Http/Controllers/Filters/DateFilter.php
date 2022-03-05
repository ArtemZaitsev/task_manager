<?php

namespace App\Http\Controllers\Filters;

use phpDocumentor\Reflection\Types\Boolean;

class DateFilter
{
    const MODE_TODAY = "today";
    const MODE_RANGE = "range";
    const ALL_MODES = [self::MODE_TODAY, self::MODE_RANGE];

    public static function filterEnabled($filterData): bool
    {
        switch ($filterData['mode']) {
            case self::MODE_TODAY:
                return true;
            case self::MODE_RANGE:
                return !empty($filterData['start']) || !empty($filterData['end']);
            default:
                return false;
        }
    }
}


