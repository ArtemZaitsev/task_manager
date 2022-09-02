<?php

namespace App\Http\Controllers\TaskTree;

use App\Models\Project;
use App\Models\Task;

class TaskTreeSaver
{
    public function __construct(private CommonTaskSaver $saver)
    {
    }

    public function saveTask(Task $task, Project $project, array $projectJson): void {
        $this->saver->saveProject($project, $projectJson, [$task->id]);
    }
}
