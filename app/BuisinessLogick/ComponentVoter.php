<?php

namespace App\BuisinessLogick;

use App\Models\Component\Component;
use Illuminate\Support\Facades\Auth;

class ComponentVoter
{
    public const ROLE_PLANER = 'planer';
    public const ROLE_CONSTRUCTOR = 'constructor';
    public const ROLE_MANUFACTOR = 'manufactor';
    public const ROLE_PURCHASER = 'purchaser';

    public function __construct(
        private PlanerService $planerService,
        private ?bool $isPlaner = null
    )
    {
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
}
