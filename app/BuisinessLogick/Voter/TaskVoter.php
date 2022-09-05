<?php

namespace App\BuisinessLogick\Voter;

use App\BuisinessLogick\PlanerService;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;


class TaskVoter
{
    public const ROLE_PLANER = 'planer';
    public const ROLE_PERFORMER = 'performer';


    public function __construct(private PlanerService $planerService)
    {

    }

    public function canEdit(Task $task): bool
    {
        if (VoterUtils::userIsAdmin()) {
            return true;
        }

        if (Auth::id() === $task->user_id) {
            return true;
        }
        if ($this->userIsDirectionPlaner($task)) {
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
        if (VoterUtils::userIsAdmin()) {
            return true;
        }
        if ($this->userIsDirectionPlaner($task)) {
            return true;
        }
        return false;
    }

    public function userIsPlaner(): bool
    {
        return $this->planerService->userIsPlaner(Auth::id());
    }

    public function editRole(Task $task): ?string
    {
        if ($this->userIsPlaner()) {
            return self::ROLE_PLANER;
        }
        if (Auth::id() === $task->user_id) {
            return self::ROLE_PERFORMER;
        }
        return null;
    }

    private function userIsDirectionPlaner(Task $task): bool
    {
        $user = $task->user;
        if ($user === null) {
            return false;
        }
        $direction = $user->direction;
        if ($direction === null) {
            return false;
        }
        return $direction->userIsPlaner(Auth::id());
    }

}
