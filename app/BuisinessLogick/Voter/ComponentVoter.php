<?php

namespace App\BuisinessLogick\Voter;

use App\BuisinessLogick\PlanerService;
use App\Models\Component\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ComponentVoter
{
    public const ROLE_ADMIN = 'admin';
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
        if (VoterUtils::userIsAdmin()) {
            return true;
        }
        if ($this->userIsComponentPlaner($entity)) {
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


        return false;
    }

    public function canDelete(Component $entity): bool
    {
        if (VoterUtils::userIsAdmin()) {
            return true;
        }
        if ($this->userIsComponentPlaner($entity)) {
            return true;
        }

        return false;
    }

    public function editRole(Component $entity): ?string
    {
        if (VoterUtils::userIsAdmin()) {
            return self::ROLE_ADMIN;
        }
        if ($this->userIsComponentPlaner($entity)) {
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

    private function userIsComponentPlaner(Component $entity): bool
    {
        $constructor = $entity->constructor;
        if ($constructor === null) {
            if ($this->planerService->userIsPlaner(Auth::id())) {
                return true;
            }
            return false;
        }

        $direction = $constructor->direction;
        if ($direction === null) {
            return false;
        }
        return $direction->userIsPlaner(Auth::id());
    }


}
