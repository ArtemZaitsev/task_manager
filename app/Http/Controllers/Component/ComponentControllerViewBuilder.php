<?php

namespace App\Http\Controllers\Component;

use App\Models\Component\Component;
use App\Models\Component\PhysicalObject;
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
            'physicalObjectsSelectData' =>SelectUtils::entityListToLabelMap(
                PhysicalObject::all()->all(),
                fn(PhysicalObject $user) => $user->label()
            ),
            'componentsSelectData' =>SelectUtils::entityListToLabelMap(
                $entity->id === null ?
                    Component::all()->all() :
                    Component::query()->where('id', '!=', $entity->id)->get()->all(),
                fn(Component $component) => $component->label())
        ];
    }
}
