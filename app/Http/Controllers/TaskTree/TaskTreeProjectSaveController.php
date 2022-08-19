<?php

namespace App\Http\Controllers\TaskTree;

use App\BuisinessLogick\Voter\ProjectVoter;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TaskTreeProjectSaveController extends Controller
{
    public const ROUTE_NAME = 'project.save';

    public function __construct(
        private ProjectVoter $projectVoter,
        private ProjectSaver $projectSaver)
    {
    }

    public function save(Request $request, $id)
    {
        /** @var Project $project */
        $project = Project::query()->findOrFail($id);
        if (!$this->projectVoter->canEditGantt($project)) {
            throw new AccessDeniedHttpException();
        }

        $projectRawJson = $request->request->get('project');
        $projectJson = json_decode($projectRawJson, true);

        $this->projectSaver->saveProject($project, $projectJson);

        return response()->json([
            'ok' => true
        ]);
    }


}
