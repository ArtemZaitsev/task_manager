<?php

namespace App\Utils;

use Illuminate\Http\Request;

class UrlUtils
{


    public static function clearFilterUrl(string $routeName, string $filterName, Request $request)
    {
        $getParams = $request->query->all();
        if (isset($getParams[$filterName])) {
            unset($getParams[$filterName]);
        }
        $res = http_build_query($getParams);
        return route($routeName) . "?" . $res;
    }

    public static function sortUrl(string $routeName, string $sortColumn, Request $request)
    {
        $reversDirections = [
            'ASC' => 'DESC',
            'DESC' => 'ASC',
        ];
        $getParams = $request->query->all();
        if (isset($getParams['sort']) && $getParams['sort'] === $sortColumn) {
            $getParams['sort_direction'] = $reversDirections[$getParams['sort_direction']];
        } else {
            $getParams['sort'] = $sortColumn;
            $getParams['sort_direction'] = 'ASC';
        }
        $res = http_build_query($getParams);
        return route($routeName) . "?" . $res;
    }
}
