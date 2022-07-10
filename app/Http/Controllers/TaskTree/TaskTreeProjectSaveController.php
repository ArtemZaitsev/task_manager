<?php

namespace App\Http\Controllers\TaskTree;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTreeProjectSaveController extends Controller
{
    public const ROUTE_NAME = 'project.save';

    public function __construct(private ProjectSaver $projectSaver)
    {
    }

    public function save(Request $request, $id)
    {
        /** @var Project $project */
        $project = Project::query()->findOrFail($id);

        $projectRawJson = $request->request->get('project');
        $projectJson = json_decode($projectRawJson, true);

       $this->projectSaver->saveProject($project, $projectJson);

        return response()->json([
            'ok' => true
        ]);
    }


}
