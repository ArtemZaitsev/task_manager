<?php

namespace App\Http\Controllers\Component;

use App\Lib\SelectUtils;
use App\Models\Component\Component;
use App\Models\Component\PhysicalObject;
use App\Models\Component\Sz;
use App\Models\User;

class ComponentControllerViewBuilder
{

    public function viewData(Component $entity): array
    {
        return [
            'entity' => $entity,
            'userSelectData' => SelectUtils::entityListToLabelMap(
                User::all()->all(),
                fn(User $user) => $user->label()
            ),
            'szSelectData' => SelectUtils::entityListToLabelMap(
                Sz::all()->all(),
                fn(Sz $user) => $user->label()
            ),
            'physicalObjectsSelectData' => SelectUtils::entityListToLabelMap(
                PhysicalObject::all()->all(),
                fn(PhysicalObject $user) => $user->label()
            ),
            'componentsSelectData' => SelectUtils::entityListToLabelMap(
                $entity->id === null ?
                    Component::query()
                        ->where('is_highlevel', 1)
                        ->get()
                        ->all() :
                    Component::query()
                        ->where('is_highlevel', 1)
                        ->where('id', '!=', $entity->id)
                        ->get()
                        ->all(),
                fn(Component $component) => $component->label())
        ];
    }
}
