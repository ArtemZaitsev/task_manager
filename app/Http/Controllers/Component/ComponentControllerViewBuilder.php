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
            'userSelectData' => $this->selectData(
                User::all()->all(),
                fn(User $user) => $user->label()
            ),
            'physicalObjectsSelectData' => $this->selectData(
                PhysicalObject::all()->all(),
                fn(PhysicalObject $user) => $user->label()
            ),
            'componentsSelectData' => $this->selectData(
                $entity->id === null ?
                    Component::all()->all() :
                    Component::query()->where('id', '!=', $entity->id)->get()->all(),
                fn(Component $component) => $component->label())
        ];
    }

    private function selectData(array $entities, callable $labelMaker): array
    {
        $selectData = [];
        foreach ($entities as $entity) {
            $selectData[$entity->id] = $labelMaker($entity);
        }

        return $selectData;
    }
}
