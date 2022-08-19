<?php

namespace App\BuisinessLogick\Voter;

use App\BuisinessLogick\PlanerService;
use App\Models\Component\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ComponentVoter
{
    public const ROLE_PLANER = 'planer';
    public const ROLE_CONSTRUCTOR = 'constructor';
    public const ROLE_MANUFACTOR = 'manufactor';
    public const ROLE_PURCHASER = 'purchaser';

    public function __construct(
        private PlanerService $planerService,
        private ?bool         $isPlaner = null
    )
    {
    }

    public function canExport(): bool
    {
        return true;
    }

    public function canEdit(Component $entity): bool
    {
        if ($this->userIsAdmin()) {
            return true;
        }
        if ($this->userIsPlaner()) {
            return true;
        }
        if (Auth::id() === $entity->constructor_id) {
            return true;
        }
        if (Auth::id() === $entity->purchaser_id) {
            return true;
        }
        if (Auth::id() === $entity->manufactor_id) {
            return true;
        }
//
//        $direction = $entity->constructor?->direction;
//        if ($direction === null) {
//            return true;
//        }
//        $directionPlanerId = $direction->planer_id;
//        if ($directionPlanerId === null) {
//            return true;
//        }


        return false;

//        return Auth::id() === $directionPlanerId;

    }

    public function canDelete(Component $entity): bool
    {
        if ($this->userIsPlaner()) {
            return true;
        }
        return false;
    }

    public function editRole(Component $entity): ?string
    {

        if ($this->userIsPlaner()) {
            return self::ROLE_PLANER;
        }
        if (Auth::id() === $entity->constructor_id) {
            return self::ROLE_CONSTRUCTOR;
        }
        if (Auth::id() === $entity->manufactor_id) {
            return self::ROLE_MANUFACTOR;
        }
        if (Auth::id() === $entity->purchaser_id) {
            return self::ROLE_PURCHASER;
        }
        return null;
    }

    public function userIsPlaner(): bool
    {
        if ($this->isPlaner !== null) {
            return $this->isPlaner;
        }
        $this->isPlaner = $this->planerService->userIsPlaner(Auth::id());
        return $this->isPlaner;
    }

    private function userIsAdmin(): bool
    {
        $user = Auth::user();
        $permissions = $user->permissions;
        if (!isset($permissions['platform.index'])) {
            return false;
        }
        $permission = (int)$permissions['platform.index'];
        return $permission === 1;
    }


}
