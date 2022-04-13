<?php

namespace App\BuisinessLogick;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class TaskVoter
{
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
    }

    public function canDelete(Task $task): bool
    {
        if ($this->userIsPlaner()) {
            return true;
        }
        return false;
    }

    private function userIsPlaner(): bool
    {
        if ($this->isPlaner !== null) {
            return $this->isPlaner;
        }
        $this->isPlaner = $this->planerService->userIsPlaner(Auth::id());
        return $this->isPlaner;
    }

}
