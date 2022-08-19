<?php

namespace App\Http\Controllers\TaskTree;

use App\BuisinessLogick\Voter\ProjectVoter;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TaskTreeProjectController extends Controller
{
    public const ROUTE_NAME = 'project.gantt';

    public function __construct(
        private ProjectVoter $projectVoter,
        private TaskTreeBuilder $treeBuilder
    )
    {
    }

    public function index(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $tasks = $this->treeBuilder->build($project);

        $result = [
            'tasks' => $tasks,
            'selectedRow' => 0,
            'deletedTaskIds' => [],
            "resources" => [],
            "roles" => [],
            "canAdd" => true,
            "canWrite" => true,
            "canWriteOnParent" => true,
            "zoom" => "1Q"
        ];

        return view('task/task_project_tree', [
            'project' => $project,
            'projectVoter' => $this->projectVoter,
            'tasks' => json_encode($result)
        ]);

    }








}
