<?php

namespace App\Http\Controllers\Component;

class SelectUtils
{
    public static function entityListToLabelMap(array $entities, callable $labelMaker): array
    {
        $selectData = [];
        foreach ($entities as $entity) {
            $selectData[$entity->id] = $labelMaker($entity);
        }

        return $selectData;
    }
}
