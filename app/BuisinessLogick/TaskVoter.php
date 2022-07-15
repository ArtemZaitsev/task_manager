<?php

namespace App\BuisinessLogick;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class TaskVoter
{
    public const ROLE_PLANER = 'planer';
    public const ROLE_PERFORMER = 'performer';

    private ?bool $isPlaner = null;
    private PlanerService $planerService;


    public function __construct()
    {
        $this->planerService = new PlanerService();

    }

    public function canEdit(Task $task): bool
    {
        if (Auth::id() === $task->user_id) {
            return true;
        }
        if ($this->userIsPlaner()) {
            return true;
        }
        return false;
    }

    public function canCreate(): bool
    {
        return $this->userIsPlaner();
    }

    public function canExport(): bool
    {
        return true;
    }

    public function canDelete(Task $task): bool
    {
        if ($this->userIsPlaner()) {
            return true;
        }
        return false;
    }

    public function userIsPlaner(): bool
    {
        if ($this->isPlaner !== null) {
            return $this->isPlaner;
        }
        $this->isPlaner = $this->planerService->userIsPlaner(Auth::id());
        return $this->isPlaner;
    }

    public function editRole(Task $task): ?string {
        if($this->userIsPlaner()) {
            return self::ROLE_PLANER;
        }
        if(Auth::id() === $task->user_id) {
            return self::ROLE_PERFORMER;
        }
        return null;
    }

}
