<?php

namespace App\BuisinessLogick\Voter;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectVoter
{

    public function canSeeGantt(Project $entity): bool
    {
        $allowedUserIds = [
            $entity->head_id,
            $entity->planer_id,
            ... $entity->watchers()->allRelatedIds()->toArray(),
            ... $entity->heads()->allRelatedIds()->toArray()
        ];
        return in_array(Auth::id(), $allowedUserIds);
    }

    public function canEditGantt(Project $entity): bool
    {
        return $entity->planer_id === Auth::id();
    }
}
