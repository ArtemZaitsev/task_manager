<?php

namespace App\Http\Controllers\Component;

use App\BuisinessLogick\ComponentVoter;
use App\BuisinessLogick\PlanerService;
use App\BuisinessLogick\ProjectVoter;
use App\BuisinessLogick\TaskService;
use App\BuisinessLogick\TaskVoter;
use Illuminate\Http\Request;

class ComponentController
{
    public const ROUTE_NAME = 'components.list';
    const RECORDS_PER_PAGE = 30;

    public function __construct(
        private TaskVoter     $taskVoter,
        private TaskService   $taskService,
        private PlanerService $planerService,
        private ProjectVoter  $projectVoter,
        private ComponentVoter  $componentVoter,

    )
    {

    }

    public function index(Request $request)
    {
        $grid = new ComponentGrid();
        $query = $grid->buildQuery($request);

        $components = $query->paginate(self::RECORDS_PER_PAGE)->withQueryString();

        return view('component.list', [
            'componentVoter'=>$this->componentVoter,
            'data' => $components,
            'columns' => $grid->getColumns(),
            'taskVoter' => $this->taskVoter,
            'projectVoter' => $this->projectVoter,
            'taskService' => $this->taskService,
            'planerService' => $this->planerService,
            'exportUrl' => route(ComponentExportController::EXPORT_ACTION) . '?' . http_build_query
                ($request->query->all()),
        ]);
    }


}
