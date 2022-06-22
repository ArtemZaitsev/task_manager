<?php

namespace App\BuisinessLogick;

use App\Models\Audit;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public function createEntity(Model $entity, array $metainf = []): void
    {
        (new Audit([
            'user_id' => Auth::id(),
            'event_type' => Audit::EVENT_TYPE_CREATE,
            'table_name' => $entity->getTable(),
            'entity_id' => $entity->id,
            'meta_inf' => $metainf,
        ]))->save();
    }

    public function editEntity(Model $entity, array $metainf = []): void
    {
        (new Audit([
            'user_id' => Auth::id(),
            'event_type' => Audit::EVENT_TYPE_EDIT,
            'table_name' => $entity->getTable(),
            'entity_id' => $entity->id,
            'meta_inf' => $metainf,
        ]))->save();
    }

    public function deleteEntity(Model $entity, array $metainf = []):void{
        (new Audit([
            'user_id' => Auth::id(),
            'event_type' => Audit::EVENT_TYPE_DELETE,
            'table_name' => $entity->getTable(),
            'entity_id' => $entity->id,
            'meta_inf' => $metainf,
        ]))->save();
    }

    public function wasManyToManyChanges(array $changes): bool
    {
        return count($changes['attached']) > 0 ||
            count($changes['updated']) > 0 ||
            count($changes['detached']) > 0;
    }

    public function editEntityRelation(array $changes, Model $entity, string $relationName): void
    {
        if ($this->wasManyToManyChanges($changes)) {
            $this->editEntity($entity, [
                'type' => $relationName,
                'changes' => $changes,
            ]);
        }
    }
}
