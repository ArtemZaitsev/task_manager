<?php

namespace App\Http\Controllers\Component;

use App\Lib\SelectUtils;
use App\Models\Component\Component;
use App\Models\Component\DrawingFile;
use App\Models\Component\Metasystem;
use App\Models\Component\PhysicalObject;
use App\Models\Component\PurchaseOrder;
use App\Models\Component\Subsystem;
use App\Models\Component\System;
use App\Models\Component\Sz;
use App\Models\Component\TechnicalTaskCalculation;
use App\Models\User;

class ComponentControllerViewBuilder
{

    public function viewData(Component $entity): array
    {
        return [
            'entity' => $entity,
            'userSelectData' => SelectUtils::entityListToLabelMap(
                User::all()->all(),
                fn(User $entity) => $entity->label()
            ),
            'szSelectData' => SelectUtils::entityListToLabelMap(
                Sz::query()->orderBy('id', 'desc')->get()->all(),
                fn(Sz $entity) => $entity->label()
            ),
            'purchaseOrderSelectData' => SelectUtils::entityListToLabelMap(
                PurchaseOrder::query()->orderBy('id', 'desc')->get()->all(),
                fn(PurchaseOrder $entity) => $entity->label()
            ),
            'technicalTaskCalculationSelectData' => SelectUtils::entityListToLabelMap(
                TechnicalTaskCalculation::query()->orderBy('id', 'desc')->get()->all(),
                fn(TechnicalTaskCalculation $entity) => $entity->label()
            ),
            'physicalObjectsSelectData' => SelectUtils::entityListToLabelMap(
                PhysicalObject::all()->all(),
                fn(PhysicalObject $user) => $user->label()
            ),
            'metasystemsSelectData' => SelectUtils::entityListToLabelMap(
                Metasystem::all()->all(),
                fn(Metasystem $entity) => $entity->title
            ),
            'systemsSelectData' => SelectUtils::entityListToLabelMap(
                System::all()->all(),
                fn(System $entity) => $entity->title
            ),
            'subsystemsSelectData' => SelectUtils::entityListToLabelMap(
                Subsystem::all()->all(),
                fn(Subsystem $entity) => $entity->title
            ),
            'drawingFilesData' => SelectUtils::entityListToLabelMap(
                DrawingFile::all()->all(),
                fn(DrawingFile $entity) => $entity->label()
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
