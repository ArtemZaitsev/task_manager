<?php

namespace App\Utils;

class DateUtils
{
    public static function dateToHtmlInput(?string $date){
        if ($date === null) {
            return '';
        }
      return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public static function dateToDisplayFormat(?string $date){
        if ($date === null) {
            return '';
        }

        return \Carbon\Carbon::parse($date)->format('d.m.Y');
    }
}
