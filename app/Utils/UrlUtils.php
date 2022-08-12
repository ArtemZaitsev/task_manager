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

    public static function newSortUrl(string $sortColumn)
    {
        $request = request();
        $routeName = $request->route()->getName();

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

    public static function newClearFilterUrl(string $filterName)
    {
        $request = request();
        $routeName = $request->route()->getName();
        $routeParameters = $request->route()->parameters();

        $filterData = $request->query->get('filters') ?? [];
        if (isset($filterData[$filterName])) {
            unset($filterData[$filterName]);
        }
        $newQuery = array_merge($request->query->all(), ['filters' => $filterData]);
        $res = http_build_query($newQuery);
        return route($routeName, $routeParameters) . "?" . $res;
    }
}
