<?php

namespace App\Http\Controllers\TaskTree;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectSaver
{
    public function __construct(private CommonTaskSaver $saver)
    {
    }

    public function saveProject(Project $project, array $projectJson): void
    {
        $this->saver->saveProject($project, $projectJson, []);
    }
}
