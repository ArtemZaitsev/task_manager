<?php

namespace App\Utils;

class ColumnUtils
{
    public static function isColumnEnabled($columnName)
    {
        if (!session()->has('task_columns')) {
            return true;
        }
        $columns = session()->get('task_columns');

        if (!isset($columns[$columnName])) {
            return true;
        }

        if (session()->get('task_columns')[$columnName]) {
            return true;
        } else {
            return false;
        }
    }
}
